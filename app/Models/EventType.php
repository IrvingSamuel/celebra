<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description', 'sort_order'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'event_type_venue');
    }
}
