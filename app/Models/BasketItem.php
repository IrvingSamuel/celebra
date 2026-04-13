<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'itemable_type', 'itemable_id', 'quantity', 'price'])]
class BasketItem extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itemable()
    {
        return $this->morphTo();
    }
}
