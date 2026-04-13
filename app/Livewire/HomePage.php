<?php

namespace App\Livewire;

use App\Models\EventType;
use App\Models\ServiceCategory;
use App\Models\Venue;
use Livewire\Component;

class HomePage extends Component
{
    public string $search = '';

    public function render()
    {
        $featuredVenues = Venue::where('active', true)
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        $carouselImages = Venue::where('active', true)
            ->whereNotNull('image')
            ->inRandomOrder()
            ->take(6)
            ->get()
            ->map(fn($v) => $v->image_url)
            ->filter()
            ->values()
            ->toArray();

        return view('livewire.home-page', [
            'categories' => ServiceCategory::orderBy('sort_order')->get(),
            'eventTypes' => EventType::orderBy('sort_order')->get(),
            'featuredVenues' => $featuredVenues,
            'carouselImages' => $carouselImages,
        ]);
    }
}
