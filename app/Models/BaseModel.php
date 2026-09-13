<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

abstract class BaseModel extends Model
{
    /**
     * Common attributes that should be hidden by default
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Common attributes that should be cast
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Format created_at as human readable using Laravel 12 Attributes
     */
    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->created_at?->format('d/m/Y H:i') ?? '',
        );
    }

    /**
     * Format updated_at as human readable using Laravel 12 Attributes
     */
    protected function updatedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->updated_at?->format('d/m/Y H:i') ?? '',
        );
    }

    /**
     * Check if model was created recently (within 24 hours)
     */
    protected function isRecent(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->created_at?->isAfter(Carbon::now()->subDay()) ?? false,
        );
    }

    /**
     * Scope for active records (override in child models if needed)
     */
    public function scopeActive($query)
    {
        return $query;
    }

    /**
     * Scope for recent records
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Get the model's primary key value as string
     */
    public function getKey(): string
    {
        return (string) parent::getKey();
    }

    /**
     * Check if model exists in database
     */
    public function isPersistedInDatabase(): bool
    {
        return $this->exists && !$this->wasRecentlyCreated;
    }
}
