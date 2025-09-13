<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public $timestamps = ['created_at']; // Only created_at timestamp
    
    const UPDATED_AT = null; // Disable updated_at

    /**
     * Get the documents that belong to this tag.
     */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_tag');
    }

    /**
     * Scope: Order by name.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('name');
    }

    /**
     * Scope: Search tags by name.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    /**
     * Get the document count for this tag.
     */
    public function getDocumentCountAttribute(): int
    {
        return $this->documents()->count();
    }
}