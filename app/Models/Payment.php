<?php

namespace App\Models;

class Payment extends BaseModel
{
    protected $fillable = [
        'order_id',
        'status',
        'amount',
        'currency',
        'provider',
        'provider_ref',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'order_id' => 'integer',
            'amount'   => 'integer',
        ]);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
