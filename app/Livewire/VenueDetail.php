<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;

class VenueDetail extends Component
{
    public Venue $venue;

    public function mount(string $slug)
    {
        $this->venue = Venue::where('slug', $slug)->where('active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.venue-detail');
    }
}
