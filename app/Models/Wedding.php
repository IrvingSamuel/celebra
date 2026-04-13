<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'title', 'slug', 'wedding_date', 'partner1_name', 'partner2_name', 'message', 'cover_image', 'gift_registry_enabled', 'public'])]
class Wedding extends Model
{
    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'gift_registry_enabled' => 'boolean',
            'public' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function giftRegistry()
    {
        return $this->hasOne(GiftRegistry::class);
    }
}
