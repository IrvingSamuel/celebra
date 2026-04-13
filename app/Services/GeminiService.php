<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Venue;
use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->model = config('gemini.model');
        $this->baseUrl = config('gemini.base_url');
    }

    public function chat(array $conversationHistory, ?string $systemPrompt = null): string
    {
        $contents = [];

        if ($systemPrompt) {
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $systemPrompt]],
            ];
            $contents[] = [
                'role' => 'model',
                'parts' => [['text' => 'Entendido! Estou pronta para ajudar.']],
            ];
        }

        foreach ($conversationHistory as $message) {
            $contents[] = [
                'role' => $message['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $message['content']]],
            ];
        }

        $response = Http::connectTimeout(10)->timeout(90)->post(
            "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
            [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 2048,
                ],
            ]
        );

        if ($response->failed()) {
            return 'Desculpe, não consegui processar sua mensagem no momento. Tente novamente.';
        }

        return $response->json('candidates.0.content.parts.0.text', 'Sem resposta.');
    }

    public function planEvent(array $context): string
    {
        $venues = Venue::where('active', true)->orderByDesc('rating')->limit(15)->get(['name', 'slug', 'city', 'type', 'price', 'capacity', 'rating']);
        $services = Service::where('active', true)->orderByDesc('rating')->limit(15)->with('category')->get(['id', 'name', 'slug', 'service_category_id', 'price', 'rating']);

        $venueList = $venues->map(function ($v) {
            return "- {$v->name} (slug: {$v->slug}) — {$v->city}, {$v->type} — R$ " . number_format($v->price, 0, ',', '.') . " — {$v->capacity} pessoas — ★{$v->rating}";
        })->implode("\n");

        $serviceList = $services->map(function ($s) {
            $categoryName = $s->category->name ?? 'N/A';
            return "- {$s->name} (slug: {$s->slug}) — {$categoryName} — R$ " . number_format($s->price, 0, ',', '.') . " — ★{$s->rating}";
        })->implode("\n");

        $eventType = $context['event_type'] ?? 'Não definido';
        $budget = $context['budget'] ?? 'Não definido';
        $guests = $context['guests'] ?? 'Não definido';
        $date = $context['date'] ?? 'Não definida';
        $preferences = $context['preferences'] ?? 'Nenhuma';
        // Mantém apenas as últimas 10 mensagens para não inflar o payload
        $allMessages = $context['messages'] ?? [];
        $messages = array_slice($allMessages, -10);

        $systemPrompt = <<<PROMPT
Você é a Celi, assistente inteligente da plataforma Celebra — uma plataforma de planejamento de eventos que conecta clientes a fornecedores.

Seu papel é ajudar o usuário a planejar seu evento de forma conversacional e amigável. Você deve:
1. Fazer perguntas abertas para entender o evento (tipo, data, orçamento, número de convidados, preferências)
2. Recomendar espaços e serviços da nossa base de dados
3. Ser calorosa, profissional e objetiva
4. Sempre responder em português brasileiro
5. Usar formatação com **negrito** e listas quando apropriado

REGRAS PARA RECOMENDAÇÕES (MUITO IMPORTANTE):
- Quando recomendar um ESPAÇO, use EXATAMENTE esta tag numa linha própria: [[VENUE:slug-do-espaco]]
- Quando recomendar um SERVIÇO, use EXATAMENTE esta tag numa linha própria: [[SERVICE:slug-do-servico]]
- O slug deve ser exatamente o slug listado entre parênteses nos dados abaixo
- Coloque cada tag em sua própria linha, separada do texto
- Pode adicionar comentários breves antes ou depois, mas a tag DEVE ficar sozinha na linha
- Exemplo correto:
  Aqui estão minhas recomendações de espaços:

  [[VENUE:jardim-imperial]]

  [[VENUE:villa-toscana]]

  E para fotografia:

  [[SERVICE:clique-perfeito-fotografia]]

- NUNCA use markdown de imagem ou links para recomendações. Use APENAS as tags [[VENUE:...]] e [[SERVICE:...]]

ESPAÇOS DISPONÍVEIS:
{$venueList}

SERVIÇOS DISPONÍVEIS:
{$serviceList}

CONTEXTO DO EVENTO:
Tipo: {$eventType}
Orçamento: {$budget}
Convidados: {$guests}
Data: {$date}
Preferências: {$preferences}
PROMPT;

        return $this->chat($messages, $systemPrompt);
    }
}
