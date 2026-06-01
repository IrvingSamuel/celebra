<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SupplierProfile;
use App\Models\Venue;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceListing extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $minPrice = '';

    #[Url]
    public string $maxPrice = '';

    #[Url]
    public string $minRating = '';

    #[Url]
    public string $sortBy = 'rating';

    public string $categorySlug = '';

    public function mount(string $category = ''): void
    {
        $this->categorySlug = $category;
    }

    public function updated(string $property): void
    {
        if ($property !== 'categorySlug') {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->search   = '';
        $this->city     = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->minRating = '';
        $this->sortBy   = 'rating';
        $this->resetPage();
    }

    public function hasActiveFilters(): bool
    {
        return $this->search !== ''
            || $this->city !== ''
            || $this->minPrice !== ''
            || $this->maxPrice !== ''
            || $this->minRating !== ''
            || $this->sortBy !== 'rating';
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

            if ($this->city) {
                $venueQuery->where(function ($q) {
                    $q->where('city', 'like', '%' . $this->city . '%')
                      ->orWhere('state', 'like', '%' . $this->city . '%');
                });
            }

            if ($this->minPrice !== '') {
                $venueQuery->where('price', '>=', (float) $this->minPrice);
            }

            if ($this->maxPrice !== '') {
                $venueQuery->where('price', '<=', (float) $this->maxPrice);
            }

            if ($this->minRating !== '') {
                $venueQuery->where('rating', '>=', (float) $this->minRating);
            }

            $venueQuery = match ($this->sortBy) {
                'price_asc'  => $venueQuery->orderBy('price'),
                'price_desc' => $venueQuery->orderByDesc('price'),
                default      => $venueQuery->orderByDesc('rating'),
            };

            $cities = Venue::where('active', true)->distinct()->orderBy('city')->pluck('city');

            return view('livewire.service-listing', [
                'services'        => collect(),
                'venues'          => $venueQuery->paginate(12),
                'categories'      => ServiceCategory::orderBy('sort_order')->get(),
                'currentCategory' => $currentCategory,
                'isVenueCategory' => true,
                'cities'          => $cities,
                'hasActiveFilters' => $this->hasActiveFilters(),
            ]);
        }

        $query = Service::where('active', true)
            ->whereHas('supplierProfile', fn ($q) => $q->where('verified', true))
            ->with(['category', 'supplierProfile']);

        if ($this->categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->categorySlug));
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->city) {
            $query->whereHas('supplierProfile', function ($q) {
                $q->where(function ($inner) {
                    $inner->where('city', 'like', '%' . $this->city . '%')
                          ->orWhere('state', 'like', '%' . $this->city . '%');
                });
            });
        }

        if ($this->minPrice !== '') {
            $query->where('price', '>=', (float) $this->minPrice);
        }

        if ($this->maxPrice !== '') {
            $query->where('price', '<=', (float) $this->maxPrice);
        }

        if ($this->minRating !== '') {
            $query->where('rating', '>=', (float) $this->minRating);
        }

        $query = match ($this->sortBy) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default      => $query->orderByDesc('rating'),
        };

        $cities = SupplierProfile::where('verified', true)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('livewire.service-listing', [
            'services'         => $query->paginate(12),
            'venues'           => collect(),
            'categories'       => ServiceCategory::orderBy('sort_order')->get(),
            'currentCategory'  => $currentCategory,
            'isVenueCategory'  => false,
            'cities'           => $cities,
            'hasActiveFilters' => $this->hasActiveFilters(),
        ]);
    }
}
