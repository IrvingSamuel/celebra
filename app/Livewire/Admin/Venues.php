<?php

namespace App\Livewire\Admin;

use App\Models\Venue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Venues extends Component
{
    use WithPagination, WithFileUploads;

    #[Url]
    public string $search = '';

    // Form state
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $description = '';
    public string $city = '';
    public string $state = '';
    public string $address = '';
    public string $type = 'indoor';
    public string $capacity = '';
    public string $price = '';
    public bool $active = true;

    public string $existingImage = '';
    public $imageFile = null;

    public array $existingGallery = [];
    public $galleryFile1 = null;
    public $galleryFile2 = null;
    public $galleryFile3 = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:3000',
            'city'         => 'required|string|max:100',
            'state'        => 'required|string|size:2',
            'address'      => 'nullable|string|max:255',
            'type'         => 'required|in:indoor,outdoor,ambos',
            'capacity'     => 'nullable|integer|min:1|max:99999',
            'price'        => 'nullable|numeric|min:0',
            'active'       => 'boolean',
            'imageFile'    => 'nullable|image|max:5120',
            'galleryFile1' => 'nullable|image|max:5120',
            'galleryFile2' => 'nullable|image|max:5120',
            'galleryFile3' => 'nullable|image|max:5120',
        ];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $venue = Venue::findOrFail($id);
        $this->editingId      = $id;
        $this->name           = $venue->name;
        $this->description    = $venue->description ?? '';
        $this->city           = $venue->city;
        $this->state          = $venue->state;
        $this->address        = $venue->address ?? '';
        $this->type           = $venue->type;
        $this->capacity       = $venue->capacity !== null ? (string) $venue->capacity : '';
        $this->price          = $venue->price !== null ? (string) $venue->price : '';
        $this->active         = $venue->active;
        $this->existingImage  = $venue->image ?? '';
        $this->existingGallery = $venue->gallery ?? [];
        $this->showForm       = true;
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function removeExistingGalleryImage(int $index): void
    {
        $path = $this->existingGallery[$index] ?? null;
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('minio')->delete($path);
        }
        array_splice($this->existingGallery, $index, 1);
    }

    public function save(): void
    {
        $data = $this->validate();

        // Imagem principal
        $imagePath = $this->existingImage ?: null;
        if ($this->imageFile) {
            if ($imagePath && ! str_starts_with($imagePath, 'http')) {
                Storage::disk('minio')->delete($imagePath);
            }
            $imagePath = $this->imageFile->store('venues', 'minio');
        }

        // Galeria
        $gallery = $this->existingGallery;
        foreach (['galleryFile1', 'galleryFile2', 'galleryFile3'] as $field) {
            if ($this->$field) {
                $gallery[] = $this->$field->store('venues/gallery', 'minio');
            }
        }

        $payload = [
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']) . ($this->editingId ? '-' . $this->editingId : '-' . time()),
            'description' => $data['description'] ?: null,
            'city'        => $data['city'],
            'state'       => strtoupper($data['state']),
            'address'     => $data['address'] ?: null,
            'type'        => $data['type'],
            'capacity'    => $data['capacity'] !== '' && $data['capacity'] !== null ? (int) $data['capacity'] : null,
            'price'       => $data['price'] !== '' && $data['price'] !== null ? $data['price'] : null,
            'active'      => $data['active'],
            'image'       => $imagePath,
            'gallery'     => ! empty($gallery) ? $gallery : null,
        ];

        if ($this->editingId) {
            $venue = Venue::findOrFail($this->editingId);
            // Preserve slug if name hasn't changed
            if ($venue->name === $payload['name']) {
                $payload['slug'] = $venue->slug;
            }
            $venue->update($payload);
        } else {
            Venue::create($payload);
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $venue = Venue::findOrFail($id);
        $venue->update(['active' => ! $venue->active]);
    }

    public function delete(int $id): void
    {
        $venue = Venue::findOrFail($id);
        if ($venue->image && ! str_starts_with($venue->image, 'http')) {
            Storage::disk('minio')->delete($venue->image);
        }
        foreach ($venue->gallery ?? [] as $img) {
            if (! str_starts_with($img, 'http')) {
                Storage::disk('minio')->delete($img);
            }
        }
        $venue->delete();
    }

    private function resetForm(): void
    {
        $this->editingId       = null;
        $this->name            = '';
        $this->description     = '';
        $this->city            = '';
        $this->state           = '';
        $this->address         = '';
        $this->type            = 'indoor';
        $this->capacity        = '';
        $this->price           = '';
        $this->active          = true;
        $this->existingImage   = '';
        $this->existingGallery = [];
        $this->imageFile       = null;
        $this->galleryFile1    = null;
        $this->galleryFile2    = null;
        $this->galleryFile3    = null;
    }

    public function render()
    {
        $query = Venue::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%');
            });
        }

        $venues     = $query->latest()->paginate(20);
        $totalCount  = Venue::count();
        $activeCount = Venue::where('active', true)->count();

        return view('livewire.admin.venues', compact('venues', 'totalCount', 'activeCount'));
    }
}
