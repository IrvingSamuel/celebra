<div>
@php
    $p = $theme['primary'];
    $bg = $theme['bg'];
    $tc = $theme['text'];
    $sectionIdx = 0; // alternating bg counter (hero excluded)
    $lastSectionBg = 'var(--ep-bg)';
@endphp

@foreach($page->blocks ?? [] as $block)
    @if(!($block['visible'] ?? true)) @continue @endif
    @php $c = $block['content'] ?? []; @endphp

    {{-- ══ HERO ════════════════════════════════════════════════════════ --}}
    @if($block['type'] === 'hero')
        @php
            $heroBg = $c['bg_image'] ?? '';
            $heroFitRaw = $c['bg_fit'] ?? 'cover';
            $heroFit = in_array($heroFitRaw, ['cover', 'contain', 'fill'], true) ? $heroFitRaw : 'cover';

            $heroAnchorRaw = $c['bg_anchor'] ?? 'center';
            $heroAnchorMap = [
                'top-left' => 'left top',
                'top' => 'center top',
                'top-right' => 'right top',
                'left' => 'left center',
                'center' => 'center center',
                'right' => 'right center',
                'bottom-left' => 'left bottom',
                'bottom' => 'center bottom',
                'bottom-right' => 'right bottom',
            ];
            $heroAnchor = $heroAnchorMap[$heroAnchorRaw] ?? 'center center';
        @endphp
        <section class="ep-hero relative min-h-[70vh] flex flex-col items-center justify-center text-center py-20 px-4"
                 style="background: var(--ep-bg);">
            @if($heroBg)
                <img src="{{ $heroBg }}" alt="Imagem de capa" class="absolute inset-0 w-full h-full" loading="lazy"
                     style="object-fit: {{ $heroFit }}; object-position: {{ $heroAnchor }};">
            @endif
            @if($heroBg)
                <div class="absolute inset-0" style="background: rgba(0,0,0,{{ ($c['overlay_opacity'] ?? 50) / 100 }});"></div>
            @endif
            <div class="relative z-10 max-w-2xl mx-auto" style="{{ $heroBg ? 'color:#fff;' : 'color: var(--ep-text);' }}">
                <h1 class="text-5xl sm:text-6xl font-bold leading-tight mb-4">{{ $c['title'] ?? $event->title }}</h1>
                @if($c['subtitle'] ?? '')
                    <p class="text-xl sm:text-2xl opacity-90 mb-2">{{ $c['subtitle'] }}</p>
                @endif
                @if($event->event_date)
                    <p class="text-lg opacity-75 mb-8">{{ $event->event_date->translatedFormat('d \d\e F \d\e Y') }}</p>
                @endif
                @if(($c['button_text'] ?? '') && ($c['button_target'] ?? ''))
                    <a href="#block-{{ $c['button_target'] }}"
                       class="inline-block px-8 py-3 rounded-full text-white font-semibold text-sm transition hover:opacity-90 shadow-lg"
                       style="background: var(--ep-primary);">
                        {{ $c['button_text'] }}
                    </a>
                @endif
            </div>
        </section>

    {{-- ══ MESSAGE ══════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'message')
        @php $sectionIdx++; $blockBg = $sectionIdx % 2 === 0 ? 'var(--ep-bg)' : 'var(--ep-alt-bg)'; $lastSectionBg = $blockBg; @endphp
        <section class="py-16 px-4" style="background: {{ $blockBg }}; color: var(--ep-text);">
            <div class="max-w-4xl mx-auto">
                @php $side = $c['image_side'] ?? 'right'; @endphp
                @if(($c['image'] ?? '') && $side === 'top')
                    <img src="{{ $c['image'] }}" alt="{{ $c['heading'] ?? '' }}" class="w-full max-h-72 object-cover rounded-2xl mb-8">
                @endif
                <div class="flex flex-col {{ ($c['image'] ?? '') && in_array($side, ['left','right']) ? ($side === 'right' ? 'md:flex-row' : 'md:flex-row-reverse') : '' }} items-center gap-10">
                    <div class="{{ ($c['image'] ?? '') && in_array($side, ['left','right']) ? 'flex-1' : 'max-w-2xl mx-auto text-center' }}">
                        @if($c['heading'] ?? '')
                            <h2 class="text-3xl sm:text-4xl font-bold mb-5" style="color: var(--ep-primary);">{{ $c['heading'] }}</h2>
                        @endif
                        @if($c['text'] ?? '')
                            <p class="text-lg leading-relaxed whitespace-pre-line" style="color: var(--ep-text); opacity: 0.85;">{{ $c['text'] }}</p>
                        @endif
                    </div>
                    @if(($c['image'] ?? '') && in_array($side, ['left','right']))
                        <div class="flex-1 max-w-sm">
                            <img src="{{ $c['image'] }}" alt="{{ $c['heading'] ?? '' }}" class="w-full rounded-2xl shadow-lg">
                        </div>
                    @endif
                </div>
            </div>
        </section>

    {{-- ══ COUNTDOWN ════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'countdown')
        @php $sectionIdx++; $lastSectionBg = 'var(--ep-primary)'; @endphp
        <section class="py-16 px-4 text-center" style="background: var(--ep-primary); color: #fff;">
            <div class="max-w-3xl mx-auto">
                @if($c['heading'] ?? '')
                    <h2 class="text-2xl font-semibold opacity-90 mb-8">{{ $c['heading'] }}</h2>
                @endif
                <div x-data="countdown('{{ $event->event_date?->format('Y-m-d') }}')"
                     x-init="startCountdown()"
                     class="grid grid-cols-4 gap-4 max-w-md mx-auto">
                    <div class="bg-white/20 rounded-2xl py-4 px-2">
                        <p class="text-4xl font-bold" x-text="days"></p>
                        <p class="text-xs uppercase tracking-widest opacity-80 mt-1">Dias</p>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4 px-2">
                        <p class="text-4xl font-bold" x-text="hours"></p>
                        <p class="text-xs uppercase tracking-widest opacity-80 mt-1">Horas</p>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4 px-2">
                        <p class="text-4xl font-bold" x-text="minutes"></p>
                        <p class="text-xs uppercase tracking-widest opacity-80 mt-1">Min</p>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4 px-2">
                        <p class="text-4xl font-bold" x-text="seconds"></p>
                        <p class="text-xs uppercase tracking-widest opacity-80 mt-1">Seg</p>
                    </div>
                </div>
                @if($c['note_text'] ?? '')
                    <p class="mt-8 text-lg opacity-80">{{ $c['note_text'] }}</p>
                @endif
            </div>
        </section>

    {{-- ══ GIFTS ════════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'gifts')
        @php $sectionIdx++; $blockBg = $sectionIdx % 2 === 0 ? 'var(--ep-bg)' : 'var(--ep-alt-bg)'; $lastSectionBg = $blockBg; @endphp
        <section id="block-gifts" class="py-16 px-4" style="background: {{ $blockBg }}; color: var(--ep-text);">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-3xl sm:text-4xl font-bold" style="color: var(--ep-primary);">{{ $c['heading'] ?? 'Lista de Presentes' }}</h2>
                    @if($c['subtitle'] ?? '')
                        <p class="mt-2 text-lg" style="color: var(--ep-text); opacity: 0.7;">{{ $c['subtitle'] }}</p>
                    @endif
                </div>
                @if($registry && $registry->items->isNotEmpty())
                    @php $cols = (int)($c['columns'] ?? 3); $gridClass = match($cols) { 2 => 'grid-cols-1 sm:grid-cols-2', 4 => 'grid-cols-2 sm:grid-cols-4', default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' }; @endphp
                    <div class="grid {{ $gridClass }} gap-6">
                        @foreach($registry->items as $item)
                            @php $full = $item->quantity_desired > 0 && $item->quantity_received >= $item->quantity_desired; @endphp
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow group border border-black/5">
                                <div class="aspect-square overflow-hidden bg-gray-50 relative">
                                    @if($item->image)
                                        <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ $full ? 'opacity-50' : '' }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-5xl"></div>
                                    @endif
                                    @if($full)
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md">Completo ✓</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold truncate" style="color: var(--ep-text);">{{ $item->name }}</h3>
                                    @if($item->description)
                                        <p class="text-sm mt-1 line-clamp-2 opacity-60" style="color: var(--ep-text);">{{ $item->description }}</p>
                                    @endif
                                    <div class="flex items-center justify-between mt-3">
                                        @if($item->price)
                                            <span class="font-bold" style="color: var(--ep-primary);">R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                                        @endif
                                        @if($item->quantity_desired > 0)
                                            <span class="text-xs opacity-50" style="color: var(--ep-text);">{{ $item->quantity_received }}/{{ $item->quantity_desired }}</span>
                                        @endif
                                    </div>
                                    @if($item->quantity_desired > 0)
                                        <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background: var(--ep-accent); opacity: 0.4;">
                                            <div class="h-full rounded-full" style="width: {{ min(100, ($item->quantity_received / $item->quantity_desired) * 100) }}%; background: var(--ep-primary); opacity: 1;"></div>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex flex-col gap-1.5">
                                        @if(! $full)
                                            <button wire:click="openPledge({{ $item->id }})"
                                                    class="w-full text-center text-sm font-semibold py-2.5 rounded-full transition hover:opacity-80 text-white"
                                                    style="background: var(--ep-primary);">
                                                Vou presentear
                                            </button>
                                        @endif
                                        @if($item->url)
                                            <a href="{{ $item->url }}" target="_blank" rel="noopener"
                                               class="block w-full text-center text-sm font-medium py-2 rounded-full border transition hover:opacity-80"
                                               style="border-color: var(--ep-primary); color: var(--ep-primary);">
                                                Ver produto →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-lg opacity-50" style="color: var(--ep-text);">A lista de presentes está sendo preparada.</p>
                @endif
            </div>
        </section>

    {{-- ══ SCHEDULE ════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'schedule')
        @php $sectionIdx++; $blockBg = $sectionIdx % 2 === 0 ? 'var(--ep-bg)' : 'var(--ep-alt-bg)'; $lastSectionBg = $blockBg; @endphp
        <section class="py-16 px-4" style="background: {{ $blockBg }}; color: var(--ep-text);">
            <div class="max-w-xl mx-auto">
                @if($c['heading'] ?? '')
                    <h2 class="text-3xl sm:text-4xl font-bold text-center mb-10" style="color: var(--ep-primary);">{{ $c['heading'] }}</h2>
                @endif
                <div class="relative pl-8">
                    <div class="absolute left-3 top-0 bottom-0 w-0.5" style="background: var(--ep-accent);"></div>
                    @foreach($c['items'] ?? [] as $itm)
                        <div class="relative mb-8 last:mb-0">
                            <div class="absolute -left-8 w-6 h-6 rounded-full flex items-center justify-center text-sm ring-4 ring-white"
                                 style="background: var(--ep-primary);">
                                {{ $itm['icon'] ?? '•' }}
                            </div>
                            <div class="bg-white rounded-2xl px-5 py-4 shadow-sm border border-black/5">
                                <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: var(--ep-primary);">{{ $itm['time'] ?? '' }}</p>
                                <p class="font-semibold text-lg" style="color: var(--ep-text);">{{ $itm['label'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    {{-- ══ LOCATION ════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'location')
        @php $sectionIdx++; $blockBg = $sectionIdx % 2 === 0 ? 'var(--ep-bg)' : 'var(--ep-alt-bg)'; $lastSectionBg = $blockBg; @endphp
        <section class="py-16 px-4" style="background: {{ $blockBg }}; color: var(--ep-text);">
            <div class="max-w-3xl mx-auto text-center">
                @if($c['heading'] ?? '')
                    <h2 class="text-3xl sm:text-4xl font-bold mb-8" style="color: var(--ep-primary);">{{ $c['heading'] }}</h2>
                @endif
                @if($c['image'] ?? '')
                    <img src="{{ $c['image'] }}" alt="{{ $c['venue_name'] ?? '' }}" class="w-full max-h-64 object-cover rounded-2xl mb-8 shadow-md">
                @endif
                @if($c['venue_name'] ?? '')
                    <h3 class="text-2xl font-bold mb-2" style="color: var(--ep-text);">{{ $c['venue_name'] }}</h3>
                @endif
                @if($c['address'] ?? '')
                    <p class="text-lg opacity-75 mb-6" style="color: var(--ep-text);">{{ $c['address'] }}</p>
                @endif
                @if($c['maps_url'] ?? '')
                    <a href="{{ $c['maps_url'] }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-white transition hover:opacity-90 shadow"
                       style="background: var(--ep-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Ver no Google Maps
                    </a>
                @endif
            </div>
        </section>

    {{-- ══ RSVP ════════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'rsvp')
        @php $sectionIdx++; $lastSectionBg = 'var(--ep-primary)'; @endphp
        <section id="block-rsvp" class="py-16 px-4" style="background: var(--ep-primary);">
            <div class="max-w-lg mx-auto">
                <div class="text-center mb-8 text-white">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-2">{{ $c['heading'] ?? 'Confirme sua Presença' }}</h2>
                    @if($c['subtitle'] ?? '')
                        <p class="opacity-80 text-lg">{{ $c['subtitle'] }}</p>
                    @endif
                </div>

                @if($rsvpSent)
                    <div class="bg-white/20 rounded-2xl p-8 text-center text-white">
                        <span class="text-5xl block mb-3">🎉</span>
                        <h3 class="text-xl font-bold">Presença confirmada!</h3>
                        <p class="opacity-80 mt-2">Obrigado, {{ $rsvpName }}! Estamos ansiosos para celebrar com você.</p>
                    </div>
                @else
                    <form wire:submit="submitRsvp" class="bg-white rounded-2xl p-6 space-y-4 shadow-xl">
                        @if($rsvpError)
                            <p class="text-sm text-red-600 bg-red-50 rounded-xl p-3">{{ $rsvpError }}</p>
                        @endif

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--ep-text);">Seu nome *</label>
                            <input wire:model="rsvpName" type="text" placeholder="Nome completo"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 outline-none transition @error('rsvpName') border-red-400 @enderror"
                                   style="--tw-ring-color: {{ $p }}40;">
                            @error('rsvpName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--ep-text);">E-mail</label>
                            <input wire:model="rsvpEmail" type="email" placeholder="seu@email.com"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 outline-none transition @error('rsvpEmail') border-red-400 @enderror">
                            @error('rsvpEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--ep-text);">Presença</label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 cursor-pointer rounded-xl border-2 px-4 py-3 transition
                                    {{ $rsvpAttending ? 'border-current bg-opacity-5' : 'border-gray-200' }}"
                                     style="{{ $rsvpAttending ? 'border-color: ' . $p . '; color: ' . $p . ';' : '' }}">
                                    <input wire:model="rsvpAttending" type="radio" :value="true" class="sr-only" value="1">
                                    <input type="radio" wire:model="rsvpAttending" value="1" class="accent-rose-600"> Confirmo presença
                                </label>
                                <label class="flex-1 flex items-center gap-2 cursor-pointer rounded-xl border-2 px-4 py-3 transition border-gray-200">
                                    <input type="radio" wire:model="rsvpAttending" value="0" class="accent-rose-600"> Não poderei ir
                                </label>
                            </div>
                        </div>

                        @if($rsvpAttending)
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--ep-text);">Quantidade de pessoas (incluindo você)</label>
                                <input wire:model="rsvpGuests" type="number" min="1" max="{{ $c['max_per_person'] ?? 10 }}"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 outline-none">
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--ep-text);">Mensagem (opcional)</label>
                            <textarea wire:model="rsvpMessage" rows="2" placeholder="Deixe um recado especial..."
                                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 outline-none resize-none"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3 rounded-full text-white font-semibold text-sm transition hover:opacity-90 mt-2"
                                style="background: var(--ep-primary);">
                            <span wire:loading.remove wire:target="submitRsvp">Confirmar</span>
                            <span wire:loading wire:target="submitRsvp">Enviando...</span>
                        </button>
                    </form>
                @endif
            </div>
        </section>

    {{-- ══ GALLERY ════════════════════════════════════════════════════ --}}
    @elseif($block['type'] === 'gallery')
        @php $sectionIdx++; $blockBg = $sectionIdx % 2 === 0 ? 'var(--ep-bg)' : 'var(--ep-alt-bg)'; $lastSectionBg = $blockBg; @endphp
        <section class="py-16 px-4" style="background: {{ $blockBg }}; color: var(--ep-text);">
            <div class="max-w-6xl mx-auto">
                @if($c['heading'] ?? '')
                    <h2 class="text-3xl sm:text-4xl font-bold text-center mb-10" style="color: var(--ep-primary);">{{ $c['heading'] }}</h2>
                @endif
                @if(!empty($c['images']))
                    @php $cols = (int)($c['columns'] ?? 3); $gridClass = match($cols) { 2 => 'grid-cols-2', 4 => 'grid-cols-2 sm:grid-cols-4', default => 'grid-cols-2 sm:grid-cols-3' }; @endphp
                    <div class="grid {{ $gridClass }} gap-3">
                        @foreach($c['images'] as $gImg)
                            <div class="aspect-square overflow-hidden rounded-2xl group cursor-pointer">
                                <img src="{{ $gImg }}" alt="Galeria" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
@endforeach

{{-- Gradient bridge from last section to footer --}}
<div style="height: 56px; background: linear-gradient(to bottom, {{ $lastSectionBg }} 90%, var(--ep-bg)) 100%;"></div>

{{-- Alpine.js countdown component --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', (targetDate) => ({
        days: '00', hours: '00', minutes: '00', seconds: '00',
        startCountdown() {
            if (!targetDate) return;
            const update = () => {
                const diff = new Date(targetDate + 'T00:00:00') - new Date();
                if (diff <= 0) {
                    this.days = this.hours = this.minutes = this.seconds = '00';
                    return;
                }
                const pad = n => String(Math.floor(n)).padStart(2, '0');
                this.days    = pad(diff / 86400000);
                this.hours   = pad((diff % 86400000) / 3600000);
                this.minutes = pad((diff % 3600000) / 60000);
                this.seconds = pad((diff % 60000) / 1000);
            };
            update();
            setInterval(update, 1000);
        }
    }));
});
</script>

{{-- ── Pledge Modal ──────────────────────────────────────────────────── --}}
@if($pledgingItemId !== null)
    @php $pledgeItem = $registry?->items->firstWhere('id', $pledgingItemId); @endphp
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
         x-data x-init="document.body.style.overflow='hidden'"
         x-destroy="document.body.style.overflow=''">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             wire:click="closePledge"></div>

        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header uses event theme primary --}}
            <div class="px-6 py-5 text-white" style="background: var(--ep-primary);">
                <button wire:click="closePledge"
                        class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <p class="text-xs font-semibold uppercase tracking-widest text-white/70 mb-1">Você vai presentear</p>
                <h3 class="font-semibold text-lg leading-snug pr-8">{{ $pledgeItem?->name ?? 'Presente' }}</h3>
                @if($pledgeItem?->price)
                    <p class="mt-1 text-sm text-white/80">R$ {{ number_format($pledgeItem->price, 2, ',', '.') }}</p>
                @endif
            </div>

            <div class="px-6 py-5">
                @if($pledgeSent)
                    <div class="text-center py-6">
                        <span class="text-5xl block mb-3">🎉</span>
                        <h4 class="font-semibold text-lg" style="color: var(--ep-text);">Que gentileza!</h4>
                        <p class="text-sm mt-2 leading-relaxed opacity-70" style="color: var(--ep-text);">
                            Enviamos uma confirmação para <strong>{{ $pledgerEmail }}</strong> com um link para cancelar caso mude de ideia.
                        </p>
                        <button wire:click="closePledge"
                                class="mt-5 w-full py-2.5 rounded-xl text-white text-sm font-semibold transition hover:opacity-90"
                                style="background: var(--ep-primary);">
                            Fechar
                        </button>
                    </div>
                @else
                    @if($pledgeError)
                        <p class="mb-4 text-sm text-red-600 bg-red-50 rounded-xl px-4 py-2.5">{{ $pledgeError }}</p>
                    @endif

                    <form wire:submit="submitPledge" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1.5 opacity-60" style="color: var(--ep-text);">Seu nome *</label>
                            <input type="text" wire:model="pledgerName"
                                   placeholder="Como você quer ser identificado"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[var(--ep-primary)]/30 focus:border-[var(--ep-primary)] transition
                                          @error('pledgerName') border-red-400 @enderror">
                            @error('pledgerName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1.5 opacity-60" style="color: var(--ep-text);">E-mail *</label>
                            <input type="email" wire:model="pledgerEmail"
                                   placeholder="Para enviar a confirmação"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[var(--ep-primary)]/30 focus:border-[var(--ep-primary)] transition
                                          @error('pledgerEmail') border-red-400 @enderror">
                            @error('pledgerEmail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1.5 opacity-60" style="color: var(--ep-text);">
                                Telefone / WhatsApp <span class="font-normal">(opcional)</span>
                            </label>
                            <input type="tel" wire:model="pledgerPhone"
                                   placeholder="(00) 00000-0000"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[var(--ep-primary)]/30 focus:border-[var(--ep-primary)] transition">
                        </div>

                        <p class="text-[10px] leading-relaxed opacity-50" style="color: var(--ep-text);">
                            Você receberá um e-mail de confirmação com um link para cancelar caso mude de ideia.
                        </p>

                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="submitPledge"
                                class="w-full py-3 rounded-xl text-white text-sm font-semibold transition hover:opacity-90 disabled:opacity-60"
                                style="background: var(--ep-primary);">
                            <span wire:loading.remove wire:target="submitPledge">Confirmar presente</span>
                            <span wire:loading wire:target="submitPledge">Confirmando…</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endif

</div>
