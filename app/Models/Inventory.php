<?php

namespace App\Models;

class Inventory extends BaseModel
{
    protected $fillable = [
        'product_id',
        'quantity',
        'version',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'product_id' => 'integer',
            'quantity'    => 'integer',
            'version'    => 'integer',
        ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
