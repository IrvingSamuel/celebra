<?php

namespace App\Livewire\Concerns;

use App\Models\GiftItem;
use App\Models\GiftPledge;
use App\Notifications\GiftPledgeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait HasGiftPledge
{
    public ?int $pledgingItemId = null;

    public string $pledgerName  = '';
    public string $pledgerEmail = '';
    public string $pledgerPhone = '';

    public bool    $pledgeSent  = false;
    public ?string $pledgeError = null;

    public function openPledge(int $itemId): void
    {
        $this->pledgingItemId = $itemId;
        $this->pledgeSent     = false;
        $this->pledgeError    = null;
        $this->pledgerName    = '';
        $this->pledgerEmail   = '';
        $this->pledgerPhone   = '';
        $this->resetErrorBag();
    }

    public function closePledge(): void
    {
        $this->pledgingItemId = null;
        $this->pledgeSent     = false;
        $this->pledgeError    = null;
    }

    public function submitPledge(): void
    {
        $this->pledgeError = null;

        $this->validate([
            'pledgerName'  => 'required|string|max:255',
            'pledgerEmail' => 'required|email|max:255',
            'pledgerPhone' => 'nullable|string|max:20',
        ], [
            'pledgerName.required'  => 'Informe seu nome.',
            'pledgerEmail.required' => 'Informe seu e-mail.',
            'pledgerEmail.email'    => 'Informe um e-mail válido.',
        ]);

        $item = GiftItem::whereHas('registry', fn ($q) => $q->where('event_id', $this->event->id))
            ->where('id', $this->pledgingItemId)
            ->first();

        if (! $item) {
            $this->pledgeError = 'Item não encontrado.';
            return;
        }

        // Prevent duplicate active pledge from same email for the same item
        $exists = GiftPledge::where('gift_item_id', $item->id)
            ->where('giver_email', $this->pledgerEmail)
            ->whereNull('cancelled_at')
            ->exists();

        if ($exists) {
            $this->pledgeError = 'Você já marcou este presente com este e-mail.';
            return;
        }

        $token  = Str::random(64);
        $pledge = GiftPledge::create([
            'gift_item_id' => $item->id,
            'giver_name'   => $this->pledgerName,
            'giver_email'  => $this->pledgerEmail,
            'giver_phone'  => $this->pledgerPhone ?: null,
            'cancel_token' => $token,
        ]);

        $item->increment('quantity_received');

        $cancelUrl  = route('gift.pledge.cancel', $token);
        $eventTitle = $this->event->title;

        Notification::route('mail', $this->pledgerEmail)
            ->notify(new GiftPledgeNotification($pledge->load('giftItem'), $cancelUrl, $eventTitle));

        $this->pledgeSent = true;
    }
}
