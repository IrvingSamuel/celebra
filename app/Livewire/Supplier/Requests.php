<?php

namespace App\Livewire\Supplier;

use App\Models\SupplierProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Requests extends Component
{
    public string $filterStatus = 'all';
    public ?int $acceptingId = null;
    public string $priceAgreed = '';

    public function boot(): void
    {
        if (! Auth::user()?->isSupplier()) {
            $this->redirect('/dashboard', navigate: true);
        }
    }

    private function profile(): ?SupplierProfile
    {
        return Auth::user()->supplierProfile;
    }

    private function requestsQuery()
    {
        $profile = $this->profile();
        if (! $profile) {
            return null;
        }

        $serviceIds = $profile->services()->pluck('id');

        return DB::table('event_services')
            ->join('services', 'services.id', '=', 'event_services.service_id')
            ->join('events', 'events.id', '=', 'event_services.event_id')
            ->join('users', 'users.id', '=', 'events.user_id')
            ->whereIn('event_services.service_id', $serviceIds)
            ->select(
                'event_services.id',
                'event_services.status',
                'event_services.notes',
                'event_services.price_agreed',
                'event_services.created_at',
                'services.name as service_name',
                'events.title as event_title',
                'events.event_date',
                'events.guest_count',
                'events.budget',
                'users.name as client_name',
            );
    }

    public function openAccept(int $id): void
    {
        $this->acceptingId = $id;
        $this->priceAgreed = '';
    }

    public function confirmAccept(): void
    {
        $this->validate(['priceAgreed' => 'nullable|numeric|min:0']);

        if (! $this->acceptingId) {
            return;
        }

        $this->authorizeRequest($this->acceptingId);

        DB::table('event_services')->where('id', $this->acceptingId)->update([
            'status'       => 'accepted',
            'price_agreed' => $this->priceAgreed !== '' ? $this->priceAgreed : null,
            'updated_at'   => now(),
        ]);

        $this->acceptingId = null;
        $this->priceAgreed = '';
    }

    public function decline(int $id): void
    {
        $this->authorizeRequest($id);

        DB::table('event_services')->where('id', $id)->update([
            'status'     => 'declined',
            'updated_at' => now(),
        ]);
    }

    public function cancelAccept(): void
    {
        $this->acceptingId = null;
    }

    private function authorizeRequest(int $id): void
    {
        $profile = $this->profile();
        abort_if(! $profile, 403);

        $serviceIds = $profile->services()->pluck('id');
        $exists = DB::table('event_services')
            ->whereIn('service_id', $serviceIds)
            ->where('id', $id)
            ->exists();

        abort_if(! $exists, 403);
    }

    public function render()
    {
        $query = $this->requestsQuery();

        $requests = collect();
        if ($query) {
            if ($this->filterStatus !== 'all') {
                $query->where('event_services.status', $this->filterStatus);
            }
            $requests = $query->orderByDesc('event_services.created_at')->get();
        }

        $counts = [];
        if ($this->profile()) {
            $baseQuery  = $this->requestsQuery();
            $counts['all']      = (clone $baseQuery)->count();
            $counts['pending']  = (clone $baseQuery)->where('event_services.status', 'pending')->count();
            $counts['accepted'] = (clone $baseQuery)->where('event_services.status', 'accepted')->count();
            $counts['declined'] = (clone $baseQuery)->where('event_services.status', 'declined')->count();
        }

        return view('livewire.supplier.requests', compact('requests', 'counts'));
    }
}
