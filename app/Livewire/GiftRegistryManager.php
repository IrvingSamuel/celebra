<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\GiftItem;
use App\Models\GiftRegistry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class GiftRegistryManager extends Component
{
    public Event $event;
    public GiftRegistry $registry;

    // URL input
    public string $productUrl = '';

    // Item fields (form)
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public string $image = '';
    public string $url = '';
    public string $platform = '';
    public int $quantityDesired = 1;
    public ?int $editingItemId = null;

    // UI state
    public bool $fetching = false;
    public bool $fetchError = false;
    public string $fetchErrorMessage = '';
    public bool $showForm = false;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->where('gift_registry_enabled', true)
            ->firstOrFail();

        $this->registry = $this->event->giftRegistry ?? GiftRegistry::create([
            'event_id'    => $this->event->id,
            'title'       => $this->event->title,
            'description' => null,
            'active'      => true,
        ]);
    }

    public function fetchFromUrl(): void
    {
        $this->resetFetchState();

        $this->validate(['productUrl' => 'required|url|max:2048']);

        $url = $this->productUrl;

        // SSRF protection: only allow http/https and public IPs
        $parsed = parse_url($url);
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (! in_array($scheme, ['http', 'https'], true)) {
            $this->fetchError = true;
            $this->fetchErrorMessage = 'Apenas URLs http/https são permitidas.';
            return;
        }

        $host = $parsed['host'] ?? '';
        $ip = gethostbyname($host);
        if ($this->isPrivateIp($ip)) {
            $this->fetchError = true;
            $this->fetchErrorMessage = 'URL não permitida.';
            return;
        }

        $this->fetching = true;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; CelebraBot/1.0)',
                'Accept'     => 'text/html,application/xhtml+xml',
            ])->timeout(10)->get($url);

            if (! $response->successful()) {
                $this->fetchError = true;
                $this->fetchErrorMessage = 'Não foi possível acessar a URL. Verifique e tente novamente.';
                $this->fetching = false;
                return;
            }

            $html = $response->body();
            $data = $this->parseMetaTags($html, $url);

            $this->name        = $data['title'];
            $this->description = $data['description'];
            $this->price       = $data['price'];
            $this->image       = $data['image'];
            $this->url         = $url;
            $this->platform    = $data['platform'];
            $this->showForm    = true;

        } catch (\Exception) {
            $this->fetchError = true;
            $this->fetchErrorMessage = 'Erro ao buscar dados do produto. Tente novamente.';
        }

        $this->fetching = false;
    }

    public function saveItem(): void
    {
        $this->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'price'           => 'nullable|numeric|min:0',
            'image'           => 'nullable|url|max:2048',
            'url'             => 'nullable|url|max:2048',
            'platform'        => 'nullable|string|max:100',
            'quantityDesired' => 'integer|min:1|max:9999',
        ]);

        $data = [
            'gift_registry_id' => $this->registry->id,
            'name'             => $this->name,
            'description'      => $this->description ?: null,
            'price'            => $this->price !== '' ? (float) $this->price : null,
            'image'            => $this->image ?: null,
            'url'              => $this->url ?: null,
            'platform'         => $this->platform ?: null,
            'quantity_desired' => $this->quantityDesired,
        ];

        if ($this->editingItemId) {
            GiftItem::where('id', $this->editingItemId)
                ->where('gift_registry_id', $this->registry->id)
                ->update($data);
        } else {
            GiftItem::create($data);
        }

        $this->resetForm();
    }

    public function editItem(int $id): void
    {
        $item = GiftItem::where('id', $id)
            ->where('gift_registry_id', $this->registry->id)
            ->firstOrFail();

        $this->editingItemId  = $item->id;
        $this->name           = $item->name;
        $this->description    = $item->description ?? '';
        $this->price          = (string) ($item->price ?? '');
        $this->image          = $item->image ?? '';
        $this->url            = $item->url ?? '';
        $this->platform       = $item->platform ?? '';
        $this->quantityDesired = $item->quantity_desired ?? 1;
        $this->productUrl     = $item->url ?? '';
        $this->showForm       = true;
    }

    public function deleteItem(int $id): void
    {
        GiftItem::where('id', $id)
            ->where('gift_registry_id', $this->registry->id)
            ->delete();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.gift-registry-manager', [
            'items' => $this->registry->items()->with('activePledges')->latest()->get(),
        ]);
    }

    // ─── Private helpers ────────────────────────────────────────────────────

    private function resetFetchState(): void
    {
        $this->fetchError        = false;
        $this->fetchErrorMessage = '';
    }

    private function resetForm(): void
    {
        $this->editingItemId  = null;
        $this->productUrl     = '';
        $this->name           = '';
        $this->description    = '';
        $this->price          = '';
        $this->image          = '';
        $this->url            = '';
        $this->platform       = '';
        $this->quantityDesired = 1;
        $this->showForm       = false;
        $this->resetFetchState();
    }

    private function isPrivateIp(string $ip): bool
    {
        return ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    private function parseMetaTags(string $html, string $sourceUrl): array
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        $xpath = new \DOMXPath($doc);

        $get = function (string $property) use ($xpath): string {
            foreach (['property', 'name'] as $attr) {
                $nodes = $xpath->query("//meta[@{$attr}='{$property}']/@content");
                if ($nodes && $nodes->length > 0) {
                    return trim((string) $nodes->item(0)->nodeValue);
                }
            }
            return '';
        };

        // Title: og:title → twitter:title → <title>
        $title = $get('og:title') ?: $get('twitter:title');
        if (!$title) {
            $titleNodes = $xpath->query('//title');
            $title = $titleNodes && $titleNodes->length > 0
                ? trim((string) $titleNodes->item(0)->nodeValue)
                : '';
        }

        // Description
        $description = $get('og:description') ?: $get('twitter:description') ?: $get('description');

        // Image
        $image = $get('og:image') ?: $get('twitter:image');

        // Price: try meta tags first, then JSON-LD, then extract from title text
        $price = $get('product:price:amount')
            ?: $get('product:price')
            ?: $get('og:price:amount')
            ?: $this->extractPriceFromJsonLd($doc, $xpath)
            ?: $this->extractPriceFromText($title);

        $price = $this->normalizePrice($price);

        // Platform: og:site_name → extract meaningful part from domain
        $platform = $get('og:site_name') ?: $this->extractPlatformFromHost(
            parse_url($sourceUrl, PHP_URL_HOST) ?? ''
        );

        return compact('title', 'description', 'image', 'price', 'platform');
    }

    private function extractPriceFromJsonLd(\DOMDocument $doc, \DOMXPath $xpath): string
    {
        $scripts = $xpath->query('//script[@type="application/ld+json"]');
        if (! $scripts) {
            return '';
        }

        foreach ($scripts as $script) {
            $data = json_decode((string) $script->nodeValue, true);
            if (! is_array($data)) {
                continue;
            }
            // Handle array of schemas
            $items = isset($data[0]) ? $data : [$data];
            foreach ($items as $item) {
                $price = $this->findPriceInSchema($item);
                if ($price !== '') {
                    return $price;
                }
            }
        }

        return '';
    }

    private function findPriceInSchema(array $data, int $depth = 0): string
    {
        if ($depth > 4) {
            return '';
        }

        // Direct price fields
        foreach (['price', 'lowPrice', 'highPrice'] as $key) {
            if (isset($data[$key]) && is_scalar($data[$key]) && $data[$key] !== '') {
                return (string) $data[$key];
            }
        }

        // offers object or array
        if (isset($data['offers'])) {
            $offers = isset($data['offers'][0]) ? $data['offers'] : [$data['offers']];
            foreach ($offers as $offer) {
                if (is_array($offer)) {
                    $p = $this->findPriceInSchema($offer, $depth + 1);
                    if ($p !== '') {
                        return $p;
                    }
                }
            }
        }

        return '';
    }

    private function extractPriceFromText(string $text): string
    {
        // Match "R$ 1.299,90" or "R$1299,90" patterns
        if (preg_match('/R\$\s*([\d.,]+)/u', $text, $m)) {
            return $m[1];
        }

        return '';
    }

    private function normalizePrice(string $raw): string
    {
        // Remove all non-numeric chars except . and ,
        $price = preg_replace('/[^\d.,]/', '', $raw);

        if ($price === '' || $price === null) {
            return '';
        }

        // "1.299,90" (BR format with thousands dot) → "1299.90"
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d{1,2})?$/', $price)) {
            $price = str_replace('.', '', $price);
            $price = str_replace(',', '.', $price);
        } else {
            // "1299,90" → "1299.90"
            $price = str_replace(',', '.', $price);
        }

        return is_numeric($price) ? $price : '';
    }

    private function extractPlatformFromHost(string $host): string
    {
        $host = strtolower(preg_replace('/^www\d*\./', '', $host));
        $parts = explode('.', $host);

        // Strip known generic TLD segments from the right
        $tlds = ['com', 'net', 'org', 'edu', 'gov', 'br', 'ar', 'mx', 'pt', 'co', 'info', 'io', 'app'];
        while (count($parts) > 1 && in_array(end($parts), $tlds, true)) {
            array_pop($parts);
        }

        return ucfirst($parts[0] ?? $host);
    }
}
