<?php

namespace App\Models;

class Cart extends BaseModel
{
    protected $fillable = [
        'user_id',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'user_id' => 'integer',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
