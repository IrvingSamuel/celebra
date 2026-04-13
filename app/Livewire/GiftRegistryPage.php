<?php

namespace App\Livewire;

use App\Livewire\Concerns\HasGiftPledge;
use App\Models\Event;
use App\Models\GiftRegistry;
use Livewire\Component;

class GiftRegistryPage extends Component
{
    use HasGiftPledge;

    public Event $event;
    public ?GiftRegistry $registry = null;

    public function mount(string $slug)
    {
        $this->event = Event::where('slug', $slug)
            ->where('public', true)
            ->where('gift_registry_enabled', true)
            ->firstOrFail();

        $this->registry = $this->event->giftRegistry;
    }

    public function render()
    {
        return view('livewire.gift-registry-page', [
            'items' => $this->registry?->items()->get() ?? collect(),
        ])->layout('components.layouts.gift-registry', [
            'title'         => ($this->registry?->title ?? 'Lista de Presentes') . ' · ' . $this->event->title,
            'ogImage'       => $this->event->cover_image,
            'ogDescription' => $this->event->message ?? 'Confira a lista de presentes de ' . $this->event->title,
        ]);
    }
}
