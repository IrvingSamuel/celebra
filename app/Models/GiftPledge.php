<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftPledge extends Model
{
    protected $fillable = [
        'gift_item_id',
        'giver_name',
        'giver_email',
        'giver_phone',
        'cancel_token',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'cancelled_at' => 'datetime',
        ];
    }

    public function giftItem(): BelongsTo
    {
        return $this->belongsTo(GiftItem::class);
    }

    public function isActive(): bool
    {
        return $this->cancelled_at === null;
    }
}
