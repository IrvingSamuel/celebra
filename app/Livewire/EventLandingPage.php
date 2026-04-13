<?php

namespace App\Livewire;

use App\Livewire\Concerns\HasGiftPledge;
use App\Models\Event;
use App\Models\EventPage;
use App\Models\EventRsvp;
use App\Models\GiftRegistry;
use App\Models\User;
use Livewire\Component;

class EventLandingPage extends Component
{
    use HasGiftPledge;

    public ?User      $pageUser  = null;
    public ?Event     $event     = null;
    public ?EventPage $page      = null;
    public ?GiftRegistry $registry = null;

    // RSVP form fields
    public string $rsvpName      = '';
    public string $rsvpEmail     = '';
    public bool   $rsvpAttending = true;
    public int    $rsvpGuests    = 1;
    public string $rsvpMessage   = '';
    public bool   $rsvpSent      = false;
    public ?string $rsvpError    = null;

    public function mount(string $userSlug, string $eventSlug): void
    {
        $this->pageUser = User::where('slug', $userSlug)->firstOrFail();
        $this->event    = Event::where('slug', $eventSlug)
            ->where('user_id', $this->pageUser->id)
            ->firstOrFail();

        $this->page = EventPage::where('event_id', $this->event->id)
            ->where('published', true)
            ->firstOrFail();

        if ($this->event->gift_registry_enabled) {
            $this->registry = GiftRegistry::where('event_id', $this->event->id)
                ->where('active', true)
                ->with(['items' => fn ($q) => $q->orderBy('name')])
                ->first();
        }
    }

    public function submitRsvp(): void
    {
        $this->rsvpError = null;

        $this->validate([
            'rsvpName'      => 'required|string|max:255',
            'rsvpEmail'     => 'nullable|email|max:255',
            'rsvpAttending' => 'boolean',
            'rsvpGuests'    => 'integer|min:1|max:20',
            'rsvpMessage'   => 'nullable|string|max:1000',
        ], [
            'rsvpName.required' => 'Informe seu nome.',
            'rsvpEmail.email'   => 'Informe um e-mail válido.',
        ]);

        // Prevent duplicate RSVPs from same email for this page
        if ($this->rsvpEmail) {
            $exists = EventRsvp::where('event_page_id', $this->page->id)
                ->where('email', $this->rsvpEmail)
                ->exists();
            if ($exists) {
                $this->rsvpError = 'Você já confirmou presença com este e-mail.';
                return;
            }
        }

        EventRsvp::create([
            'event_page_id' => $this->page->id,
            'name'          => $this->rsvpName,
            'email'         => $this->rsvpEmail ?: null,
            'attending'     => $this->rsvpAttending,
            'guests'        => $this->rsvpGuests,
            'message'       => $this->rsvpMessage ?: null,
        ]);

        $this->rsvpSent = true;
    }

    public function render()
    {
        $themes = EventPage::themes();
        $theme  = $themes[$this->page->theme] ?? $themes['romantic'];

        if ($this->page->primary_color) {
            $theme['primary'] = $this->page->primary_color;
        }

        // Always reload registry items fresh so pledge counters stay up to date
        if ($this->registry) {
            $this->registry->setRelation('items', $this->registry->items()->orderBy('name')->get());
        }

        return view('livewire.event-landing-page', compact('themes', 'theme'))
            ->layout('components.layouts.event-page', [
                'title' => $this->event->title,
                'theme' => $theme,
            ]);
    }
}
