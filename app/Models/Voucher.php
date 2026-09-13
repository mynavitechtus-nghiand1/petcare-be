<?php

namespace App\Models;

class Voucher extends BaseModel
{
    protected $fillable = [
        'code',
        'discount_amount',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'discount_amount' => 'integer',
            'expires_at'      => 'datetime',
            'is_active'       => 'boolean',
        ]);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        return $this->is_active
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
