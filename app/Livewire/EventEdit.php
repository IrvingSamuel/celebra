<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Venue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class EventEdit extends Component
{
    public ?int $eventId = null;
    public string $activeTab = 'info';

    // Event fields
    public string $title = '';
    public string $eventTypeId = '';
    public string $eventDate = '';
    public string $guestCount = '';
    public string $budget = '';
    public string $description = '';
    public string $message = '';
    public bool $public = true;
    public bool $giftRegistryEnabled = false;

    // Text search per category (keyed by category id)
    public array $categorySearch = [];

    // Shared filters across all categories
    public string $filterLocation = '';
    public string $filterMinPrice = '';
    public string $filterMaxPrice = '';

    // IDs of services attached to this event
    public array $attachedServiceIds = [];

    // IDs of venues attached to this event
    public array $attachedVenueIds = [];

    // Slug da categoria de espaços
    private const VENUE_CATEGORY_SLUG = 'espaco';

    // Draft status
    public bool $draftSaved = false;
    public string $draftSavedAt = '';

    private const DRAFT_FIELDS = [
        'title', 'eventTypeId', 'eventDate', 'guestCount',
        'budget', 'description', 'message', 'public', 'giftRegistryEnabled',
    ];

    public function updated(string $property): void
    {
        if ($this->eventId && in_array($property, self::DRAFT_FIELDS)) {
            $this->autoSave();
        }
    }

    public function autoSave(): void
    {
        if (!$this->eventId || empty(trim($this->title))) {
            return;
        }

        Event::where('id', $this->eventId)
            ->where('user_id', Auth::id())
            ->update([
                'title'                => $this->title,
                'event_type_id'        => $this->eventTypeId ?: null,
                'event_date'           => $this->eventDate ?: null,
                'guest_count'          => $this->guestCount !== '' ? (int) $this->guestCount : null,
                'budget'               => $this->budget !== '' ? (float) $this->budget : null,
                'description'          => $this->description ?: null,
                'message'              => $this->message ?: null,
                'public'               => $this->public,
                'gift_registry_enabled' => $this->giftRegistryEnabled,
            ]);

        $this->draftSaved = true;
        $this->draftSavedAt = now()->format('H:i');
    }

    public function mount(?string $slug = null): void
    {
        if ($slug) {
            $event = Event::where('slug', $slug)
                ->where('user_id', Auth::id())
                ->with('services')
                ->firstOrFail();

            $this->eventId = $event->id;
            $this->title = $event->title;
            $this->eventTypeId = (string) ($event->event_type_id ?? '');
            $this->eventDate = $event->event_date ? $event->event_date->format('Y-m-d') : '';
            $this->guestCount = (string) ($event->guest_count ?? '');
            $this->budget = (string) ($event->budget ?? '');
            $this->description = $event->description ?? '';
            $this->message = $event->message ?? '';
            $this->public = (bool) $event->public;
            $this->giftRegistryEnabled = (bool) $event->gift_registry_enabled;
            $this->attachedServiceIds = $event->services->pluck('id')->toArray();
            $this->attachedVenueIds = $event->venues->pluck('id')->toArray();
            $this->draftSaved = true;
            $this->draftSavedAt = $event->updated_at->format('H:i');
        }
    }

    protected function rules(): array
    {
        return [
            'title'               => 'required|string|max:255',
            'eventTypeId'         => 'required|exists:event_types,id',
            'eventDate'           => 'nullable|date',
            'guestCount'          => 'nullable|integer|min:1|max:100000',
            'budget'              => 'nullable|numeric|min:0',
            'description'         => 'nullable|string|max:2000',
            'message'             => 'nullable|string|max:1000',
            'public'              => 'boolean',
            'giftRegistryEnabled' => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'       => 'O nome do evento é obrigatório.',
            'eventTypeId.required' => 'Selecione o tipo do evento.',
            'eventDate.date'       => 'Data inválida.',
            'guestCount.integer'   => 'Número de convidados deve ser um número inteiro.',
            'budget.numeric'       => 'Orçamento inválido.',
        ];
    }

    public function save(): mixed
    {
        $this->validate();

        $data = [
            'title'               => $this->title,
            'event_type_id'       => $this->eventTypeId ?: null,
            'event_date'          => $this->eventDate ?: null,
            'guest_count'         => $this->guestCount !== '' ? (int) $this->guestCount : null,
            'budget'              => $this->budget !== '' ? (float) $this->budget : null,
            'description'         => $this->description ?: null,
            'message'             => $this->message ?: null,
            'public'              => $this->public,
            'gift_registry_enabled' => $this->giftRegistryEnabled,
        ];

        if ($this->eventId) {
            Event::where('id', $this->eventId)
                ->where('user_id', Auth::id())
                ->update($data);

            session()->flash('success', 'Evento salvo com sucesso!');
        } else {
            $slug = Str::slug($this->title);
            $original = $slug;
            $i = 2;
            while (Event::where('slug', $slug)->where('user_id', Auth::id())->exists()) {
                $slug = $original . '-' . $i++;
            }

            $event = Event::create(array_merge($data, [
                'user_id' => Auth::id(),
                'slug'    => $slug,
            ]));

            $this->eventId = $event->id;

            return redirect()->route('event.edit', ['slug' => $slug])
                ->with('success', 'Evento criado com sucesso!');
        }

        return null;
    }

    public function attachService(int $serviceId): void
    {
        if (!$this->eventId) {
            session()->flash('error', 'Salve as informações do evento antes de adicionar serviços.');
            $this->activeTab = 'info';
            return;
        }

        if (in_array($serviceId, $this->attachedServiceIds)) {
            return;
        }

        $event = Event::where('id', $this->eventId)->where('user_id', Auth::id())->firstOrFail();
        $event->services()->attach($serviceId, ['status' => 'pending']);
        $this->attachedServiceIds[] = $serviceId;
    }

    public function detachService(int $serviceId): void
    {
        if (!$this->eventId) {
            return;
        }

        $event = Event::where('id', $this->eventId)->where('user_id', Auth::id())->firstOrFail();
        $event->services()->detach($serviceId);
        $this->attachedServiceIds = array_values(array_filter(
            $this->attachedServiceIds,
            fn($id) => $id !== $serviceId
        ));
    }

    public function attachVenue(int $venueId): void
    {
        if (!$this->eventId) {
            session()->flash('error', 'Salve as informações do evento antes de adicionar um espaço.');
            $this->activeTab = 'info';
            return;
        }

        if (in_array($venueId, $this->attachedVenueIds)) {
            return;
        }

        $event = Event::where('id', $this->eventId)->where('user_id', Auth::id())->firstOrFail();
        $event->venues()->attach($venueId, ['status' => 'pending']);
        $this->attachedVenueIds[] = $venueId;
    }

    public function detachVenue(int $venueId): void
    {
        if (!$this->eventId) {
            return;
        }

        $event = Event::where('id', $this->eventId)->where('user_id', Auth::id())->firstOrFail();
        $event->venues()->detach($venueId);
        $this->attachedVenueIds = array_values(array_filter(
            $this->attachedVenueIds,
            fn($id) => $id !== $venueId
        ));
    }

    public function render()
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();

        $location = $this->filterLocation;
        $minPrice = $this->filterMinPrice;
        $maxPrice = $this->filterMaxPrice;

        $servicesByCategory = [];
        foreach ($categories as $cat) {
            $search = $this->categorySearch[$cat->id] ?? '';

            if ($cat->slug === self::VENUE_CATEGORY_SLUG) {
                $query = Venue::where('active', true);
                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                    });
                }
                if ($location !== '') {
                    $query->where(function ($q) use ($location) {
                        $q->where('city', 'like', "%{$location}%")
                          ->orWhere('state', 'like', "%{$location}%");
                    });
                }
                if ($minPrice !== '' && is_numeric($minPrice)) {
                    $query->where('price', '>=', (float) $minPrice);
                }
                if ($maxPrice !== '' && is_numeric($maxPrice)) {
                    $query->where('price', '<=', (float) $maxPrice);
                }
                $servicesByCategory[$cat->id] = $query->orderByDesc('rating')->get();
            } else {
                $query = $cat->services()->where('active', true)->with('supplierProfile');
                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                    });
                }
                if ($location !== '') {
                    $query->whereHas('supplierProfile', function ($q) use ($location) {
                        $q->where('city', 'like', "%{$location}%")
                          ->orWhere('state', 'like', "%{$location}%");
                    });
                }
                if ($minPrice !== '' && is_numeric($minPrice)) {
                    $query->where('price', '>=', (float) $minPrice);
                }
                if ($maxPrice !== '' && is_numeric($maxPrice)) {
                    $query->where('price', '<=', (float) $maxPrice);
                }
                $servicesByCategory[$cat->id] = $query->orderByDesc('rating')->get();
            }
        }

        $venueCategorySlug = self::VENUE_CATEGORY_SLUG;

        $event     = $this->eventId ? Event::with('eventPage')->find($this->eventId) : null;
        $eventSlug = $event?->slug;
        $eventPage = $event?->eventPage;
        $userSlug  = Auth::user()->slug ?? null;

        return view('livewire.event-edit', [
            'eventTypes'          => EventType::orderBy('sort_order')->get(),
            'categories'          => $categories,
            'servicesByCategory'  => $servicesByCategory,
            'venueCategorySlug'   => $venueCategorySlug,
            'eventSlug'           => $eventSlug,
            'eventPage'           => $eventPage,
            'userSlug'            => $userSlug,
        ]);
    }
}
