<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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
            // Find employee by employee ID (code field)
            $employee = Employee::where('code', $record['employee_id'])
                             ->where('status', 'active')
                             ->first();

            if (!$employee) {
                return [
                    'success' => false,
                    'error' => "Employee with ID {$record['employee_id']} not found or inactive"
                ];
            }

            $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $record['timestamp']);
            $type = $record['type'];
            $deviceInfo = $record['device_info'] ?? null;

            // Here you would normally save to an attendance table
            // For now, we'll log the activity as this table doesn't exist yet

            // Log successful attendance record
            activity()
                ->causedBy(null) // System activity
                ->performedOn($employee)
                ->withProperties([
                    'device_id' => $deviceId,
                    'attendance_type' => $type,
                    'timestamp' => $timestamp->toISOString(),
                    'device_info' => $deviceInfo
                ])
                ->log("Attendance {$type} recorded from device {$deviceId}");

            return [
                'success' => true,
                'employee_id' => $employee->id,
                'employee_name' => $employee->display_name
            ];

        } catch (\Exception $e) {
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

            // Since we don't have attendance table yet, return mock data
            $stats = [
                'date' => $date,
                'total_employees' => Employee::where('status', 'active')->count(),
                'checked_in' => 0, // Would query attendance table
                'checked_out' => 0, // Would query attendance table
                'on_break' => 0, // Would query attendance table
                'absent' => 0, // Would calculate based on attendance records
                'late_arrivals' => 0, // Would calculate based on shift times
                'early_departures' => 0, // Would calculate based on shift times
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
}