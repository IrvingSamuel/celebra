<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-text">Espaços para Eventos</h1>
                <p class="mt-2 text-gray-500">Encontre o espaço perfeito para o seu evento</p>
            </div>

            {{-- Filters Bar --}}
            <div class="bg-white dark:bg-gray-800 rounded-card p-4 shadow-sm mb-8 flex flex-col sm:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1 flex items-center gap-2 bg-bg rounded-full px-4 py-2">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Buscar por nome, cidade..."
                        class="w-full bg-transparent text-sm text-text placeholder-gray-400 border-none focus:outline-none focus:ring-0"
                    >
                </div>

                {{-- Type Filter --}}
                <div class="flex gap-2">
                    <button wire:click="$set('type', '')"
                            class="px-4 py-2 rounded-full text-sm font-medium transition {{ !$type ? 'bg-primary text-white' : 'bg-bg text-text hover:bg-gray-200' }}">
                        Todos
                    </button>
                    <button wire:click="$set('type', 'indoor')"
                            class="px-4 py-2 rounded-full text-sm font-medium transition {{ $type === 'indoor' ? 'bg-primary text-white' : 'bg-bg text-text hover:bg-gray-200' }}">
                        Indoor
                    </button>
                    <button wire:click="$set('type', 'outdoor')"
                            class="px-4 py-2 rounded-full text-sm font-medium transition {{ $type === 'outdoor' ? 'bg-primary text-white' : 'bg-bg text-text hover:bg-gray-200' }}">
                        Outdoor
                    </button>
                    <button wire:click="$set('type', 'ambos')"
                            class="px-4 py-2 rounded-full text-sm font-medium transition {{ $type === 'ambos' ? 'bg-primary text-white' : 'bg-bg text-text hover:bg-gray-200' }}">
                        Ambos
                    </button>
                </div>

                {{-- City Filter --}}
                @if($cities->count())
                    <select wire:model.live="city" class="bg-bg dark:bg-gray-700 rounded-full px-4 py-2 text-sm text-text border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Todas as cidades</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Venues Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.class="opacity-50">
                @forelse($venues as $venue)
                    <div class="bg-white dark:bg-gray-800 rounded-card overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 group">
                        <div class="aspect-[4/3] bg-gradient-to-br from-primary/10 to-primary/5 overflow-hidden relative">
                            @if($venue->image_url)
                                <img src="{{ $venue->image_url }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-primary/20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Rating Badge --}}
                            <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full px-2.5 py-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xs font-semibold text-text">{{ number_format($venue->rating, 1) }}</span>
                            </div>

                            {{-- Capacity Badge --}}
                            @if($venue->capacity)
                                <div class="absolute bottom-3 left-3 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full px-2.5 py-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-xs font-medium text-gray-600">{{ $venue->capacity }} pessoas</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3 class="font-semibold text-text text-lg leading-snug">{{ $venue->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $venue->city }}, {{ $venue->state }}
                            </p>

                            @if($venue->description)
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2 line-clamp-2">{{ $venue->description }}</p>
                            @endif

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50 dark:border-gray-700">
                                <div>
                                    <p class="text-xs text-gray-400">a partir de</p>
                                    <span class="text-primary font-bold text-lg">R$ {{ number_format($venue->price, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-xs text-gray-400 bg-bg px-3 py-1 rounded-full capitalize">{{ $venue->type }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <h3 class="text-lg font-semibold text-text">Nenhum espaço encontrado</h3>
                        <p class="text-gray-500 mt-1">Tente ajustar os filtros de busca.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $venues->links() }}
            </div>
        </div>
    </section>
</div>
