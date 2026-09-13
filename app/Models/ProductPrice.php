<?php

namespace App\Models;

class ProductPrice extends BaseModel
{
    protected $fillable = [
        'product_id',
        'currency',
        'amount',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'product_id' => 'integer',
            'amount'     => 'integer',
        ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
