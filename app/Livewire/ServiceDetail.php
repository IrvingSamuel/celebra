<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;

class ServiceDetail extends Component
{
    public Service $service;

    public function mount(string $slug)
    {
        $this->service = Service::with(['category', 'supplierProfile'])
            ->where('slug', $slug)
            ->where('active', true)
            ->whereHas('supplierProfile', fn ($q) => $q->where('verified', true))
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.service-detail');
    }
}
