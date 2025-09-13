<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'version_no',
        'path',
        'checksum',
    ];

    protected $casts = [
        'version_no' => 'integer',
    ];

    public $timestamps = ['created_at']; // Only created_at timestamp
    
    const UPDATED_AT = null; // Disable updated_at

    /**
     * Get the document that owns this version.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
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
     * Check if this is the current version.
     */
    public function getIsCurrentAttribute(): bool
    {
        return $this->document && $this->version_no === $this->document->version_current;
    }
}