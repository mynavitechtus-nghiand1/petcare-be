<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'sku',
        'product_type',
        'status',
        'description',
        'image_url',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'brand_id' => 'integer',
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
