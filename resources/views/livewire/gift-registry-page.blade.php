<div>
    {{-- Pledge cancelled flash --}}
    @if(session('pledge_cancelled'))
        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium"
             x-data x-init="setTimeout(() => $el.remove(), 4000)">
            ✓ Sua marcação foi cancelada com sucesso.
        </div>
    @endif

    {{-- Hero --}}
    <div class="relative overflow-hidden">

        @if($event->cover_image)
            <div class="absolute inset-0 z-0">
                <img src="{{ $event->cover_image }}" alt="" class="w-full h-full object-cover scale-110">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-rose-100 via-pink-50 to-amber-50"></div>
        @endif

        <div class="relative z-10 max-w-2xl mx-auto px-6 py-20 text-center">
            @if($event->cover_image)
                <div class="w-24 h-24 rounded-full mx-auto mb-6 shadow-xl ring-4 ring-white/40 overflow-hidden">
                    <img src="{{ $event->cover_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-20 h-20 rounded-full mx-auto mb-6 bg-white/80 flex items-center justify-center shadow-xl ring-4 ring-white/40 text-4xl">
                    {{ $event->eventType->icon ?? '🎉' }}
                </div>
            @endif

            <h1 class="gr-serif text-4xl sm:text-5xl font-semibold leading-tight
                {{ $event->cover_image ? 'text-white drop-shadow' : 'text-[#1c1917]' }}">
                {{ $event->title }}
            </h1>

            @if($event->event_date)
                <p class="mt-3 text-sm tracking-widest uppercase
                    {{ $event->cover_image ? 'text-white/70' : 'text-[#78716c]' }}">
                    {{ $event->event_date->translatedFormat('d \d\e F \d\e Y') }}
                </p>
            @endif

            @if($event->message)
                <p class="mt-5 text-base leading-relaxed max-w-lg mx-auto
                    {{ $event->cover_image ? 'text-white/85' : 'text-[#57534e]' }}">
                    {{ $event->message }}
                </p>
            @endif
        </div>

        <div class="relative z-10 -mb-px">
            <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full" preserveAspectRatio="none" style="height:56px">
                <path d="M0 56 C360 0 1080 0 1440 56 L1440 56 L0 56 Z" fill="#faf9f7"/>
            </svg>
        </div>
    </div>

    {{-- Registry section --}}
    @if($registry)
        <section class="py-14 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
            <div class="text-center mb-10">
                <span class="inline-block text-xs font-semibold tracking-widest uppercase text-primary/60 mb-2">
                    {{ $registry->title ?? 'Lista de Presentes' }}
                </span>
                <div class="flex items-center justify-center gap-3">
                    <div class="h-px w-16 bg-primary/20"></div>
                    <span class="text-2xl">✦</span>
                    <div class="h-px w-16 bg-primary/20"></div>
                </div>
                @if($registry->description)
                    <p class="mt-4 text-[#78716c] text-sm max-w-md mx-auto">{{ $registry->description }}</p>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                @forelse($items as $item)
                    @php $full = $item->quantity_desired > 0 && $item->quantity_received >= $item->quantity_desired; @endphp
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col">

                        <div class="aspect-square bg-[#f5f5f4] overflow-hidden shrink-0 relative">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $full ? 'opacity-50' : '' }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-5xl opacity-30">✦</span>
                                </div>
                            @endif
                            @if($full)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md">Completo ✓</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-3.5 flex flex-col flex-1">
                            <h3 class="text-sm font-medium text-[#1c1917] leading-snug line-clamp-2 flex-1">
                                {{ $item->name }}
                            </h3>

                            @if($item->platform)
                                <span class="mt-1.5 text-[10px] font-medium text-[#a8a29e] uppercase tracking-wide">
                                    {{ $item->platform }}
                                </span>
                            @endif

                            @if($item->price)
                                <p class="mt-2 text-primary font-semibold text-sm">
                                    R$ {{ number_format($item->price, 2, ',', '.') }}
                                </p>
                            @endif

                            @if($item->quantity_desired > 0)
                                <div class="mt-2 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] text-[#a8a29e]">
                                            {{ $item->quantity_received }}/{{ $item->quantity_desired }}
                                            {{ $item->quantity_desired > 1 ? 'marcados' : 'marcado' }}
                                        </span>
                                    </div>
                                    <div class="h-1 bg-[#f0ede8] rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all duration-500"
                                             style="width: {{ min(100, $item->quantity_desired > 0 ? ($item->quantity_received / $item->quantity_desired) * 100 : 0) }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3 flex flex-col gap-1.5">
                                @if(! $full)
                                    <button
                                        wire:click="openPledge({{ $item->id }})"
                                        class="w-full text-center text-xs font-semibold py-2 rounded-lg bg-primary text-white hover:bg-primary-dark transition-colors duration-200">
                                        Vou presentear
                                    </button>
                                @endif
                                @if($item->url)
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                                       class="block w-full text-center text-xs font-medium py-1.5 rounded-lg bg-[#f5f5f4] text-[#78716c] hover:bg-[#ece9e3] transition-colors duration-200">
                                        Ver produto →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <p class="text-[#a8a29e] text-sm">A lista de presentes ainda está sendo preparada.</p>
                    </div>
                @endforelse
            </div>
        </section>
    @endif

    {{-- ── Pledge Modal ──────────────────────────────────────────────── --}}
    @if($pledgingItemId !== null)
        @php $pledgeItem = $items->firstWhere('id', $pledgingItemId); @endphp
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
             x-data x-init="document.body.style.overflow='hidden'"
             x-destroy="document.body.style.overflow=''">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                 wire:click="closePledge"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-primary to-primary-dark px-6 py-5 text-white">
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
                            <h4 class="font-semibold text-[#1c1917] text-lg">Que gentileza!</h4>
                            <p class="text-sm text-[#78716c] mt-2 leading-relaxed">
                                Enviamos uma confirmação para <strong>{{ $pledgerEmail }}</strong> com um link para cancelar caso mude de ideia.
                            </p>
                            <button wire:click="closePledge"
                                    class="mt-5 w-full py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition">
                                Fechar
                            </button>
                        </div>
                    @else
                        @if($pledgeError)
                            <p class="mb-4 text-sm text-red-600 bg-red-50 rounded-xl px-4 py-2.5">{{ $pledgeError }}</p>
                        @endif

                        <form wire:submit="submitPledge" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#78716c] mb-1.5">Seu nome *</label>
                                <input type="text" wire:model="pledgerName"
                                       placeholder="Como você quer ser identificado"
                                       class="w-full text-sm border border-[#e5e0d9] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition
                                              @error('pledgerName') border-red-400 @enderror">
                                @error('pledgerName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#78716c] mb-1.5">E-mail *</label>
                                <input type="email" wire:model="pledgerEmail"
                                       placeholder="Para enviar a confirmação"
                                       class="w-full text-sm border border-[#e5e0d9] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition
                                              @error('pledgerEmail') border-red-400 @enderror">
                                @error('pledgerEmail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#78716c] mb-1.5">Telefone / WhatsApp <span class="font-normal text-[#a8a29e]">(opcional)</span></label>
                                <input type="tel" wire:model="pledgerPhone"
                                       placeholder="(00) 00000-0000"
                                       class="w-full text-sm border border-[#e5e0d9] rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                            </div>

                            <p class="text-[10px] text-[#a8a29e] leading-relaxed">
                                Você receberá um e-mail de confirmação com um link para cancelar caso mude de ideia.
                            </p>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submitPledge"
                                    class="w-full py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition disabled:opacity-60">
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
