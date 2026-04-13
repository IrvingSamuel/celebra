<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class VenueListing extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $type = '';

    #[Url]
    public string $city = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Venue::where('active', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->city) {
            $query->where('city', $this->city);
        }

        return view('livewire.venue-listing', [
            'venues' => $query->orderByDesc('rating')->paginate(12),
            'cities' => Venue::where('active', true)->distinct()->pluck('city')->sort(),
        ]);
    }
}
