<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class AttendanceSummary extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'employee_code',
        'date',
        'expected_start_time',
        'expected_end_time',
        'first_check_in',
        'last_check_out',
        'total_work_minutes',
        'break_minutes',
        'late_minutes',
        'early_departure_minutes',
        'overtime_minutes',
        'is_absent',
        'is_holiday',
        'status',
        'notes',
        'anomalies',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date' => 'date',
        'expected_start_time' => 'datetime:H:i:s',
        'expected_end_time' => 'datetime:H:i:s',
        'first_check_in' => 'datetime',
        'last_check_out' => 'datetime',
        'is_absent' => 'boolean',
        'is_holiday' => 'boolean',
        'anomalies' => 'array',
    ];

    /**
     * Summary status constants.
     */
    public const STATUSES = [
        'present' => 'Present',
        'absent' => 'Absent',
        'partial' => 'Partial',
        'holiday' => 'Holiday',
        'leave' => 'On Leave',
    ];

    /**
     * Get the employee that owns the attendance summary.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get attendance records for this day.
     */
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'employee_id', 'employee_id')
            ->whereDate('timestamp', $this->date);
    }

    /**
     * Check if employee was present.
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * Check if employee was absent.
     */
    public function isAbsent(): bool
    {
        return $this->status === 'absent' || $this->is_absent;
    }

    /**
     * Check if it was a holiday.
     */
    public function isHoliday(): bool
    {
        return $this->is_holiday || $this->status === 'holiday';
    }

    /**
     * Check if employee was late.
     */
    public function isLate(): bool
    {
        return $this->late_minutes > 0;
    }

    /**
     * Check if employee left early.
     */
    public function leftEarly(): bool
    {
        return $this->early_departure_minutes > 0;
    }

    /**
     * Check if employee worked overtime.
     */
    public function hasOvertime(): bool
    {
        return $this->overtime_minutes > 0;
    }

    /**
     * Check if there are anomalies.
     */
    public function hasAnomalies(): bool
    {
        return !empty($this->anomalies);
    }

    /**
     * Get formatted total work time.
     */
    public function getFormattedWorkTimeAttribute()
    {
        return $this->formatMinutes($this->total_work_minutes);
    }

    /**
     * Get formatted break time.
     */
    public function getFormattedBreakTimeAttribute()
    {
        return $this->formatMinutes($this->break_minutes);
    }

    /**
     * Get formatted overtime.
     */
    public function getFormattedOvertimeAttribute()
    {
        return $this->formatMinutes($this->overtime_minutes);
    }

    /**
     * Get localized status name.
     */
    public function getStatusNameAttribute()
    {
        return __('hrms.attendance.statuses.' . $this->status) ?: (self::STATUSES[$this->status] ?? $this->status);
    }

    /**
     * Format minutes to hours:minutes format.
     */
    private function formatMinutes($minutes)
    {
        if ($minutes <= 0) {
            return '00:00';
        }

        $hours = intval($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $mins);
    }

    /**
     * Scope to filter by employee.
     */
    public function scopeForEmployee(Builder $query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to filter by employee code.
     */
    public function scopeForEmployeeCode(Builder $query, $employeeCode)
    {
        return $query->where('employee_code', $employeeCode);
    }

    /**
     * Scope to filter by date.
     */
    public function scopeForDate(Builder $query, $date)
    {
        return $query->whereDate('date', Carbon::parse($date));
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [
            Carbon::parse($startDate)->toDateString(),
            Carbon::parse($endDate)->toDateString()
        ]);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get present employees.
     */
    public function scopePresent(Builder $query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope to get absent employees.
     */
    public function scopeAbsent(Builder $query)
    {
        return $query->where('status', 'absent');
    }

    /**
     * Scope to get late arrivals.
     */
    public function scopeLate(Builder $query)
    {
        return $query->where('late_minutes', '>', 0);
    }

    /**
     * Scope to get overtime records.
     */
    public function scopeWithOvertime(Builder $query)
    {
        return $query->where('overtime_minutes', '>', 0);
    }

    /**
     * Scope to get records with anomalies.
     */
    public function scopeWithAnomalies(Builder $query)
    {
        return $query->whereNotNull('anomalies')
            ->where('anomalies', '!=', '[]');
    }

    /**
     * Calculate and create/update attendance summary from records.
     */
    public static function calculateFromRecords($employeeId, $date)
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return null;
        }

        $carbonDate = Carbon::parse($date);
        $records = AttendanceRecord::getDailyRecords($employeeId, $date);

        // Get existing summary or create new
        $summary = static::firstOrNew([
            'employee_id' => $employeeId,
            'date' => $carbonDate->toDateString(),
        ]);

        $summary->employee_code = $employee->code;

        // Set expected work hours (default 9:00-17:00)
        $summary->expected_start_time = '09:00:00';
        $summary->expected_end_time = '17:00:00';

        if ($records->isEmpty()) {
            // No records = absent
            $summary->fill([
                'first_check_in' => null,
                'last_check_out' => null,
                'total_work_minutes' => 0,
                'break_minutes' => 0,
                'late_minutes' => 0,
                'early_departure_minutes' => 0,
                'overtime_minutes' => 0,
                'is_absent' => true,
                'status' => 'absent',
                'anomalies' => [],
            ]);
        } else {
            // Calculate from records
            $checkIns = $records->where('type', 'check_in');
            $checkOuts = $records->where('type', 'check_out');
            $breakStarts = $records->where('type', 'break_start');
            $breakEnds = $records->where('type', 'break_end');

            $firstCheckIn = $checkIns->first();
            $lastCheckOut = $checkOuts->last();

            $summary->first_check_in = $firstCheckIn?->timestamp;
            $summary->last_check_out = $lastCheckOut?->timestamp;
            $summary->is_absent = false;

            // Calculate total work time
            $totalWorkMinutes = 0;
            if ($firstCheckIn && $lastCheckOut) {
                $totalWorkMinutes = $firstCheckIn->timestamp->diffInMinutes($lastCheckOut->timestamp);
            }

            // Calculate break time
            $breakMinutes = 0;
            if ($breakStarts->isNotEmpty() && $breakEnds->isNotEmpty()) {
                foreach ($breakStarts as $breakStart) {
                    $breakEnd = $breakEnds->where('timestamp', '>', $breakStart->timestamp)->first();
                    if ($breakEnd) {
                        $breakMinutes += $breakStart->timestamp->diffInMinutes($breakEnd->timestamp);
                    }
                }
            }

            // Subtract break time from work time
            $totalWorkMinutes = max(0, $totalWorkMinutes - $breakMinutes);

            // Calculate late minutes
            $lateMinutes = 0;
            if ($firstCheckIn) {
                $expectedStart = $carbonDate->copy()->setTimeFromTimeString($summary->expected_start_time);
                if ($firstCheckIn->timestamp->gt($expectedStart)) {
                    $lateMinutes = $expectedStart->diffInMinutes($firstCheckIn->timestamp);
                }
            }

            // Calculate early departure
            $earlyDepartureMinutes = 0;
            if ($lastCheckOut) {
                $expectedEnd = $carbonDate->copy()->setTimeFromTimeString($summary->expected_end_time);
                if ($lastCheckOut->timestamp->lt($expectedEnd)) {
                    $earlyDepartureMinutes = $lastCheckOut->timestamp->diffInMinutes($expectedEnd);
                }
            }

            // Calculate overtime (only for eligible positions)
            $overtimeMinutes = 0;
            if ($employee->position && $employee->position->is_overtime_eligible) {
                $expectedWorkMinutes = 8 * 60; // 8 hours
                if ($totalWorkMinutes > $expectedWorkMinutes) {
                    $overtimeMinutes = $totalWorkMinutes - $expectedWorkMinutes;
                }
            }

            // Detect anomalies
            $anomalies = AttendanceRecord::detectAnomalies($employeeId, $date);

            // Determine status
            $status = 'present';
            if ($totalWorkMinutes < 240) { // Less than 4 hours
                $status = 'partial';
            }

            $summary->fill([
                'total_work_minutes' => $totalWorkMinutes,
                'break_minutes' => $breakMinutes,
                'late_minutes' => $lateMinutes,
                'early_departure_minutes' => $earlyDepartureMinutes,
                'overtime_minutes' => $overtimeMinutes,
                'status' => $status,
                'anomalies' => $anomalies,
            ]);
        }

        $summary->save();

        // Log significant events
        if ($summary->isLate() && $summary->late_minutes > 30) {
            activity('attendance')
                ->performedOn($summary)
                ->log("Employee was significantly late ({$summary->late_minutes} minutes)");
        }

        if ($summary->hasOvertime() && $summary->overtime_minutes > 120) {
            activity('attendance')
                ->performedOn($summary)
                ->log("Employee worked significant overtime ({$summary->overtime_minutes} minutes)");
        }

        return $summary;
    }

    /**
     * Get monthly summary statistics.
     */
    public static function getMonthlyStats($employeeId, $year, $month)
    {
        $summaries = static::forEmployee($employeeId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        return [
            'total_days' => $summaries->count(),
            'present_days' => $summaries->where('status', 'present')->count(),
            'absent_days' => $summaries->where('status', 'absent')->count(),
            'partial_days' => $summaries->where('status', 'partial')->count(),
            'late_days' => $summaries->where('late_minutes', '>', 0)->count(),
            'overtime_days' => $summaries->where('overtime_minutes', '>', 0)->count(),
            'total_work_hours' => $summaries->sum('total_work_minutes') / 60,
            'total_overtime_hours' => $summaries->sum('overtime_minutes') / 60,
            'total_late_minutes' => $summaries->sum('late_minutes'),
            'anomaly_count' => $summaries->filter(function ($s) {
                return !empty($s->anomalies);
            })->count(),
        ];
    }

    /**
     * Activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['employee_code', 'date', 'status', 'total_work_minutes', 'late_minutes', 'overtime_minutes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Attendance summary {$eventName}")
            ->useLogName('attendance');
    }
}