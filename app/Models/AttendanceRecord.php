<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'employee_code',
        'timestamp',
        'type',
        'device_id',
        'device_info',
        'raw_data',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'raw_data' => 'array',
    ];

    /**
     * Attendance record types.
     */
    public const TYPES = [
        'check_in' => 'Check In',
        'check_out' => 'Check Out',
        'break_start' => 'Break Start',
        'break_end' => 'Break End',
    ];

    /**
     * Attendance record statuses.
     */
    public const STATUSES = [
        'valid' => 'Valid',
        'invalid' => 'Invalid',
        'duplicate' => 'Duplicate',
        'anomaly' => 'Anomaly',
    ];

    /**
     * Get the employee that owns the attendance record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Check if record is valid.
     */
    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    /**
     * Check if record is a check-in.
     */
    public function isCheckIn(): bool
    {
        return $this->type === 'check_in';
    }

    /**
     * Check if record is a check-out.
     */
    public function isCheckOut(): bool
    {
        return $this->type === 'check_out';
    }

    /**
     * Check if record is break-related.
     */
    public function isBreakRecord(): bool
    {
        return in_array($this->type, ['break_start', 'break_end']);
    }

    /**
     * Get localized type name.
     */
    public function getTypeNameAttribute()
    {
        return __('hrms.attendance.types.' . $this->type) ?: (self::TYPES[$this->type] ?? $this->type);
    }

    /**
     * Get localized status name.
     */
    public function getStatusNameAttribute()
    {
        return __('hrms.attendance.statuses.' . $this->status) ?: (self::STATUSES[$this->status] ?? $this->status);
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
        $carbonDate = Carbon::parse($date);
        return $query->whereDate('timestamp', $carbonDate);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get valid records only.
     */
    public function scopeValid(Builder $query)
    {
        return $query->where('status', 'valid');
    }

    /**
     * Scope to get anomalies.
     */
    public function scopeAnomalies(Builder $query)
    {
        return $query->where('status', 'anomaly');
    }

    /**
     * Scope to get check-ins only.
     */
    public function scopeCheckIns(Builder $query)
    {
        return $query->where('type', 'check_in');
    }

    /**
     * Scope to get check-outs only.
     */
    public function scopeCheckOuts(Builder $query)
    {
        return $query->where('type', 'check_out');
    }

    /**
     * Create attendance record from ZKTeco data.
     */
    public static function createFromZKTecoData(array $data)
    {
        // Find employee by code
        $employee = Employee::where('code', $data['employee_id'])->first();

        if (!$employee) {
            throw new \Exception("Employee not found with code: " . $data['employee_id']);
        }

        // Check for duplicates
        $existing = static::where('employee_id', $employee->id)
            ->where('timestamp', $data['timestamp'])
            ->where('type', $data['type'])
            ->first();

        if ($existing) {
            $existing->update(['status' => 'duplicate']);
            return $existing;
        }

        return static::create([
            'employee_id' => $employee->id,
            'employee_code' => $employee->code,
            'timestamp' => $data['timestamp'],
            'type' => $data['type'],
            'device_id' => $data['device_id'] ?? null,
            'device_info' => $data['device_info'] ?? null,
            'raw_data' => $data,
            'status' => 'valid',
        ]);
    }

    /**
     * Mark record as anomaly.
     */
    public function markAsAnomaly($reason = null)
    {
        $this->update([
            'status' => 'anomaly',
            'notes' => $reason ? "Anomaly: {$reason}" : 'Anomaly detected'
        ]);

        activity('attendance')
            ->performedOn($this)
            ->withProperties(['reason' => $reason])
            ->log('Attendance record marked as anomaly');

        return $this;
    }

    /**
     * Get daily attendance records for employee.
     */
    public static function getDailyRecords($employeeId, $date)
    {
        return static::forEmployee($employeeId)
            ->forDate($date)
            ->valid()
            ->orderBy('timestamp')
            ->get();
    }

    /**
     * Get latest record for employee.
     */
    public static function getLatestForEmployee($employeeId)
    {
        return static::forEmployee($employeeId)
            ->valid()
            ->orderBy('timestamp', 'desc')
            ->first();
    }

    /**
     * Detect anomalies in attendance records.
     */
    public static function detectAnomalies($employeeId, $date)
    {
        $records = static::getDailyRecords($employeeId, $date);
        $anomalies = [];

        if ($records->isEmpty()) {
            return $anomalies;
        }

        // Check for multiple consecutive check-ins without check-out
        $lastType = null;
        foreach ($records as $record) {
            if ($lastType === 'check_in' && $record->type === 'check_in') {
                $anomalies[] = 'Multiple check-ins without check-out';
                $record->markAsAnomaly('Multiple check-ins detected');
            }

            if ($lastType === 'check_out' && $record->type === 'check_out') {
                $anomalies[] = 'Multiple check-outs without check-in';
                $record->markAsAnomaly('Multiple check-outs detected');
            }

            $lastType = $record->type;
        }

        // Check for very short work periods (less than 1 hour)
        $checkIns = $records->where('type', 'check_in');
        $checkOuts = $records->where('type', 'check_out');

        if ($checkIns->isNotEmpty() && $checkOuts->isNotEmpty()) {
            $firstCheckIn = $checkIns->first()->timestamp;
            $lastCheckOut = $checkOuts->last()->timestamp;

            $workMinutes = $firstCheckIn->diffInMinutes($lastCheckOut);
            if ($workMinutes < 60) {
                $anomalies[] = 'Very short work period (less than 1 hour)';
            }
        }

        return $anomalies;
    }

    /**
     * Activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['employee_code', 'timestamp', 'type', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Attendance record {$eventName}")
            ->useLogName('attendance');
    }
}