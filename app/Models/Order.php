<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $fillable = [
        'user_id',
        'voucher_id',
        'status',
        'currency',
        'subtotal_amount',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'user_id'         => 'integer',
            'subtotal_amount' => 'integer',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
