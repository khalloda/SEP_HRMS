<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * ZKTeco attendance push endpoint.
     *
     * This endpoint receives attendance data from ZKTeco biometric devices
     * and processes them for the HRMS system.
     *
     * Expected JSON payload:
     * {
     *     "device_id": "ZK001",
     *     "records": [
     *         {
     *             "employee_id": "EMP001",
     *             "timestamp": "2024-01-15 08:30:00",
     *             "type": "check_in",
     *             "device_info": "ZKTeco F18"
     *         }
     *     ],
     *     "signature": "hmac_signature"
     * }
     */
    public function pushAttendance(Request $request)
    {
        try {
            // Validate HMAC signature for security
            if (!$this->validateHmacSignature($request)) {
                Log::warning('Invalid HMAC signature for attendance push', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid authentication signature',
                    'error_code' => 'INVALID_SIGNATURE'
                ], 401);
            }

            // Validate input data
            $validator = Validator::make($request->all(), [
                'device_id' => 'required|string|max:50',
                'records' => 'required|array|min:1|max:1000',
                'records.*.employee_id' => 'required|string|max:50',
                'records.*.timestamp' => 'required|date_format:Y-m-d H:i:s',
                'records.*.type' => 'required|in:check_in,check_out,break_start,break_end',
                'records.*.device_info' => 'nullable|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'error_code' => 'VALIDATION_ERROR'
                ], 422);
            }

            $deviceId = $request->input('device_id');
            $records = $request->input('records');

            $processedCount = 0;
            $errorCount = 0;
            $errors = [];

            // Process each attendance record
            foreach ($records as $index => $record) {
                try {
                    $result = $this->processAttendanceRecord($deviceId, $record);

                    if ($result['success']) {
                        $processedCount++;
                    } else {
                        $errorCount++;
                        $errors[] = [
                            'record_index' => $index,
                            'employee_id' => $record['employee_id'],
                            'error' => $result['error']
                        ];
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = [
                        'record_index' => $index,
                        'employee_id' => $record['employee_id'],
                        'error' => 'Processing error: ' . $e->getMessage()
                    ];

                    Log::error('Attendance record processing error', [
                        'record' => $record,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Log the push activity
            Log::info('ZKTeco attendance push processed', [
                'device_id' => $deviceId,
                'total_records' => count($records),
                'processed_count' => $processedCount,
                'error_count' => $errorCount,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance data processed',
                'statistics' => [
                    'total_records' => count($records),
                    'processed_successfully' => $processedCount,
                    'errors' => $errorCount
                ],
                'errors' => $errorCount > 0 ? $errors : null,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('ZKTeco attendance push failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error_code' => 'INTERNAL_ERROR'
            ], 500);
        }
    }

    /**
     * Process individual attendance record.
     */
    protected function processAttendanceRecord(string $deviceId, array $record): array
    {
        try {
            $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $record['timestamp']);

            // Create attendance record from ZKTeco data
            $attendanceRecord = AttendanceRecord::createFromZKTecoData([
                'employee_id' => $record['employee_id'],
                'timestamp' => $timestamp,
                'type' => $record['type'],
                'device_id' => $deviceId,
                'device_info' => $record['device_info'] ?? null
            ]);

            // Calculate/update daily summary for this employee and date
            $summary = AttendanceSummary::calculateFromRecords(
                $attendanceRecord->employee_id,
                $timestamp->toDateString()
            );

            // Log successful attendance record
            activity('attendance')
                ->causedBy(null) // System activity
                ->performedOn($attendanceRecord->employee)
                ->withProperties([
                    'device_id' => $deviceId,
                    'attendance_type' => $record['type'],
                    'timestamp' => $timestamp->toISOString(),
                    'device_info' => $record['device_info'] ?? null,
                    'record_status' => $attendanceRecord->status
                ])
                ->log("Attendance {$record['type']} recorded from device {$deviceId}");

            return [
                'success' => true,
                'employee_id' => $attendanceRecord->employee_id,
                'employee_code' => $attendanceRecord->employee_code,
                'employee_name' => $attendanceRecord->employee?->display_name,
                'record_status' => $attendanceRecord->status,
                'summary_updated' => $summary ? true : false
            ];

        } catch (\Exception $e) {
            Log::error('Attendance record processing failed', [
                'record' => $record,
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate HMAC signature for secure communication.
     */
    protected function validateHmacSignature(Request $request): bool
    {
        $signature = $request->header('X-Signature') ?: $request->input('signature');

        if (!$signature) {
            return false;
        }

        // Get the shared secret from config
        $secret = config('services.zkteco.hmac_secret', 'your-secure-hmac-secret-key');

        // Calculate expected signature
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        // Compare signatures securely
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Get attendance statistics for API consumers.
     */
    public function getAttendanceStats(Request $request)
    {
        // Validate API authentication
        if (!$this->validateApiKey($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }

        try {
            $date = $request->input('date', now()->toDateString());
            $carbonDate = Carbon::parse($date);

            // Get real attendance statistics
            $totalEmployees = Employee::where('status', 'active')->count();

            // Get daily summaries for the date
            $summaries = AttendanceSummary::forDate($date)->get();

            // Calculate statistics
            $presentCount = $summaries->where('status', 'present')->count();
            $absentCount = $summaries->where('status', 'absent')->count();
            $partialCount = $summaries->where('status', 'partial')->count();
            $lateCount = $summaries->where('late_minutes', '>', 0)->count();
            $overtimeCount = $summaries->where('overtime_minutes', '>', 0)->count();

            // Get latest check-ins/check-outs for today
            $latestCheckIns = AttendanceRecord::valid()
                ->forDate($date)
                ->checkIns()
                ->count();

            $latestCheckOuts = AttendanceRecord::valid()
                ->forDate($date)
                ->checkOuts()
                ->count();

            $stats = [
                'date' => $date,
                'total_employees' => $totalEmployees,
                'present' => $presentCount,
                'absent' => $absentCount,
                'partial' => $partialCount,
                'checked_in_today' => $latestCheckIns,
                'checked_out_today' => $latestCheckOuts,
                'late_arrivals' => $lateCount,
                'overtime_workers' => $overtimeCount,
                'total_work_hours' => round($summaries->sum('total_work_minutes') / 60, 2),
                'total_overtime_hours' => round($summaries->sum('overtime_minutes') / 60, 2),
                'anomalies_detected' => $summaries->filter(function ($s) {
                    return !empty($s->anomalies);
                })->count(),
                'timestamp' => now()->toISOString()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance stats API error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance statistics'
            ], 500);
        }
    }

    /**
     * Health check endpoint for ZKTeco devices.
     */
    public function healthCheck()
    {
        return response()->json([
            'status' => 'healthy',
            'service' => 'HRMS Attendance API',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString(),
            'endpoints' => [
                'push_attendance' => route('api.attendance.push'),
                'get_stats' => route('api.attendance.stats'),
                'health_check' => route('api.attendance.health')
            ]
        ]);
    }

    /**
     * Validate API key for protected endpoints.
     */
    protected function validateApiKey(Request $request): bool
    {
        $apiKey = $request->header('X-API-Key') ?: $request->input('api_key');
        $validApiKey = config('services.zkteco.api_key', 'your-api-key-here');

        return $apiKey && hash_equals($validApiKey, $apiKey);
    }

    /**
     * Employee lookup endpoint for ZKTeco device configuration.
     */
    public function getEmployeeList(Request $request)
    {
        if (!$this->validateApiKey($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }

        try {
            $employees = Employee::where('status', 'active')
                              ->select('id', 'code', 'first_name', 'last_name', 'department_id')
                              ->with('department:id,name_en,name_ar')
                              ->orderBy('code')
                              ->get()
                              ->map(function ($employee) {
                                  return [
                                      'employee_id' => $employee->code,
                                      'name' => $employee->display_name,
                                      'department' => $employee->department?->name_en ?? 'N/A',
                                      'status' => 'active'
                                  ];
                              });

            return response()->json([
                'success' => true,
                'data' => $employees,
                'count' => $employees->count(),
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Employee list API error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve employee list'
            ], 500);
        }
    }

    /**
     * Batch calculate attendance summaries for a date range.
     */
    public function batchCalculateSummaries(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $employeeIds = $request->employee_ids;

            // Get employees to process
            $employeesQuery = Employee::where('status', 'active');
            if ($employeeIds) {
                $employeesQuery->whereIn('id', $employeeIds);
            }
            $employees = $employeesQuery->get();

            $processedCount = 0;
            $errors = [];

            // Process each employee for each date
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                foreach ($employees as $employee) {
                    try {
                        AttendanceSummary::calculateFromRecords($employee->id, $date->toDateString());
                        $processedCount++;
                    } catch (\Exception $e) {
                        $errors[] = [
                            'employee_id' => $employee->id,
                            'employee_code' => $employee->code,
                            'date' => $date->toDateString(),
                            'error' => $e->getMessage()
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Batch calculation completed',
                'processed_count' => $processedCount,
                'error_count' => count($errors),
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Batch attendance calculation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Batch calculation failed'
            ], 500);
        }
    }

    /**
     * Get attendance report for date range.
     */
    public function getAttendanceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
            'report_type' => 'required|in:summary,detailed,anomalies,overtime'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $employeeIds = $request->employee_ids;
            $reportType = $request->report_type;

            // Base query for attendance summaries
            $query = AttendanceSummary::dateRange($startDate, $endDate)
                ->with(['employee:id,code,first_name,last_name,department_id', 'employee.department:id,name_en,name_ar']);

            if ($employeeIds) {
                $query->whereIn('employee_id', $employeeIds);
            }

            switch ($reportType) {
                case 'summary':
                    $data = $this->generateSummaryReport($query);
                    break;
                case 'detailed':
                    $data = $this->generateDetailedReport($query);
                    break;
                case 'anomalies':
                    $data = $this->generateAnomaliesReport($query);
                    break;
                case 'overtime':
                    $data = $this->generateOvertimeReport($query);
                    break;
            }

            return response()->json([
                'success' => true,
                'report_type' => $reportType,
                'date_range' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'data' => $data,
                'generated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance report generation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Report generation failed'
            ], 500);
        }
    }

    /**
     * Generate summary report data.
     */
    protected function generateSummaryReport($query)
    {
        $summaries = $query->get();

        // Group by employee
        $employeeStats = $summaries->groupBy('employee_id')->map(function ($employeeSummaries, $employeeId) {
            $employee = $employeeSummaries->first()->employee;

            return [
                'employee_id' => $employeeId,
                'employee_code' => $employee->code,
                'employee_name' => $employee->display_name,
                'department' => $employee->department?->name_en,
                'total_days' => $employeeSummaries->count(),
                'present_days' => $employeeSummaries->where('status', 'present')->count(),
                'absent_days' => $employeeSummaries->where('status', 'absent')->count(),
                'partial_days' => $employeeSummaries->where('status', 'partial')->count(),
                'late_days' => $employeeSummaries->where('late_minutes', '>', 0)->count(),
                'overtime_days' => $employeeSummaries->where('overtime_minutes', '>', 0)->count(),
                'total_work_hours' => round($employeeSummaries->sum('total_work_minutes') / 60, 2),
                'total_overtime_hours' => round($employeeSummaries->sum('overtime_minutes') / 60, 2),
                'total_late_minutes' => $employeeSummaries->sum('late_minutes'),
                'attendance_rate' => $employeeSummaries->count() > 0 ?
                    round(($employeeSummaries->where('status', '!=', 'absent')->count() / $employeeSummaries->count()) * 100, 2) : 0
            ];
        })->values();

        return $employeeStats;
    }

    /**
     * Generate detailed report data.
     */
    protected function generateDetailedReport($query)
    {
        return $query->get()->map(function ($summary) {
            return [
                'date' => $summary->date->toDateString(),
                'employee_code' => $summary->employee_code,
                'employee_name' => $summary->employee?->display_name,
                'department' => $summary->employee?->department?->name_en,
                'first_check_in' => $summary->first_check_in?->format('H:i:s'),
                'last_check_out' => $summary->last_check_out?->format('H:i:s'),
                'total_work_time' => $summary->formatted_work_time,
                'break_time' => $summary->formatted_break_time,
                'late_minutes' => $summary->late_minutes,
                'overtime_minutes' => $summary->overtime_minutes,
                'status' => $summary->status_name,
                'anomalies' => $summary->anomalies ?? []
            ];
        });
    }

    /**
     * Generate anomalies report data.
     */
    protected function generateAnomaliesReport($query)
    {
        return $query->withAnomalies()->get()->map(function ($summary) {
            return [
                'date' => $summary->date->toDateString(),
                'employee_code' => $summary->employee_code,
                'employee_name' => $summary->employee?->display_name,
                'department' => $summary->employee?->department?->name_en,
                'anomalies' => $summary->anomalies,
                'first_check_in' => $summary->first_check_in?->format('H:i:s'),
                'last_check_out' => $summary->last_check_out?->format('H:i:s'),
                'status' => $summary->status_name
            ];
        });
    }

    /**
     * Generate overtime report data.
     */
    protected function generateOvertimeReport($query)
    {
        return $query->withOvertime()->get()->map(function ($summary) {
            return [
                'date' => $summary->date->toDateString(),
                'employee_code' => $summary->employee_code,
                'employee_name' => $summary->employee?->display_name,
                'department' => $summary->employee?->department?->name_en,
                'position' => $summary->employee?->position?->name_en,
                'overtime_eligible' => $summary->employee?->position?->is_overtime_eligible ?? false,
                'total_work_time' => $summary->formatted_work_time,
                'overtime_minutes' => $summary->overtime_minutes,
                'overtime_hours' => round($summary->overtime_minutes / 60, 2),
                'first_check_in' => $summary->first_check_in?->format('H:i:s'),
                'last_check_out' => $summary->last_check_out?->format('H:i:s')
            ];
        });
    }

    /**
     * Manual attendance adjustment (for HR use).
     */
    public function adjustAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'adjustments' => 'required|array',
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employeeId = $request->employee_id;
            $date = $request->date;
            $adjustments = $request->adjustments;
            $reason = $request->reason;

            // Get or create attendance summary
            $summary = AttendanceSummary::firstOrNew([
                'employee_id' => $employeeId,
                'date' => $date
            ]);

            // Apply manual adjustments
            foreach ($adjustments as $field => $value) {
                if (in_array($field, $summary->getFillable())) {
                    $summary->$field = $value;
                }
            }

            // Add adjustment note
            $currentNotes = $summary->notes ?? '';
            $adjustmentNote = now()->format('Y-m-d H:i:s') . " - Manual adjustment: {$reason}";
            $summary->notes = $currentNotes ? $currentNotes . "\n" . $adjustmentNote : $adjustmentNote;

            $summary->save();

            // Log the manual adjustment
            activity('attendance')
                ->performedOn($summary)
                ->withProperties([
                    'adjustments' => $adjustments,
                    'reason' => $reason,
                    'adjusted_by' => auth()->user()?->id
                ])
                ->log('Manual attendance adjustment applied');

            return response()->json([
                'success' => true,
                'message' => 'Attendance adjusted successfully',
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            Log::error('Manual attendance adjustment failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Attendance adjustment failed'
            ], 500);
        }
    }
}