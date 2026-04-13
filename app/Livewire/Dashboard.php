<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function deleteEvent(int $id): void
    {
        Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();
    }

    public function render()
    {
        $user = Auth::user();

        if ($user->isSupplier()) {
            $profile = $user->supplierProfile;

            $servicesCount    = 0;
            $pendingCount     = 0;
            $acceptedCount    = 0;
            $estimatedRevenue = 0;

            if ($profile) {
                $serviceIds = $profile->services()->pluck('id');

                $servicesCount = $profile->services()->where('active', true)->count();

                $stats = DB::table('event_services')
                    ->whereIn('service_id', $serviceIds)
                    ->selectRaw("
                        count(*) as total,
                        sum(case when status = 'pending'  then 1 else 0 end) as pending,
                        sum(case when status = 'accepted' then 1 else 0 end) as accepted,
                        sum(case when status = 'accepted' then coalesce(price_agreed, 0) else 0 end) as revenue
                    ")
                    ->first();

                $pendingCount     = (int) ($stats->pending  ?? 0);
                $acceptedCount    = (int) ($stats->accepted ?? 0);
                $estimatedRevenue = (float) ($stats->revenue ?? 0);
            }

            return view('livewire.supplier.dashboard', compact(
                'profile', 'servicesCount', 'pendingCount', 'acceptedCount', 'estimatedRevenue'
            ));
        }

        return view('livewire.client.dashboard', [
            'events' => $user->events()->with(['eventType', 'eventPage'])->latest()->get(),
        ]);
    }
}
