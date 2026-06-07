<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="/dashboard"
                   class="p-2 text-gray-400 hover:text-text hover:bg-white dark:hover:bg-gray-700 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-text truncate">Lista de Presentes</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $event->title }}</p>
                </div>
                <a href="/presentes/{{ $event->slug }}" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Ver lista pública
                </a>
            </div>

            {{-- URL Fetch Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm p-6 mb-6">
                <h2 class="text-base font-semibold text-text mb-1">Adicionar presente</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Cole o link do produto e preencheremos os dados automaticamente.</p>

                <div class="flex gap-2">
                    <input
                        type="url"
                        wire:model="productUrl"
                        placeholder="https://www.amazon.com.br/..."
                        class="flex-1 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                        wire:keydown.enter="fetchFromUrl"
                        @keydown.enter.prevent="$wire.fetchFromUrl()"
                    />
                    <button
                        wire:click="fetchFromUrl"
                        wire:loading.attr="disabled"
                        wire:target="fetchFromUrl"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="fetchFromUrl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <span wire:loading wire:target="fetchFromUrl">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="fetchFromUrl">Buscar dados</span>
                        <span wire:loading wire:target="fetchFromUrl">Buscando...</span>
                    </button>
                </div>

                @error('productUrl')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror

                @if($fetchError)
                    <p class="mt-2 text-xs text-red-500">{{ $fetchErrorMessage }}</p>
                @endif
            </div>

            {{-- Item Form --}}
            @if($showForm)
                <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm p-6 mb-6 border-l-4 border-primary">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-semibold text-text">
                            {{ $editingItemId ? 'Editar presente' : 'Novo presente' }}
                        </h3>
                            <button wire:click="cancelForm" class="p-1.5 text-gray-400 hover:text-text rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                        {{-- Image preview --}}
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Imagem</label>
                            <div class="aspect-square rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                @if($image)
                                    <img src="{{ $image }}" alt="Preview" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl"></span>
                                @endif
                            </div>
                            <input
                                type="url"
                                wire:model.live="image"
                                placeholder="URL da imagem"
                                class="mt-2 w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                            />
                            @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fields --}}
                        <div class="sm:col-span-2 flex flex-col gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Nome do produto <span class="text-red-400">*</span></label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    placeholder="Ex: Jogo de panelas antiaderente"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                />
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Preço (R$)</label>
                                    <input
                                        type="number"
                                        wire:model="price"
                                        placeholder="0,00"
                                        min="0"
                                        step="0.01"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                    />
                                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Quantidade desejada</label>
                                    <input
                                        type="number"
                                        wire:model="quantityDesired"
                                        min="1"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                    />
                                    @error('quantityDesired') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Loja / Plataforma</label>
                                    <input
                                        type="text"
                                        wire:model="platform"
                                        placeholder="Ex: Amazon, Shopee…"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                    />
                                    @error('platform') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Link do produto</label>
                                    <input
                                        type="url"
                                        wire:model="url"
                                        placeholder="https://..."
                                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                                    />
                                    @error('url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Descrição</label>
                                <textarea
                                    wire:model="description"
                                    rows="3"
                                    placeholder="Descreva o produto brevemente…"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none"
                                ></textarea>
                                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex justify-end gap-2 pt-1">
                                <button wire:click="cancelForm"
                                    class="px-4 py-2 text-sm text-gray-500 hover:text-text hover:bg-gray-100 rounded-lg transition">
                                    Cancelar
                                </button>
                                <button wire:click="saveItem"
                                    wire:loading.attr="disabled"
                                    wire:target="saveItem"
                                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2 rounded-lg transition disabled:opacity-60">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $editingItemId ? 'Salvar alterações' : 'Adicionar à lista' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Items list --}}
            <div class="bg-white rounded-card shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-text">
                        Presentes cadastrados
                        <span class="ml-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-full px-2 py-0.5">{{ $items->count() }}</span>
                    </h2>
                </div>

                @forelse($items as $item)
                    <div class="border-b border-gray-50 last:border-0" x-data="{ showPledges: false }">

                        {{-- Item row --}}
                        <div class="flex items-center gap-4 p-4 hover:bg-bg/30 transition">

                            {{-- Thumbnail --}}
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-50 border border-gray-100 shrink-0">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl"></div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-medium text-sm text-text truncate">{{ $item->name }}</h3>
                                    @if($item->platform)
                                        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 shrink-0">
                                            {{ $item->platform }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    @if($item->price)
                                        <span class="text-sm font-semibold text-primary">
                                            R$ {{ number_format($item->price, 2, ',', '.') }}
                                        </span>
                                    @endif
                                    @if($item->quantity_desired > 1)
                                        <span class="text-xs text-gray-400">{{ $item->quantity_desired }}x desejados</span>
                                    @endif
                                    @if($item->url)
                                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                                           class="text-xs text-primary hover:underline inline-flex items-center gap-0.5">
                                            Ver produto
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Pledges count badge --}}
                                    @if($item->activePledges->count() > 0)
                                        <button @click="showPledges = !showPledges"
                                                class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-primary/10 text-primary hover:bg-primary/20 transition">
                                            {{ $item->activePledges->count() }} presenteador{{ $item->activePledges->count() > 1 ? 'es' : '' }}
                                            <svg class="w-3 h-3 transition-transform" :class="showPledges ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-300">Nenhum presenteador ainda</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1 shrink-0">
                                <button wire:click="editItem({{ $item->id }})"
                                    class="p-1.5 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-full transition"
                                    title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button
                                    x-on:click="SwalTheme.dangerDialog({ title: 'Remover presente?', text: 'O item será removido da lista permanentemente.', confirmButtonText: 'Remover' }).then(r => r.isConfirmed && $wire.deleteItem({{ $item->id }}))"
                                    class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-red-50 rounded-full transition"
                                    title="Remover">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Pledges expandable panel --}}
                        @if($item->activePledges->count() > 0)
                            <div x-show="showPledges" x-collapse
                                 class="bg-primary/5 border-t border-primary/10 px-4 py-3">
                                <p class="text-xs font-semibold text-primary mb-2 uppercase tracking-wide">Quem vai presentear</p>
                                <div class="space-y-2">
                                    @foreach($item->activePledges as $pledge)
                                        <div class="flex items-center gap-3 bg-white rounded-lg px-3 py-2 shadow-sm">
                                            <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                                <span class="text-xs font-bold text-primary">{{ mb_strtoupper(mb_substr($pledge->giver_name, 0, 1)) }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-text truncate">{{ $pledge->giver_name }}</p>
                                                <p class="text-xs text-gray-400 truncate">{{ $pledge->giver_email }}{{ $pledge->giver_phone ? ' · ' . $pledge->giver_phone : '' }}</p>
                                            </div>
                                            <p class="text-xs text-gray-300 shrink-0">{{ $pledge->created_at->format('d/m') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <span class="text-5xl block mb-3">✦</span>
                        <p class="text-gray-500 text-sm">Nenhum presente cadastrado ainda.</p>
                        <p class="text-gray-400 text-xs mt-1">Cole o link de um produto acima para começar.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
</div>
