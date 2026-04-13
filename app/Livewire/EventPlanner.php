<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Service;
use App\Models\Venue;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class EventPlanner extends Component
{
    public array $messages = [];
    public string $userMessage = '';
    public ?int $activeConversationId = null;

    // Planning context
    public string $eventType = '';
    public string $budget = '';
    public string $guests = '';
    public string $date = '';
    public string $preferences = '';

    // Flash action buttons shown after AI responses
    public array $suggestedActions = [];

    protected function getWelcomeMessage(): array
    {
        return [
            'role' => 'assistant',
            'content' => 'Olá! Eu sou a **Celi**, sua assistente de planejamento de eventos na Celebra! 🎉

Estou aqui para ajudar você a planejar o evento perfeito. Me conte:

**Que tipo de evento você quer planejar?** (Casamento, Formatura, Aniversário, 15 Anos, Confraternização, Conferência...)',
            'time' => now()->format('H:i'),
        ];
    }

    public function mount(?int $conversation = null)
    {
        if ($conversation) {
            $this->loadConversation($conversation);
        } else {
            $this->messages = [$this->getWelcomeMessage()];
        }
    }

    public function loadConversation(int $id)
    {
        $conv = Conversation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($conv) {
            $this->activeConversationId = $conv->id;
            $this->messages = $conv->messages ?? [$this->getWelcomeMessage()];
        }
    }

    public function newConversation()
    {
        $this->activeConversationId = null;
        $this->messages = [$this->getWelcomeMessage()];
        $this->userMessage = '';
        $this->eventType = '';
        $this->budget = '';
        $this->guests = '';
        $this->date = '';
        $this->preferences = '';
        $this->suggestedActions = [];
    }

    public function deleteConversation(int $id)
    {
        Conversation::where('id', $id)->where('user_id', Auth::id())->delete();

        if ($this->activeConversationId === $id) {
            $this->newConversation();
        }
    }

    public function sendMessage()
    {
        if (empty(trim($this->userMessage))) {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $this->userMessage,
            'time' => now()->format('H:i'),
        ];

        $this->userMessage = '';

        $this->js('$wire.fetchResponse()');
    }

    public function quickSend(string $message)
    {
        $this->userMessage = $message;
        $this->sendMessage();
    }

    public function fetchResponse()
    {
        try {
            $gemini = new GeminiService();
            $response = $gemini->planEvent([
                'event_type' => $this->eventType,
                'budget' => $this->budget,
                'guests' => $this->guests,
                'date' => $this->date,
                'preferences' => $this->preferences,
                'messages' => $this->messages,
            ]);

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response,
                'time' => now()->format('H:i'),
            ];
        } catch (\Throwable $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Desculpe, tive um problema ao processar sua mensagem. Pode tentar novamente?',
                'time' => now()->format('H:i'),
            ];
        }

        $this->saveConversation();
        $this->updateSuggestedActions();
    }

    protected function saveConversation(): void
    {
        // Generate title from the first user message
        $title = null;
        foreach ($this->messages as $msg) {
            if ($msg['role'] === 'user') {
                $title = \Illuminate\Support\Str::limit($msg['content'], 60);
                break;
            }
        }

        if ($this->activeConversationId) {
            Conversation::where('id', $this->activeConversationId)
                ->where('user_id', Auth::id())
                ->update(['messages' => $this->messages]);
        } else {
            $conv = Conversation::create([
                'user_id' => Auth::id(),
                'title' => $title ?? 'Nova conversa',
                'messages' => $this->messages,
                'status' => 'active',
            ]);
            $this->activeConversationId = $conv->id;
        }
    }

    protected function updateSuggestedActions(): void
    {
        $userMessages = array_filter($this->messages, fn($m) => $m['role'] === 'user');

        if (count($userMessages) >= 1) {
            $this->suggestedActions = [
                ['label' => '🎉 Criar evento a partir desta conversa', 'action' => 'createEventFromChat'],
                ['label' => '📋 Ver meus eventos', 'action' => 'goToDashboard'],
            ];
        } else {
            $this->suggestedActions = [];
        }
    }

    public function createEventFromChat(): mixed
    {
        // Try to find event type from context or messages
        $eventTypeId = null;
        $allTypes = EventType::all();

        // First check the explicit context field
        if ($this->eventType) {
            foreach ($allTypes as $type) {
                if (stripos($this->eventType, $type->name) !== false) {
                    $eventTypeId = $type->id;
                    break;
                }
            }
        }

        // Then scan messages
        if (!$eventTypeId) {
            foreach ($this->messages as $msg) {
                foreach ($allTypes as $type) {
                    if (stripos($msg['content'], $type->name) !== false) {
                        $eventTypeId = $type->id;
                        break 2;
                    }
                }
            }
        }

        $typeName = $eventTypeId ? ($allTypes->find($eventTypeId)?->name ?? 'Evento') : 'Evento';
        $title = $typeName . ' - ' . now()->format('Y');

        $slug = Str::slug($title);
        $original = $slug;
        $i = 2;
        while (Event::where('slug', $slug)->where('user_id', Auth::id())->exists()) {
            $slug = $original . '-' . $i++;
        }

        Event::create([
            'user_id'       => Auth::id(),
            'event_type_id' => $eventTypeId,
            'title'         => $title,
            'slug'          => $slug,
            'guest_count'   => $this->guests !== '' ? (int) $this->guests : null,
            'budget'        => $this->budget !== '' ? (float) $this->budget : null,
            'event_date'    => $this->date !== '' ? $this->date : null,
            'public'        => true,
        ]);

        return redirect()->route('event.edit', ['slug' => $slug]);
    }

    public function goToDashboard(): mixed
    {
        return redirect('/dashboard');
    }

    public function formatMessageContent(string $content): string
    {
        // 1. Extract [[VENUE:slug]] and [[SERVICE:slug]] tags, replace with placeholders
        $cards = [];
        $counter = 0;

        $content = preg_replace_callback('/\[\[VENUE:([\w-]+)\]\]/', function ($matches) use (&$cards, &$counter) {
            $slug = $matches[1];
            $venue = Venue::where('slug', $slug)->first();
            if (!$venue) {
                return $matches[0];
            }
            $key = "%%CARD_{$counter}%%";
            $counter++;

            $img = e($venue->image_url ?? $venue->image);
            $name = e($venue->name);
            $city = e($venue->city);
            $type = e($venue->type);
            $price = 'R$ ' . number_format($venue->price, 0, ',', '.');
            $capacity = number_format($venue->capacity, 0, ',', '.');
            $rating = $venue->rating;
            $url = '/espacos/' . e($venue->slug);

            $cards[$key] = '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="celi-card">'
                . '<img src="' . $img . '" alt="' . $name . '" class="celi-card-img">'
                . '<span class="celi-card-body">'
                . '<span class="celi-card-title">' . $name . '</span>'
                . '<span class="celi-card-sub">' . $city . ' · ' . $type . '</span>'
                . '<span class="celi-card-price">' . $price . '</span>'
                . '<span class="celi-card-meta">'
                . '<span>' . $capacity . ' pessoas</span>'
                . '<span class="celi-card-rating">★ ' . $rating . '</span>'
                . '</span></span>'
                . '<span class="celi-card-arrow">→</span></a>';

            return "\n\n" . $key . "\n\n";
        }, $content);

        $content = preg_replace_callback('/\[\[SERVICE:([\w-]+)\]\]/', function ($matches) use (&$cards, &$counter) {
            $slug = $matches[1];
            $service = Service::where('slug', $slug)->with('category')->first();
            if (!$service) {
                return $matches[0];
            }
            $key = "%%CARD_{$counter}%%";
            $counter++;

            $img = e($service->image_url ?? $service->image);
            $name = e($service->name);
            $category = e($service->category->name ?? 'Serviço');
            $categorySlug = e($service->category->slug ?? 'outros');
            $price = 'R$ ' . number_format($service->price, 0, ',', '.');
            $rating = $service->rating;
            $url = '/servicos/' . $categorySlug;

            $cards[$key] = '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="celi-card">'
                . '<img src="' . $img . '" alt="' . $name . '" class="celi-card-img">'
                . '<span class="celi-card-body">'
                . '<span class="celi-card-title">' . $name . '</span>'
                . '<span class="celi-card-sub">' . $category . '</span>'
                . '<span class="celi-card-price">' . $price . '</span>'
                . '<span class="celi-card-meta">'
                . '<span class="celi-card-rating">★ ' . $rating . '</span>'
                . '</span></span>'
                . '<span class="celi-card-arrow">→</span></a>';

            return "\n\n" . $key . "\n\n";
        }, $content);

        // 2. Convert markdown to HTML
        $html = Str::markdown($content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        // 3. Replace placeholders with card HTML (removing any wrapping <p> tags)
        foreach ($cards as $key => $cardHtml) {
            $html = preg_replace('/<p>\s*' . preg_quote($key, '/') . '\s*<\/p>/', $cardHtml, $html);
            // Fallback: replace bare placeholder if not wrapped in <p>
            $html = str_replace($key, $cardHtml, $html);
        }

        return $html;
    }

    public function render()
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->get();

        return view('livewire.event-planner', [
            'eventTypes' => EventType::orderBy('sort_order')->get(),
            'conversations' => $conversations,
        ]);
    }
}
