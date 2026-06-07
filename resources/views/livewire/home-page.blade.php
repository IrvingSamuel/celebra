<div data-home-page>
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-pink-50 via-white to-indigo-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 data-hero-title class="text-4xl lg:text-5xl font-bold text-text leading-tight">
                        Planeje o <span class="text-primary">evento</span> dos seus sonhos
                    </h1>
                    <p data-hero-subtitle class="mt-4 text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                        Casamentos, formaturas, aniversários e muito mais. Encontre os melhores espaços, fornecedores e serviços em um só lugar.
                    </p>

                    {{-- Search Bar --}}
                    <div data-hero-search class="mt-8 flex items-center bg-white dark:bg-gray-800 rounded-full shadow-lg p-2 max-w-lg">
                        <div class="flex-1 flex items-center gap-2 px-4">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Buscar espaços, serviços..."
                                class="w-full py-2 text-sm text-text placeholder-gray-400 dark:placeholder-gray-500 border-none focus:outline-none focus:ring-0 bg-transparent"
                            >
                        </div>
                        <a href="/espacos{{ $search ? '?search=' . urlencode($search) : '' }}"
                           class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-3 rounded-full transition">
                            Buscar
                        </a>
                    </div>

                    {{-- Quick stats --}}
                    <div class="mt-8 flex gap-8">
                        <div data-hero-stat>
                            <span class="text-2xl font-bold text-text">500+</span>
                            <p class="text-xs text-gray-500">Fornecedores</p>
                        </div>
                        <div data-hero-stat>
                            <span class="text-2xl font-bold text-text">1.200+</span>
                            <p class="text-xs text-gray-500">Eventos realizados</p>
                        </div>
                        <div data-hero-stat>
                            <span class="text-2xl font-bold text-text">98%</span>
                            <p class="text-xs text-gray-500">Satisfação</p>
                        </div>
                    </div>
                </div>

                {{-- Hero Carousel --}}
                <div data-hero-carousel class="relative" x-data="{
                    current: 0,
                    images: {{ Js::from($carouselImages) }},
                    init() {
                        setInterval(() => { this.current = (this.current + 1) % this.images.length }, 4000);
                    }
                }">
                    <div class="aspect-[16/9] lg:aspect-[4/3] rounded-card overflow-hidden relative shadow-2xl">
                        <template x-for="(img, index) in images" :key="index">
                            <img :src="img"
                                 alt="Espaço para eventos"
                                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                                 :class="current === index ? 'opacity-100' : 'opacity-0'">
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        {{-- Dots --}}
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                            <template x-for="(img, index) in images" :key="'dot-'+index">
                                <button @click="current = index"
                                        class="w-2 h-2 rounded-full transition-all duration-300"
                                        :class="current === index ? 'bg-white w-6' : 'bg-white/50'"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Event Types --}}
    @php
        $eventTypeImages = [
            'Casamento' => 'images/venues/villa-toscana.jpg',
            '15 Anos' => 'images/venues/palacio-cristal.jpg',
            'Aniversário' => 'images/venues/espaco-luz.jpg',
            'Formatura' => 'images/venues/palacio-das-artes.jpg',
            'Confraternização' => 'images/venues/arena-beach-club.jpg',
            'Conferência' => 'images/venues/loft-industrial-42.jpg',
        ];
    @endphp
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-section-header class="text-center mb-12">
                <h2 class="text-3xl font-bold text-text">Que tipo de evento você quer planejar?</h2>
                <p class="mt-2 text-gray-500">Escolha o tipo e encontre tudo que precisa</p>
            </div>

            <div data-event-grid class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
                @forelse($eventTypes as $eventType)
                    <a href="/espacos" data-event-card class="group relative flex flex-col items-center justify-end p-4 rounded-card overflow-hidden h-40 hover:shadow-lg transition-shadow duration-300">
                        <img src="/{{ $eventTypeImages[$eventType->name] ?? 'images/venues/espaco-gardens.jpg' }}" alt="{{ $eventType->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                        <span class="relative text-sm font-semibold text-white text-center drop-shadow-lg">{{ $eventType->name }}</span>
                    </a>
                @empty
                    @foreach([
                        ['name' => 'Casamento'],
                        ['name' => '15 Anos'],
                        ['name' => 'Aniversário'],
                        ['name' => 'Formatura'],
                        ['name' => 'Confraternização'],
                        ['name' => 'Conferência'],
                    ] as $cat)
                        <div data-event-card class="group relative flex flex-col items-center justify-end p-4 rounded-card overflow-hidden h-40 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                            <img src="/{{ $eventTypeImages[$cat['name']] ?? 'images/venues/espaco-gardens.jpg' }}" alt="{{ $cat['name'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                            <span class="relative text-sm font-semibold text-white text-center drop-shadow-lg">{{ $cat['name'] }}</span>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-16 bg-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-section-header class="text-center mb-12">
                <h2 class="text-3xl font-bold text-text">Como funciona</h2>
                <p class="mt-2 text-gray-500">Três passos simples para o evento perfeito</p>
            </div>

            <div data-step-grid class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-step-card class="text-center p-8 bg-white dark:bg-gray-800 rounded-card shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden shadow-md">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('minio')->url('venues/jardim-imperial.jpg') }}" alt="Escolha" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-lg font-semibold text-text mb-2">1. Escolha</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selecione o tipo de evento e explore espaços e serviços disponíveis.</p>
                </div>
                <div data-step-card class="text-center p-8 bg-white dark:bg-gray-800 rounded-card shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden shadow-md">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('minio')->url('services/luxe-design-eventos.jpg') }}" alt="Planeje" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-lg font-semibold text-text mb-2">2. Planeje</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Use a Celi, nossa assistente IA, para receber recomendações personalizadas.</p>
                </div>
                <div data-step-card class="text-center p-8 bg-white dark:bg-gray-800 rounded-card shadow-sm">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden shadow-md">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('minio')->url('venues/mansao-tropical.jpg') }}" alt="Celebre" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-lg font-semibold text-text mb-2">3. Celebre</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Contrate os fornecedores, crie sua lista de presentes e aproveite!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Service Categories --}}
    @php
        $categoryImages = [
            'Fotografia' => 'images/services/clique-perfeito-fotografia.jpg',
            'Buffet' => 'images/services/buffet-sabor-arte.jpg',
            'Decoração' => 'images/services/flora-bella-decoracao.jpg',
            'Música' => 'images/services/banda-celebracao.jpg',
            'Vestidos' => 'images/services/atelier-noiva-perfeita.jpg',
        ];
    @endphp
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-section-header class="text-center mb-12">
                <h2 class="text-3xl font-bold text-text">Nossos Serviços</h2>
                <p class="mt-2 text-gray-500">Tudo que você precisa em um só lugar</p>
            </div>
            <style>
                .lg\:grid-cols-5{
                    grid-template-columns: repeat(5, minmax(0, 1fr));
                }
            </style>

            <div data-service-grid class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 flex justify-center items-center">
                @forelse($categories as $category)
                    <a href="/servicos/{{ $category->slug }}" data-service-card class="group relative flex flex-col items-center justify-end p-4 rounded-card overflow-hidden h-40 hover:shadow-lg transition-shadow duration-300">
                        <img src="/{{ $categoryImages[$category->name] ?? 'images/services/luxe-design-eventos.jpg' }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent group-hover:from-primary/80 group-hover:via-primary/40 group-hover:to-transparent transition-all duration-300"></div>
                        <span class="relative text-sm font-semibold text-white text-center drop-shadow-lg">{{ $category->name }}</span>
                    </a>
                @empty
                    @foreach([
                        ['name' => 'Fotografia'],
                        ['name' => 'Buffet'],
                        ['name' => 'Decoração'],
                        ['name' => 'Música'],
                        ['name' => 'Vestidos'],
                    ] as $cat)
                        <div data-service-card class="group relative flex flex-col items-center justify-end p-4 rounded-card overflow-hidden h-40 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                            <img src="/{{ $categoryImages[$cat['name']] ?? 'images/services/luxe-design-eventos.jpg' }}" alt="{{ $cat['name'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent group-hover:from-primary/80 group-hover:via-primary/40 group-hover:to-transparent transition-all duration-300"></div>
                            <span class="relative text-sm font-semibold text-white text-center drop-shadow-lg">{{ $cat['name'] }}</span>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- Featured Venues --}}
    <section class="py-16 bg-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-section-header class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-text">Espaços em Destaque</h2>
                    <p class="mt-2 text-gray-500">Os espaços mais bem avaliados</p>
                </div>
                <a href="/espacos" class="text-primary font-semibold text-sm hover:text-primary-dark transition flex items-center gap-1">
                    Ver todos
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div data-venue-grid class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredVenues as $venue)
                    <a href="/espacos/{{ $venue->slug }}" data-venue-card class="bg-white dark:bg-gray-800 rounded-card overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 group block">
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
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm rounded-full px-2.5 py-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xs font-semibold text-text">{{ number_format($venue->rating, 1) }}</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-text text-lg leading-snug">{{ $venue->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $venue->city }}, {{ $venue->state }}
                            </p>
                            <div class="flex items-center justify-between mt-3">
                                <div>
                                    <p class="text-xs text-gray-400">a partir de</p>
                                    <span class="text-primary font-bold">R$ {{ number_format($venue->price, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-xs text-gray-400 bg-bg px-2 py-1 rounded-full capitalize">{{ $venue->type }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    @for($i = 0; $i < 4; $i++)
                        <div class="bg-white dark:bg-gray-800 rounded-card overflow-hidden shadow-sm">
                            <div class="aspect-[4/3] bg-gradient-to-br from-gray-100 dark:from-gray-700 to-gray-50 dark:to-gray-800 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-200 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="p-4">
                                <div class="h-5 bg-gray-100 dark:bg-gray-700 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-100 dark:bg-gray-700 rounded w-1/2 mt-2"></div>
                                <div class="h-5 bg-gray-100 dark:bg-gray-700 rounded w-1/3 mt-3"></div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-primary to-secondary">
        <div data-cta-content class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white">Pronto para planejar o seu evento?</h2>
            <p class="mt-4 text-white/80 text-lg">Converse com a Celi, nossa assistente IA, e receba recomendações personalizadas.</p>
            <a href="/planejar" class="mt-8 inline-flex items-center gap-2 bg-white text-primary font-semibold px-8 py-4 rounded-full hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path transform="translate(3, 0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
                Planejar com a Celi
            </a>
        </div>
    </section>
</div>
