<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model
{
    use LogsActivity;

    protected $fillable = [
        'employee_id',
        'contract_id',
        'type',
        'path',
        'original_name',
        'mime',
        'checksum',
        'visibility',
        'expires_at',
        'version_current',
        'watermark_note',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'version_current' => 'integer',
    ];

    protected $dates = [
        'expires_at',
    ];

    /**
     * Document types with their display names and icons.
     */
    public const TYPES = [
        'id_card' => [
            'name_en' => 'National ID Card',
            'name_ar' => 'بطاقة الهوية الوطنية',
            'icon' => 'fas fa-id-card',
            'category' => 'personal'
        ],
        'bar_license' => [
            'name_en' => 'Bar License',
            'name_ar' => 'رخصة المحاماة',
            'icon' => 'fas fa-certificate',
            'category' => 'professional'
        ],
        'bar_registration' => [
            'name_en' => 'Bar Association Registration',
            'name_ar' => 'تسجيل نقابة المحامين',
            'icon' => 'fas fa-stamp',
            'category' => 'professional',
            'requires_expiry' => true,
            'auto_reminder' => true
        ],
        'contract_pdf' => [
            'name_en' => 'Employment Contract',
            'name_ar' => 'عقد العمل',
            'icon' => 'fas fa-file-contract',
            'category' => 'contract'
        ],
        'payslip_pdf' => [
            'name_en' => 'Payslip',
            'name_ar' => 'قسيمة الراتب',
            'icon' => 'fas fa-money-check',
            'category' => 'payroll'
        ],
        'hr_letter' => [
            'name_en' => 'HR Letter',
            'name_ar' => 'خطاب الموارد البشرية',
            'icon' => 'fas fa-envelope',
            'category' => 'correspondence'
        ],
        'certificate' => [
            'name_en' => 'Certificate',
            'name_ar' => 'شهادة',
            'icon' => 'fas fa-award',
            'category' => 'professional'
        ],
        'passport' => [
            'name_en' => 'Passport',
            'name_ar' => 'جواز السفر',
            'icon' => 'fas fa-passport',
            'category' => 'personal'
        ],
        'other' => [
            'name_en' => 'Other Document',
            'name_ar' => 'مستند آخر',
            'icon' => 'fas fa-file',
            'category' => 'general'
        ],
    ];

    /**
     * Get the employee that owns the document.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the contract that owns the document.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the document versions.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_no', 'desc');
    }

    /**
     * Get the tags for the document.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tag');
    }

    /**
     * Scope: Filter by document type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter by employee.
     */
    public function scopeForEmployee(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope: Filter by contract.
     */
    public function scopeForContract(Builder $query, int $contractId): Builder
    {
        return $query->where('contract_id', $contractId);
    }

    /**
     * Scope: Only private documents.
     */
    public function scopePrivate(Builder $query): Builder
    {
        return $query->where('visibility', 'private');
    }

    /**
     * Scope: Only shared documents.
     */
    public function scopeShared(Builder $query): Builder
    {
        return $query->where('visibility', 'shared');
    }

    /**
     * Scope: Documents expiring soon.
     */
    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    /**
     * Scope: Expired documents.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }

    /**
     * Scope: Search documents by name or type.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('original_name', 'like', "%{$term}%")
              ->orWhere('type', 'like', "%{$term}%")
              ->orWhereHas('employee', function ($eq) use ($term) {
                  $eq->where('first_name', 'like', "%{$term}%")
                     ->orWhere('last_name', 'like', "%{$term}%")
                     ->orWhere('code', 'like', "%{$term}%");
              });
        });
    }

    /**
     * Get the display name for the document type.
     */
    public function getTypeDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        $key = $locale === 'ar' ? 'name_ar' : 'name_en';
        
        return self::TYPES[$this->type][$key] ?? $this->type;
    }

    /**
     * Get the icon for the document type.
     */
    public function getTypeIconAttribute(): string
    {
        return self::TYPES[$this->type]['icon'] ?? 'fas fa-file';
    }

    /**
     * Get the category for the document type.
     */
    public function getTypeCategoryAttribute(): string
    {
        return self::TYPES[$this->type]['category'] ?? 'general';
    }

    /**
     * Check if the document is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the document is expiring soon.
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expires_at && 
               $this->expires_at->isFuture() && 
               $this->expires_at->diffInDays(now()) <= 30;
    }

    /**
     * Get the file size in a readable format.
     */
    public function getFileSizeAttribute(): string
    {
        if (!Storage::exists($this->path)) {
            return '0 B';
        }

        $bytes = Storage::size($this->path);
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' B';
    }

    /**
     * Get the owner name (employee or contract).
     */
    public function getOwnerNameAttribute(): string
    {
        if ($this->employee) {
            return $this->employee->display_name;
        }
        
        if ($this->contract) {
            return $this->contract->employee->display_name ?? 'Unknown';
        }
        
        return 'System';
    }

    /**
     * Check if the current user can view this document.
     */
    public function canView($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        // HR and IT admins can view all documents
        if ($user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin'])) {
            return true;
        }

        // HR coordinators can view all except payslips (unless they're the employee)
        if ($user->hasRole('HR_Coordinator')) {
            if ($this->type === 'payslip_pdf' && $this->employee_id !== $user->employee_id) {
                return false;
            }
            return true;
        }

        // Accounting can view payslips and financial documents
        if ($user->hasAnyRole(['Accounting_Manager', 'Accountant'])) {
            return in_array($this->type, ['payslip_pdf', 'contract_pdf']);
        }

        // Employees can only view their own documents
        return $this->employee_id === $user->employee_id;
    }

    /**
     * Create a new version of the document.
     */
    public function createVersion($filePath, $checksum): DocumentVersion
    {
        $nextVersionNo = $this->versions()->max('version_no') + 1;

        $version = $this->versions()->create([
            'version_no' => $nextVersionNo,
            'path' => $filePath,
            'checksum' => $checksum,
        ]);

        // Update current version
        $this->update(['version_current' => $nextVersionNo]);

        // Log version creation
        activity('document')
            ->performedOn($this)
            ->withProperties([
                'version_no' => $nextVersionNo,
                'previous_version' => $nextVersionNo - 1
            ])
            ->log("Document version {$nextVersionNo} created");

        return $version;
    }

    /**
     * Get the current version.
     */
    public function getCurrentVersion(): ?DocumentVersion
    {
        return $this->versions()->where('version_no', $this->version_current)->first();
    }

    /**
     * Check if document type requires expiry date.
     */
    public function requiresExpiryDate(): bool
    {
        return isset(self::TYPES[$this->type]['requires_expiry']) &&
               self::TYPES[$this->type]['requires_expiry'] === true;
    }

    /**
     * Check if document type has auto reminders.
     */
    public function hasAutoReminder(): bool
    {
        return isset(self::TYPES[$this->type]['auto_reminder']) &&
               self::TYPES[$this->type]['auto_reminder'] === true;
    }

    /**
     * Get documents requiring attention (expired or expiring).
     */
    public static function getDocumentsRequiringAttention()
    {
        return [
            'expired' => static::expired()->with(['employee', 'contract.employee'])->get(),
            'expiring_soon' => static::expiringSoon(30)->with(['employee', 'contract.employee'])->get(),
            'expiring_critically' => static::expiringSoon(7)->with(['employee', 'contract.employee'])->get(),
        ];
    }

    /**
     * Get document statistics by type and category.
     */
    public static function getDocumentStats()
    {
        $stats = [
            'total_documents' => static::count(),
            'by_category' => [],
            'by_type' => [],
            'expiry_status' => [
                'no_expiry' => static::whereNull('expires_at')->count(),
                'expired' => static::expired()->count(),
                'expiring_soon' => static::expiringSoon(30)->count(),
                'expiring_critically' => static::expiringSoon(7)->count(),
            ],
        ];

        // Group by category
        foreach (static::TYPES as $type => $config) {
            $category = $config['category'];
            $count = static::byType($type)->count();

            if (!isset($stats['by_category'][$category])) {
                $stats['by_category'][$category] = 0;
            }
            $stats['by_category'][$category] += $count;
            $stats['by_type'][$type] = $count;
        }

        return $stats;
    }

    /**
     * Check if document needs watermarking.
     */
    public function needsWatermark($user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return true; // Default to watermarked for security
        }

        // HR and IT admins don't need watermarked documents
        if ($user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin'])) {
            return false;
        }

        // All other users get watermarked documents for sensitive types
        $sensitiveTypes = ['id_card', 'bar_license', 'bar_registration', 'payslip_pdf', 'contract_pdf'];
        return in_array($this->type, $sensitiveTypes);
    }

    /**
     * Get watermark text for this document.
     */
    public function getWatermarkText($user = null): string
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return 'CONFIDENTIAL - RESTRICTED ACCESS';
        }

        $role = $user->roles->first()?->name ?? 'Unknown';
        $timestamp = now()->format('Y-m-d H:i:s');

        return "CONFIDENTIAL - {$role} - {$timestamp}";
    }

    /**
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'original_name', 'visibility', 'expires_at', 'version_current'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Document {$eventName}")
            ->useLogName('document');
    }
}