<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRsvp extends Model
{
    protected $fillable = ['event_page_id', 'name', 'email', 'attending', 'guests', 'message'];

    protected function casts(): array
    {
        return [
            'attending' => 'boolean',
        ];
    }

    public function eventPage(): BelongsTo
    {
        return $this->belongsTo(EventPage::class);
    }
}
