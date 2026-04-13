<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-text">Meu Painel</h1>
                    <p class="mt-1 text-gray-500">Olá, {{ auth()->user()->name }}! Gerencie seus eventos.</p>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-3 rounded-btn transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Novo Evento
                        <svg class="w-3.5 h-3.5 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-30"
                        style="display: none;">
                        <a href="/planejar"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-text hover:bg-bg transition rounded-t-xl">
                            <span class="text-xl">✨</span>
                            <div>
                                <p class="font-medium">Criar com a Celi</p>
                                <p class="text-xs text-gray-400 mt-0.5">Assistente de IA</p>
                            </div>
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <a href="/meus-eventos/criar"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-text hover:bg-bg transition rounded-b-xl">
                            <span class="text-xl">✏️</span>
                            <div>
                                <p class="font-medium">Criar eu mesmo</p>
                                <p class="text-xs text-gray-400 mt-0.5">Preencher formulário</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-white rounded-card p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                            <span class="text-xl">🎉</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-text">{{ $events->count() }}</p>
                            <p class="text-sm text-gray-500">Meus eventos</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-card p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center">
                            <span class="text-xl">📋</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-text">{{ $events->where('event_date', '>=', now())->count() }}</p>
                            <p class="text-sm text-gray-500">Próximos eventos</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-card p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                            <span class="text-xl">✅</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-text">{{ $events->where('event_date', '<', now())->count() }}</p>
                            <p class="text-sm text-gray-500">Realizados</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA banner: first page --}}
            @if($events->isNotEmpty() && $events->every(fn($e) => !$e->eventPage))
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 border border-primary/20 rounded-card p-5 mb-8 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm shrink-0 text-2xl">🎨</div>
                    <div class="flex-1">
                        <p class="font-semibold text-text">Crie a landing page do seu evento</p>
                        <p class="text-sm text-gray-500 mt-0.5">Monte uma página personalizada com lista de presentes, confirmação de presença e muito mais — em minutos.</p>
                    </div>
                    <a href="/meus-eventos/{{ $events->first()->slug }}/pagina"
                       class="shrink-0 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-pill transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Criar minha página
                    </a>
                </div>
            @endif

            {{-- Events List --}}
            <div class="bg-white rounded-card shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-text">Meus Eventos</h2>
                </div>
                @forelse($events as $event)
                    @php $page = $event->eventPage; @endphp
                    <div class="p-5 border-b border-gray-50 hover:bg-bg/30 transition">
                        <div class="flex items-start justify-between gap-4">
                            {{-- Event info --}}
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center shrink-0">
                                    <span>{{ $event->eventType->icon ?? '🎉' }}</span>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-text truncate">{{ $event->title }}</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $event->eventType->name ?? 'Evento' }}
                                        @if($event->event_date) · {{ $event->event_date->format('d/m/Y') }} @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
                                {{-- Edit event button --}}
                                <a href="/meus-eventos/{{ $event->slug }}/editar"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full transition bg-gray-100 text-gray-600 hover:bg-gray-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar evento
                                </a>
                                {{-- Page status badge --}}
                                @if($page)
                                    @if($page->published)
                                        @if(auth()->user()->slug)
                                            <a href="/{{ auth()->user()->slug }}/{{ $event->slug }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 px-2.5 py-1.5 rounded-full transition">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                                Publicada
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 px-2.5 py-1.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                                Publicada
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 inline-block"></span>
                                            Rascunho
                                        </span>
                                    @endif
                                @endif

                                {{-- Gift registry button --}}
                                @if($event->gift_registry_enabled)
                                    <a href="/meus-eventos/{{ $event->slug }}/presentes"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full transition bg-secondary/10 text-secondary hover:bg-secondary/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zm0 0V5a2 2 0 00-2-2H6a2 2 0 00-2 2v2"/>
                                        </svg>
                                        Lista de presentes
                                    </a>
                                @endif

                                {{-- Builder button --}}
                                <a href="/meus-eventos/{{ $event->slug }}/pagina"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full transition
                                        {{ $page ? 'bg-primary text-white hover:bg-primary-dark' : 'bg-primary/10 text-primary hover:bg-primary/20' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ $page ? 'Editar página' : 'Criar página' }}
                                </a>

                                {{-- Delete button --}}
                                <button
                                    x-on:click="SwalTheme.dangerDialog({ title: 'Remover evento?', text: 'O evento e sua landing page serão removidos permanentemente.', confirmButtonText: 'Remover' }).then(r => r.isConfirmed && $wire.deleteEvent({{ $event->id }}))"
                                    class="p-1.5 text-gray-300 hover:text-red-400 hover:bg-red-50 rounded-full transition"
                                    title="Remover evento">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-bg rounded-full flex items-center justify-center">
                            <span class="text-3xl">🎊</span>
                        </div>
                        <h3 class="text-lg font-medium text-text mb-2">Nenhum evento ainda</h3>
                        <p class="text-sm text-gray-500 mb-4">Comece planejando o seu primeiro evento com a ajuda da Celi!</p>
                        <a href="/planejar" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-3 rounded-btn transition">
                            Planejar evento
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
