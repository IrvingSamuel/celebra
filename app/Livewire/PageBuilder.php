<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PageBuilder extends Component
{
    use WithFileUploads;

    public Event $event;
    public ?EventPage $page = null;

    public array  $blocks       = [];
    public string $theme        = 'romantic';
    public string $primaryColor = '';
    public bool   $published    = false;
    public bool   $saved        = false;

    // Editing state
    public ?string $editingBlockId = null;
    public array   $editContent    = [];
    public array   $editSettings   = [];

    // Temp gallery / schedule item inputs
    public string $newGalleryUrl    = '';
    public string $newScheduleTime  = '';
    public string $newScheduleLabel = '';
    public string $newScheduleIcon  = '🎉';

    // Temporary file uploads
    public $heroUpload     = null;
    public $messageUpload  = null;
    public $locationUpload = null;
    public $galleryUpload  = null;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->page = $this->event->eventPage ?? EventPage::make(['event_id' => $this->event->id]);

        $this->blocks       = $this->page->blocks ?? [];
        $this->theme        = $this->page->theme ?? 'romantic';
        $this->primaryColor = $this->page->primary_color ?? '';
        $this->published    = $this->page->published ?? false;
    }

    // ─── Block management ────────────────────────────────────────────

    public function addBlock(string $type): void
    {
        $defaults = EventPage::blockDefaults($type);
        if (empty($defaults)) {
            return;
        }
        $this->blocks[] = $defaults;
        $this->autoSave();
    }

    public function removeBlock(string $id): void
    {
        $this->blocks = array_values(array_filter($this->blocks, fn ($b) => $b['id'] !== $id));
        if ($this->editingBlockId === $id) {
            $this->closeEdit();
        }
        $this->autoSave();
    }

    public function reorderBlocks(array $orderedIds): void
    {
        $indexed = [];
        foreach ($this->blocks as $block) {
            $indexed[$block['id']] = $block;
        }

        $reordered = [];
        foreach ($orderedIds as $id) {
            if (isset($indexed[$id])) {
                $reordered[] = $indexed[$id];
            }
        }
        $this->blocks = $reordered;
        $this->autoSave();
    }

    public function moveBlock(string $id, string $direction): void
    {
        $idx = array_search($id, array_column($this->blocks, 'id'));
        if ($idx === false) {
            return;
        }
        $target = $direction === 'up' ? $idx - 1 : $idx + 1;
        if ($target < 0 || $target >= count($this->blocks)) {
            return;
        }
        [$this->blocks[$idx], $this->blocks[$target]] = [$this->blocks[$target], $this->blocks[$idx]];
        $this->autoSave();
    }

    public function toggleBlockVisibility(string $id): void
    {
        foreach ($this->blocks as &$block) {
            if ($block['id'] === $id) {
                $block['visible'] = ! ($block['visible'] ?? true);
                break;
            }
        }
        unset($block);
        $this->autoSave();
    }

    // ─── Block editing ───────────────────────────────────────────────

    public function openEdit(string $id): void
    {
        foreach ($this->blocks as $block) {
            if ($block['id'] === $id) {
                $this->editingBlockId = $id;
                $this->editContent    = $block['content'] ?? [];
                $this->editSettings   = $block['settings'] ?? [];

                // Backfill new hero settings for pages created before this feature.
                if (($block['type'] ?? '') === 'hero') {
                    $this->editContent['bg_fit'] = $this->editContent['bg_fit'] ?? 'cover';
                    $this->editContent['bg_anchor'] = $this->editContent['bg_anchor'] ?? 'center';
                }
                return;
            }
        }
    }

    public function closeEdit(): void
    {
        $this->editingBlockId  = null;
        $this->editContent     = [];
        $this->editSettings    = [];
        $this->newGalleryUrl   = '';
        $this->newScheduleTime = '';
        $this->newScheduleLabel = '';
    }

    public function saveBlockEdit(): void
    {
        // If a file is selected but not manually sent, persist it before applying.
        $this->persistPendingUploads();
        $this->syncEditingBlock();
        $this->closeEdit();
        $this->autoSave();
    }

    private function persistPendingUploads(): void
    {
        if ($this->heroUpload) {
            $this->uploadHeroBg();
        }
        if ($this->messageUpload) {
            $this->uploadMessageImage();
        }
        if ($this->locationUpload) {
            $this->uploadLocationImage();
        }
        if ($this->galleryUpload) {
            $this->uploadGalleryImage();
        }
    }

    private function syncEditingBlock(): void
    {
        foreach ($this->blocks as &$block) {
            if ($block['id'] === $this->editingBlockId) {
                $block['content']  = $this->editContent;
                $block['settings'] = $this->editSettings;
                break;
            }
        }
        unset($block);
    }

    // Schedule items
    public function addScheduleItem(): void
    {
        if (! $this->newScheduleTime || ! $this->newScheduleLabel) {
            return;
        }
        $this->editContent['items'][] = [
            'time'  => $this->newScheduleTime,
            'label' => $this->newScheduleLabel,
            'icon'  => $this->newScheduleIcon ?: '🎉',
        ];
        $this->newScheduleTime  = '';
        $this->newScheduleLabel = '';
        $this->newScheduleIcon  = '🎉';
    }

    public function removeScheduleItem(int $idx): void
    {
        array_splice($this->editContent['items'], $idx, 1);
    }

    // Gallery images
    public function addGalleryImage(): void
    {
        $url = trim($this->newGalleryUrl);
        if (! $url) {
            return;
        }
        $this->editContent['images'][] = $url;
        $this->newGalleryUrl = '';
    }

    public function removeGalleryImage(int $idx): void
    {
        array_splice($this->editContent['images'], $idx, 1);
    }

    // Image uploads
    public function uploadHeroBg(): void
    {
        if (! $this->editingBlockId) {
            return;
        }
        $this->validate(['heroUpload' => 'required|image|max:5120']);
        $path = $this->heroUpload->store('page-images', 'public');
        $this->editContent['bg_image'] = Storage::disk('public')->url($path);
        $this->heroUpload = null;
        $this->syncEditingBlock();
        $this->autoSave();
    }

    public function uploadMessageImage(): void
    {
        if (! $this->editingBlockId) {
            return;
        }
        $this->validate(['messageUpload' => 'required|image|max:5120']);
        $path = $this->messageUpload->store('page-images', 'public');
        $this->editContent['image'] = Storage::disk('public')->url($path);
        $this->messageUpload = null;
        $this->syncEditingBlock();
        $this->autoSave();
    }

    public function uploadLocationImage(): void
    {
        if (! $this->editingBlockId) {
            return;
        }
        $this->validate(['locationUpload' => 'required|image|max:5120']);
        $path = $this->locationUpload->store('page-images', 'public');
        $this->editContent['image'] = Storage::disk('public')->url($path);
        $this->locationUpload = null;
        $this->syncEditingBlock();
        $this->autoSave();
    }

    public function uploadGalleryImage(): void
    {
        if (! $this->editingBlockId) {
            return;
        }
        $this->validate(['galleryUpload' => 'required|image|max:5120']);
        $path = $this->galleryUpload->store('page-images', 'public');
        $this->editContent['images'][] = Storage::disk('public')->url($path);
        $this->galleryUpload = null;
        $this->syncEditingBlock();
        $this->autoSave();
    }

    // ─── Persistence ────────────────────────────────────────────────

    private function autoSave(): void
    {
        $this->persistPage();
        $this->saved = true;
    }

    public function save(): void
    {
        $this->persistPage();
        $this->saved = true;
    }

    public function togglePublish(): void
    {
        $this->published = ! $this->published;
        $this->persistPage();
        $this->saved = true;
    }

    private function persistPage(): void
    {
        $data = [
            'theme'         => $this->theme,
            'primary_color' => $this->primaryColor ?: null,
            'published'     => $this->published,
            'blocks'        => $this->blocks,
        ];

        if ($this->page && $this->page->exists) {
            $this->page->update($data);
        } else {
            $this->page = EventPage::create(array_merge(['event_id' => $this->event->id], $data));
        }
    }

    // ─── Render ─────────────────────────────────────────────────────

    public function render()
    {
        $themes = EventPage::themes();
        return view('livewire.page-builder', compact('themes'))
            ->layout('components.layouts.builder', ['title' => 'Editor de Página — ' . $this->event->title]);
    }
}
