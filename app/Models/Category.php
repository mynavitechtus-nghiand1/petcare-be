<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
}
