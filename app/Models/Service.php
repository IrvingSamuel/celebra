<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    protected $fillable = [
        'service_category_id', 'supplier_profile_id', 'name', 'slug', 'description', 'price', 'image', 'images', 'rating', 'active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:2',
            'active' => 'boolean',
            'images' => 'array',
        ];
    }

    /** Retorna a primeira imagem disponível (nova estrutura ou legado). */
    public function getPrimaryImageAttribute(): ?string
    {
        if (! empty($this->images)) {
            return $this->images[0];
        }
        return $this->image ?: null;
    }

    /** Resolve URL da imagem principal (MinIO ou URL legada). */
    public function getImageUrlAttribute(): ?string
    {
        $path = $this->primary_image;
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return Storage::disk('minio')->url($path);
    }

    /** Resolve URLs de todas as imagens da galeria (MinIO ou URLs legadas). */
    public function getResolvedImagesAttribute(): array
    {
        if (empty($this->images)) {
            return [];
        }
        return array_map(function ($img) {
            if (str_starts_with($img, 'http')) {
                return $img;
            }
            return Storage::disk('minio')->url($img);
        }, $this->images);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function supplierProfile(): BelongsTo
    {
        return $this->belongsTo(SupplierProfile::class);
    }

    public function basketItems()
    {
        return $this->morphMany(BasketItem::class, 'itemable');
    }
}
