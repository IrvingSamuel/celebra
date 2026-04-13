<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Venue extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'city', 'state', 'address', 'type', 'price', 'image', 'gallery', 'rating', 'capacity', 'active'];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'price' => 'decimal:2',
            'rating' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    /** Resolve URL da imagem principal (MinIO ou URL legada). */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return Storage::disk('minio')->url($this->image);
    }

    /** Resolve URLs da galeria. */
    public function getGalleryUrlsAttribute(): array
    {
        if (empty($this->gallery)) {
            return [];
        }
        return array_map(function ($img) {
            if (str_starts_with($img, 'http')) {
                return $img;
            }
            return Storage::disk('minio')->url($img);
        }, $this->gallery);
    }

    public function basketItems()
    {
        return $this->morphMany(BasketItem::class, 'itemable');
    }

    public function eventTypes(): BelongsToMany
    {
        return $this->belongsToMany(EventType::class, 'event_type_venue');
    }
}
