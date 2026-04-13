<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-sm">
                <a href="/" class="text-gray-500 hover:text-primary transition">Início</a>
                <span class="text-gray-300 mx-2">/</span>
                <a href="/espacos" class="text-gray-500 hover:text-primary transition">Espaços</a>
                <span class="text-gray-300 mx-2">/</span>
                <span class="text-text font-medium">{{ $venue->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    {{-- Image --}}
                    <div class="aspect-[16/9] bg-gradient-to-br from-primary/10 to-primary/5 rounded-card overflow-hidden mb-6">
                        @if($venue->image_url)
                            <img src="{{ $venue->image_url }}" alt="{{ $venue->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-24 h-24 text-primary/20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Gallery --}}
                    @if(count($venue->gallery_urls) > 0)
                        <div class="grid grid-cols-4 gap-2 mb-8">
                            @foreach(array_slice($venue->gallery_urls, 0, 4) as $imageUrl)
                                <div class="aspect-square rounded-btn overflow-hidden bg-gray-100">
                                    <img src="{{ $imageUrl }}" alt="Galeria" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="bg-white rounded-card p-6 shadow-sm mb-6">
                        <h1 class="text-2xl font-bold text-text mb-2">{{ $venue->name }}</h1>
                        <p class="text-gray-500 flex items-center gap-1 mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $venue->address }} — {{ $venue->city }}, {{ $venue->state }}
                        </p>
                        <p class="text-text leading-relaxed">{{ $venue->description }}</p>
                    </div>

                    {{-- Features --}}
                    <div class="bg-white rounded-card p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-text mb-4">Informações</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3 p-3 bg-bg rounded-btn">
                                <span class="text-xl">👥</span>
                                <div>
                                    <p class="text-sm font-medium text-text">{{ $venue->capacity }} pessoas</p>
                                    <p class="text-xs text-gray-500">Capacidade</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-bg rounded-btn">
                                <span class="text-xl">📍</span>
                                <div>
                                    <p class="text-sm font-medium text-text capitalize">{{ $venue->type }}</p>
                                    <p class="text-xs text-gray-500">Tipo</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-bg rounded-btn">
                                <span class="text-xl">⭐</span>
                                <div>
                                    <p class="text-sm font-medium text-text">{{ number_format($venue->rating, 1) }}</p>
                                    <p class="text-xs text-gray-500">Avaliação</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div>
                    <div class="bg-white rounded-card p-6 shadow-sm sticky top-24">
                        <div class="text-center mb-6">
                            <p class="text-xs text-gray-400 mb-0.5">a partir de</p>
                            <p class="text-3xl font-bold text-primary">R$ {{ number_format($venue->price, 0, ',', '.') }}</p>
                        </div>

                        <div class="space-y-3">
                            <a href="/planejar" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-btn transition text-center block">
                                Solicitar orçamento
                            </a>
                            <a href="/planejar" class="w-full bg-secondary/10 text-secondary hover:bg-secondary/20 font-semibold py-3 rounded-btn transition text-center block">
                                Planejar com a Celi
                            </a>
                        </div>

                        <div class="border-t border-gray-100 mt-6 pt-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Resposta rápida
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 mt-2">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Pagamento seguro
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
