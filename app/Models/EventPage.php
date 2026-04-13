<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventPage extends Model
{
    protected $fillable = ['event_id', 'theme', 'primary_color', 'published', 'blocks'];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'blocks'    => 'array',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    /** Theme palette definitions */
    public static function themes(): array
    {
        return [
            'romantic' => [
                'name'    => 'Romântico',
                'primary' => '#e11d48',
                'accent'  => '#fda4af',
                'bg'      => '#fff1f2',
                'alt_bg'  => '#ffffff',
                'text'    => '#1f2937',
                'font'    => 'Georgia, serif',
            ],
            'classic' => [
                'name'    => 'Clássico',
                'primary' => '#1e3a5f',
                'accent'  => '#c9a84c',
                'bg'      => '#fffff8',
                'alt_bg'  => '#f5efe0',
                'text'    => '#1f1f1f',
                'font'    => 'Georgia, serif',
            ],
            'modern' => [
                'name'    => 'Moderno',
                'primary' => '#1e293b',
                'accent'  => '#6366f1',
                'bg'      => '#f8fafc',
                'alt_bg'  => '#ffffff',
                'text'    => '#0f172a',
                'font'    => 'Poppins, sans-serif',
            ],
            'garden' => [
                'name'    => 'Jardim',
                'primary' => '#16a34a',
                'accent'  => '#86efac',
                'bg'      => '#f0fdf4',
                'alt_bg'  => '#ffffff',
                'text'    => '#14532d',
                'font'    => 'Georgia, serif',
            ],
            'minimal' => [
                'name'    => 'Minimal',
                'primary' => '#374151',
                'accent'  => '#d1d5db',
                'bg'      => '#ffffff',
                'alt_bg'  => '#f3f4f6',
                'text'    => '#111827',
                'font'    => 'Poppins, sans-serif',
            ],
        ];
    }

    /** Default content for each block type */
    public static function blockDefaults(string $type): array
    {
        return match ($type) {
            'hero' => [
                'id'       => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'     => 'hero',
                'visible'  => true,
                'content'  => [
                    'title'           => 'Nosso Grande Dia',
                    'subtitle'        => 'Celebre conosco este momento especial',
                    'bg_image'        => '',
                    'bg_fit'          => 'cover',
                    'bg_anchor'       => 'center',
                    'overlay_opacity' => 50,
                    'button_text'     => 'Ver lista de presentes',
                    'button_target'   => 'gifts',
                ],
            ],
            'message' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'message',
                'visible' => true,
                'content' => [
                    'heading'    => 'Nossa História',
                    'text'       => 'Escreva aqui uma mensagem especial para os seus convidados...',
                    'image'      => '',
                    'image_side' => 'right',
                ],
            ],
            'countdown' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'countdown',
                'visible' => true,
                'content' => [
                    'heading'   => 'Contagem Regressiva',
                    'note_text' => 'Mal podemos esperar para celebrar com você!',
                ],
            ],
            'gifts' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'gifts',
                'visible' => true,
                'content' => [
                    'heading'  => 'Lista de Presentes',
                    'subtitle' => 'Cada presente é uma alegria a mais neste dia especial.',
                    'columns'  => 3,
                ],
            ],
            'schedule' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'schedule',
                'visible' => true,
                'content' => [
                    'heading' => 'Programação',
                    'items'   => [
                        ['time' => '16:00', 'label' => 'Cerimônia', 'icon' => ''],
                        ['time' => '17:30', 'label' => 'Coquetel',  'icon' => ''],
                        ['time' => '19:00', 'label' => 'Jantar',    'icon' => ''],
                        ['time' => '22:00', 'label' => 'Festa',     'icon' => ''],
                    ],
                ],
            ],
            'location' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'location',
                'visible' => true,
                'content' => [
                    'heading'    => 'Local do Evento',
                    'venue_name' => '',
                    'address'    => '',
                    'maps_url'   => '',
                    'image'      => '',
                ],
            ],
            'rsvp' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'rsvp',
                'visible' => true,
                'content' => [
                    'heading'        => 'Confirme sua Presença',
                    'subtitle'       => 'Sua confirmação é muito importante para nós.',
                    'max_per_person' => 5,
                ],
            ],
            'gallery' => [
                'id'      => 'blk_' . \Illuminate\Support\Str::random(8),
                'type'    => 'gallery',
                'visible' => true,
                'content' => [
                    'heading' => 'Nossa Galeria',
                    'images'  => [],
                    'columns' => 3,
                ],
            ],
            default => [],
        };
    }
}
