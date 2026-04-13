<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\EventType;
use App\Models\Service;
use App\Models\Venue;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class CeliChat extends Component
{
    public array $messages = [];
    public string $userMessage = '';
    public ?int $activeConversationId = null;

    public string $eventType = '';
    public string $budget = '';
    public string $guests = '';
    public string $date = '';
    public string $preferences = '';

    protected function getWelcomeMessage(): array
    {
        return [
            'role' => 'assistant',
            'content' => 'Olá! Eu sou a **Celi**, sua assistente de planejamento de eventos! 🎉

Que tipo de evento você quer planejar? (Casamento, Formatura, Aniversário, 15 Anos...)',
            'time' => now()->format('H:i'),
        ];
    }

    public function mount(): void
    {
        $this->messages = [$this->getWelcomeMessage()];
    }

    public function newConversation(): void
    {
        $this->activeConversationId = null;
        $this->messages = [$this->getWelcomeMessage()];
        $this->userMessage = '';
        $this->eventType = '';
        $this->budget = '';
        $this->guests = '';
        $this->date = '';
        $this->preferences = '';
    }

    public function loadConversation(int $id): void
    {
        $conv = Conversation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($conv) {
            $this->activeConversationId = $conv->id;
            $this->messages = $conv->messages ?? [$this->getWelcomeMessage()];
        }
    }

    public function deleteConversation(int $id): void
    {
        Conversation::where('id', $id)->where('user_id', Auth::id())->delete();

        if ($this->activeConversationId === $id) {
            $this->newConversation();
        }
    }

    public function sendMessage(): void
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

    public function quickSend(string $message): void
    {
        $this->userMessage = $message;
        $this->sendMessage();
    }

    public function fetchResponse(): void
    {
        try {
            $gemini = new GeminiService();
            $response = $gemini->planEvent([
                'event_type' => $this->eventType,
                'budget'     => $this->budget,
                'guests'     => $this->guests,
                'date'       => $this->date,
                'preferences'=> $this->preferences,
                'messages'   => $this->messages,
            ]);

            $this->messages[] = [
                'role'    => 'assistant',
                'content' => $response,
                'time'    => now()->format('H:i'),
            ];
        } catch (\Throwable $e) {
            logger()->error('CeliChat fetchResponse error', [
                'message' => $e->getMessage(),
                'class'   => get_class($e),
            ]);
            $this->messages[] = [
                'role'    => 'assistant',
                'content' => 'Desculpe, tive um problema ao processar sua mensagem. Pode tentar novamente?',
                'time'    => now()->format('H:i'),
            ];
        }

        $this->saveConversation();
    }

    protected function saveConversation(): void
    {
        $title = null;
        foreach ($this->messages as $msg) {
            if ($msg['role'] === 'user') {
                $title = Str::limit($msg['content'], 60);
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
                'title'   => $title ?? 'Nova conversa',
                'messages'=> $this->messages,
                'status'  => 'active',
            ]);
            $this->activeConversationId = $conv->id;
        }
    }

    public function formatMessageContent(string $content): string
    {
        $cards   = [];
        $counter = 0;

        $content = preg_replace_callback('/\[\[VENUE:([\w-]+)\]\]/', function ($matches) use (&$cards, &$counter) {
            $slug  = $matches[1];
            $venue = Venue::where('slug', $slug)->first();
            if (!$venue) {
                return $matches[0];
            }
            $key = "%%CARD_{$counter}%%";
            $counter++;

            $img      = e($venue->image_url);
            $name     = e($venue->name);
            $city     = e($venue->city);
            $type     = e($venue->type);
            $price    = 'R$ ' . number_format($venue->price, 0, ',', '.');
            $capacity = number_format($venue->capacity, 0, ',', '.');
            $rating   = $venue->rating;
            $url      = '/espacos/' . e($venue->slug);

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
            $slug    = $matches[1];
            $service = Service::where('slug', $slug)->with('category')->first();
            if (!$service) {
                return $matches[0];
            }
            $key = "%%CARD_{$counter}%%";
            $counter++;

            $raw          = $service->primary_image;
            $img          = e($raw && !str_starts_with($raw, 'http') ? \Illuminate\Support\Facades\Storage::disk('minio')->url($raw) : ($raw ?? ''));
            $name         = e($service->name);
            $category     = e($service->category->name ?? 'Serviço');
            $categorySlug = e($service->category->slug ?? 'outros');
            $price        = 'R$ ' . number_format($service->price, 0, ',', '.');
            $rating       = $service->rating;
            $url          = '/servicos/' . $categorySlug;

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

        $html = Str::markdown($content, [
            'html_input'          => 'strip',
            'allow_unsafe_links'  => false,
        ]);

        foreach ($cards as $key => $cardHtml) {
            $html = preg_replace('/<p>\s*' . preg_quote($key, '/') . '\s*<\/p>/', $cardHtml, $html);
            $html = str_replace($key, $cardHtml, $html);
        }

        return $html;
    }

    public function render()
    {
        $conversations = Auth::check()
            ? Conversation::where('user_id', Auth::id())->orderByDesc('updated_at')->get()
            : collect();

        return view('livewire.celi-chat', [
            'eventTypes'    => EventType::orderBy('sort_order')->get(),
            'conversations' => $conversations,
        ]);
    }
}
