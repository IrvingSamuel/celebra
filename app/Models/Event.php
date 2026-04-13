<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'event_type_id', 'title', 'slug', 'event_date',
        'description', 'message', 'cover_image', 'gift_registry_enabled',
        'public', 'budget', 'guest_count',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'gift_registry_enabled' => 'boolean',
            'public' => 'boolean',
            'budget' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function giftRegistry(): HasOne
    {
        return $this->hasOne(GiftRegistry::class, 'event_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'event_services')
            ->withPivot('status', 'notes', 'price_agreed')
            ->withTimestamps();
    }

    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'event_venues')
            ->withPivot('status', 'notes', 'price_agreed')
            ->withTimestamps();
    }

    public function eventPage(): HasOne
    {
        return $this->hasOne(\App\Models\EventPage::class);
    }
}
