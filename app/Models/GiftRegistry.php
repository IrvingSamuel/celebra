<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftRegistry extends Model
{
    protected $fillable = [
        'wedding_id', 'event_id', 'title', 'slug', 'description', 'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function wedding()
    {
        return $this->belongsTo(Wedding::class);
    }

    public function items()
    {
        return $this->hasMany(GiftItem::class);
    }
}
