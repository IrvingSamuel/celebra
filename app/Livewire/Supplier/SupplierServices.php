<?php

namespace App\Livewire\Supplier;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SupplierProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierServices extends Component
{
    use WithFileUploads;

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $description = '';
    public string $price = '';
    public bool $active = true;
    public int|string $categoryId = '';

    // Imagens temporárias (upload)
    public $imageFile1 = null;
    public $imageFile2 = null;
    public $imageFile3 = null;

    // URLs já salvas (ao editar)
    public array $existingImages = [];

    public function boot(): void
    {
        if (! Auth::user()?->isSupplier()) {
            $this->redirect('/dashboard', navigate: true);
        }
    }

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'active'      => 'boolean',
            'categoryId'  => 'required|exists:service_categories,id',
            'imageFile1'  => 'nullable|image|max:5120',
            'imageFile2'  => 'nullable|image|max:5120',
            'imageFile3'  => 'nullable|image|max:5120',
        ];
    }

    private function profile(): ?SupplierProfile
    {
        return Auth::user()->supplierProfile;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $profile = $this->profile();
        if (! $profile) {
            return;
        }

        $service = $profile->services()->findOrFail($id);
        $this->editingId       = $id;
        $this->name            = $service->name;
        $this->description     = $service->description ?? '';
        $this->price           = (string) $service->price;
        $this->active          = $service->active;
        $this->categoryId      = $service->service_category_id ?? '';
        $this->existingImages  = $service->images ?? [];
        $this->showForm        = true;
    }

    public function removeExistingImage(int $index): void
    {
        $path = $this->existingImages[$index] ?? null;
        if ($path) {
            Storage::disk('minio')->delete($path);
        }
        array_splice($this->existingImages, $index, 1);
    }

    public function save(): void
    {
        $data = $this->validate();
        $profile = $this->profile();

        if (! $profile) {
            $this->addError('name', 'Complete seu perfil de fornecedor antes de cadastrar serviços.');
            return;
        }

        // Upload das novas imagens para MinIO
        $newPaths = [];
        foreach (['imageFile1', 'imageFile2', 'imageFile3'] as $field) {
            if ($this->$field) {
                $path = $this->$field->store('services', 'minio');
                $newPaths[] = $path;
            }
        }

        // Combinar imagens existentes (não removidas) com as novas
        $allImages = array_merge($this->existingImages, $newPaths);
        $allImages = array_values(array_slice($allImages, 0, 3));

        $serviceData = [
            'supplier_profile_id' => $profile->id,
            'service_category_id' => $data['categoryId'],
            'name'        => $data['name'],
            'description' => $data['description'] ?: null,
            'price'       => $data['price'],
            'active'      => $data['active'],
            'images'      => ! empty($allImages) ? $allImages : null,
        ];

        if ($this->editingId) {
            $profile->services()->findOrFail($this->editingId)->update($serviceData);
        } else {
            $serviceData['slug']   = Str::slug($data['name']) . '-' . Str::lower(Str::random(6));
            $serviceData['rating'] = 0;
            Service::create($serviceData);
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $profile = $this->profile();
        if ($profile) {
            $profile->services()->findOrFail($id)->update(['active' => false]);
        }
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm       = false;
        $this->editingId      = null;
        $this->name           = '';
        $this->description    = '';
        $this->price          = '';
        $this->active         = true;
        $this->categoryId     = '';
        $this->imageFile1     = null;
        $this->imageFile2     = null;
        $this->imageFile3     = null;
        $this->existingImages = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $profile    = $this->profile();
        $services   = $profile
            ? $profile->services()->with('category')->latest()->get()
            : collect();
        $categories = ServiceCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('livewire.supplier.services', compact('profile', 'services', 'categories'));
    }
}
