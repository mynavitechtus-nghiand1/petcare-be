<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'sku',
        'unit_price',
        'currency',
        'quantity',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'order_id'   => 'integer',
            'product_id' => 'integer',
            'unit_price' => 'integer',
            'quantity'   => 'integer',
            'line_total' => 'integer',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
