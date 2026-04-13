<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Venue;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceListing extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $categorySlug = '';

    public function mount(string $category = '')
    {
        $this->categorySlug = $category;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $isVenueCategory = $this->categorySlug === 'espaco';

        $currentCategory = $this->categorySlug
            ? ServiceCategory::where('slug', $this->categorySlug)->first()
            : null;

        if ($isVenueCategory) {
            $venueQuery = Venue::where('active', true);

            if ($this->search) {
                $venueQuery->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%');
                });
            }

            return view('livewire.service-listing', [
                'services' => collect(),
                'venues' => $venueQuery->orderByDesc('rating')->paginate(12),
                'categories' => ServiceCategory::orderBy('sort_order')->get(),
                'currentCategory' => $currentCategory,
                'isVenueCategory' => true,
            ]);
        }

        $query = Service::where('active', true)
            ->whereHas('supplierProfile', fn ($q) => $q->where('verified', true))
            ->with('category');

        if ($this->categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->categorySlug));
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.service-listing', [
            'services' => $query->orderByDesc('rating')->paginate(12),
            'venues' => collect(),
            'categories' => ServiceCategory::orderBy('sort_order')->get(),
            'currentCategory' => $currentCategory,
            'isVenueCategory' => false,
        ]);
    }
}
