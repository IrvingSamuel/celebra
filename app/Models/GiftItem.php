<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\GiftPledge;

class GiftItem extends Model
{
    protected $fillable = [
        'gift_registry_id', 'name', 'description', 'price', 'image', 'url',
        'platform', 'quantity_desired', 'quantity_received',
    ];
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function registry()
    {
        return $this->belongsTo(GiftRegistry::class, 'gift_registry_id');
    }

    public function pledges()
    {
        return $this->hasMany(GiftPledge::class);
    }

    public function activePledges()
    {
        return $this->hasMany(GiftPledge::class)->whereNull('cancelled_at');
    }

    public function basketItems()
    {
        return $this->morphMany(BasketItem::class, 'itemable');
    }
}
