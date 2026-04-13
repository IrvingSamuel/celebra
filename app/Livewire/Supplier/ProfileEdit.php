<?php

namespace App\Livewire\Supplier;

use App\Models\SupplierProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public string $company_name = '';
    public string $description = '';
    public string $phone = '';
    public string $city = '';
    public string $state = '';

    // Logo atual (caminho salvo ou URL legada)
    public string $logo = '';
    // Novo arquivo de logo para upload
    public $logoFile = null;

    public bool $saved = false;

    public function boot(): void
    {
        if (! Auth::user()?->isSupplier()) {
            $this->redirect('/dashboard', navigate: true);
        }
    }

    protected function rules(): array
    {
        return [
            'company_name'        => 'required|string|max:255',
            'description'         => 'nullable|string|max:2000',
            'phone'               => 'nullable|string|max:20',
            'city'                => 'nullable|string|max:100',
            'state'               => 'nullable|string|size:2',
            'logoFile'            => 'nullable|image|max:2048',
        ];
    }

    public function mount(): void
    {
        $profile = Auth::user()->supplierProfile;
        if ($profile) {
            $this->company_name        = $profile->company_name ?? '';
            $this->description         = $profile->description ?? '';
            $this->phone               = $profile->phone ?? '';
            $this->city  = $profile->city ?? '';
            $this->state = $profile->state ?? '';
            $this->logo  = $profile->logo ?? '';
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $logoPath = $this->logo ?: null;

        if ($this->logoFile) {
            // Remover logo antigo do MinIO se for um caminho (não URL externa)
            if ($logoPath && ! str_starts_with($logoPath, 'http')) {
                Storage::disk('minio')->delete($logoPath);
            }
            $logoPath = $this->logoFile->store('logos', 'minio');
        }

        foreach (['description', 'phone', 'city', 'state'] as $field) {
            if ($data[$field] === '') {
                $data[$field] = null;
            }
        }

        $data['logo'] = $logoPath;
        unset($data['logoFile']);

        SupplierProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        $this->logo    = $logoPath ?? '';
        $this->logoFile = null;
        $this->saved   = true;
    }

    public function render()
    {
        return view('livewire.supplier.profile-edit');
    }
}
