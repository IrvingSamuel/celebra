<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SupplierProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierGigs extends Component
{
    use WithFileUploads;

    public SupplierProfile $supplier;

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $description = '';
    public string $price = '';
    public bool $active = true;
    public int|string $categoryId = '';

    public $imageFile1 = null;
    public $imageFile2 = null;
    public $imageFile3 = null;

    public array $existingImages = [];

    public function mount(int $id): void
    {
        $this->supplier = SupplierProfile::with(['user', 'category'])->findOrFail($id);
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

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $service = $this->supplier->services()->findOrFail($id);
        $this->editingId      = $id;
        $this->name           = $service->name;
        $this->description    = $service->description ?? '';
        $this->price          = (string) $service->price;
        $this->active         = $service->active;
        $this->categoryId     = $service->service_category_id ?? '';
        $this->existingImages = $service->images ?? [];
        $this->showForm       = true;
    }

    public function removeExistingImage(int $index): void
    {
        $path = $this->existingImages[$index] ?? null;
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('minio')->delete($path);
        }
        array_splice($this->existingImages, $index, 1);
    }

    public function save(): void
    {
        $data = $this->validate();

        $newPaths = [];
        foreach (['imageFile1', 'imageFile2', 'imageFile3'] as $field) {
            if ($this->$field) {
                $path = $this->$field->store('services', 'minio');
                $newPaths[] = $path;
            }
        }

        $allImages = array_values(array_slice(array_merge($this->existingImages, $newPaths), 0, 3));

        $serviceData = [
            'supplier_profile_id' => $this->supplier->id,
            'service_category_id' => $data['categoryId'],
            'name'        => $data['name'],
            'description' => $data['description'] ?: null,
            'price'       => $data['price'],
            'active'      => $data['active'],
            'images'      => ! empty($allImages) ? $allImages : null,
        ];

        if ($this->editingId) {
            $this->supplier->services()->findOrFail($this->editingId)->update($serviceData);
        } else {
            $serviceData['slug']   = Str::slug($data['name']) . '-' . Str::lower(Str::random(6));
            $serviceData['rating'] = 0;
            Service::create($serviceData);
        }

        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $service = $this->supplier->services()->findOrFail($id);
        $service->update(['active' => ! $service->active]);
    }

    public function delete(int $id): void
    {
        $this->supplier->services()->findOrFail($id)->delete();
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
        $services   = $this->supplier->services()->with('category')->latest()->get();
        $categories = ServiceCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('livewire.admin.supplier-gigs', compact('services', 'categories'));
    }
}
