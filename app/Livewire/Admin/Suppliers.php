<?php

namespace App\Livewire\Admin;

use App\Models\SupplierProfile;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $filter = 'all'; // all | verified | pending

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function approve(int $id): void
    {
        $supplier = SupplierProfile::with('user')->findOrFail($id);

        if (! $supplier->user?->hasVerifiedEmail()) {
            $this->addError('approve_' . $id, 'O fornecedor ainda não confirmou o e-mail.');
            return;
        }

        $supplier->update(['verified' => true]);
    }

    public function revoke(int $id): void
    {
        SupplierProfile::findOrFail($id)->update(['verified' => false]);
    }

    public function render()
    {
        $query = SupplierProfile::with(['user', 'category'])
            ->withCount('services');

        if ($this->filter === 'verified') {
            $query->where('verified', true);
        } elseif ($this->filter === 'pending') {
            $query->where('verified', false);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', fn ($u) => $u->where('email', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%'));
            });
        }

        $suppliers = $query->latest()->paginate(20);

        $totalCount    = SupplierProfile::count();
        $verifiedCount = SupplierProfile::where('verified', true)->count();
        $pendingCount  = SupplierProfile::where('verified', false)->count();

        return view('livewire.admin.suppliers', compact('suppliers', 'totalCount', 'verifiedCount', 'pendingCount'));
    }
}
