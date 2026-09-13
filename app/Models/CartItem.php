<?php

namespace App\Models;

class CartItem extends BaseModel
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'cart_id'    => 'integer',
            'product_id' => 'integer',
            'quantity'   => 'integer',
        ]);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
