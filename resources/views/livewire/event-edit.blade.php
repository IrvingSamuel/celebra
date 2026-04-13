<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="/dashboard" class="p-2 text-gray-400 hover:text-text rounded-lg hover:bg-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="flex-1 flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-text">
                            {{ $eventId ? 'Editar Evento' : 'Novo Evento' }}
                        </h1>
                        @if($eventId && $title)
                            <p class="text-sm text-gray-500 mt-0.5">{{ $title }}</p>
                        @endif
                    </div>

                    {{-- Page buttons --}}
                    @if($eventId && $eventSlug)
                        <div class="flex items-center gap-2 shrink-0">
                            @if($eventPage && $eventPage->published && $userSlug)
                                <a href="/{{ $userSlug }}/{{ $eventSlug }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-4 py-2 rounded-xl transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Ver página
                                </a>
                            @endif
                            <a href="/meus-eventos/{{ $eventSlug }}/pagina"
                                class="inline-flex items-center gap-1.5 text-sm font-medium text-white bg-secondary hover:bg-secondary-dark px-4 py-2 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ $eventPage ? 'Editar página' : 'Criar página' }}
                            </a>
                        </div>
                    @endif

                    {{-- Draft status indicator --}}
                    @if($eventId)
                        <div class="shrink-0">
                            <span wire:loading wire:target="autoSave"
                                class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Salvando...
                            </span>
                            @if($draftSaved)
                                <span wire:loading.remove wire:target="autoSave"
                                    class="inline-flex items-center gap-1.5 text-xs text-green-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Rascunho salvo às {{ $draftSavedAt }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-card px-4 py-3 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-card px-4 py-3 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tabs --}}
            <div class="bg-white rounded-card shadow-sm overflow-hidden">

                {{-- Page action bar (only when editing an existing event) --}}
                @if($eventId && $eventSlug)
                    <div class="flex items-center justify-between gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <p class="text-xs text-gray-400">
                            Edite as informações e serviços do evento
                        </p>
                        <div class="flex items-center gap-2">
                            @if($eventPage && $eventPage->published && $userSlug)
                                <a href="/{{ $userSlug }}/{{ $eventSlug }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-full transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Ver página publicada
                                </a>
                            @endif
                            <a href="/meus-eventos/{{ $eventSlug }}/pagina"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-secondary hover:bg-secondary-dark px-3 py-1.5 rounded-full transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ $eventPage ? 'Editar página' : 'Criar página' }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Tab bar --}}
                <div class="border-b border-gray-100 overflow-x-auto">
                    <div class="flex min-w-max">
                        {{-- Info tab --}}
                        <button wire:click="$set('activeTab', 'info')"
                            class="flex items-center gap-2 px-5 py-4 text-sm font-medium border-b-2 transition whitespace-nowrap
                                {{ $activeTab === 'info'
                                    ? 'border-primary text-primary'
                                    : 'border-transparent text-gray-500 hover:text-text hover:border-gray-200' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Informações
                        </button>

                        {{-- Service category tabs --}}
                        @foreach($categories as $cat)
                            @php
                                // Count attached items in this category
                                $attachedInCategory = $cat->slug === $venueCategorySlug
                                    ? count($attachedVenueIds)
                                    : \App\Models\Service::whereIn('id', $attachedServiceIds)->where('service_category_id', $cat->id)->count();
                            @endphp
                            <button wire:click="$set('activeTab', 'cat-{{ $cat->id }}')"
                                class="flex items-center gap-2 px-5 py-4 text-sm font-medium border-b-2 transition whitespace-nowrap
                                    {{ $activeTab === 'cat-' . $cat->id
                                        ? 'border-primary text-primary'
                                        : 'border-transparent text-gray-500 hover:text-text hover:border-gray-200' }}">
                                <span>{{ $cat->icon ?? '' }}</span>
                                {{ $cat->name }}
                                @if($attachedInCategory > 0)
                                    <span class="bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">
                                        {{ $attachedInCategory }}
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ===== SHARED FILTERS (visible on service/venue tabs) ===== --}}
                @if($activeTab !== 'info')
                    <div class="flex flex-wrap items-center gap-2 px-5 py-3 bg-gray-50 border-b border-gray-100">
                        {{-- Location --}}
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <input wire:model.live.debounce.300ms="filterLocation"
                                type="text" placeholder="Cidade ou estado"
                                class="pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition w-44 bg-white">
                        </div>

                        {{-- Min price --}}
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">R$</span>
                            <input wire:model.live.debounce.400ms="filterMinPrice"
                                type="number" min="0" step="100" placeholder="Mín."
                                class="pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition w-28 bg-white">
                        </div>

                        {{-- Max price --}}
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">R$</span>
                            <input wire:model.live.debounce.400ms="filterMaxPrice"
                                type="number" min="0" step="100" placeholder="Máx."
                                class="pl-8 pr-3 py-2 border border-gray-200 rounded-xl text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition w-28 bg-white">
                        </div>

                        {{-- Clear shared filters --}}
                        @if($filterLocation !== '' || $filterMinPrice !== '' || $filterMaxPrice !== '')
                            <button wire:click="$set('filterLocation', ''); $set('filterMinPrice', ''); $set('filterMaxPrice', '')"
                                class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-gray-500 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpar filtros
                            </button>
                        @endif
                    </div>
                @endif

                {{-- ===== INFO TAB ===== --}}
                @if($activeTab === 'info')
                    <form wire:submit="save" class="p-6 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            {{-- Title --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-text mb-1.5">Nome do evento <span class="text-red-400">*</span></label>
                                <input wire:model.live.debounce.800ms="title" type="text" placeholder="Ex: Casamento de Ana e Bruno"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('title') border-red-300 @enderror">
                                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Event type --}}
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Tipo de evento <span class="text-red-400">*</span></label>
                                <select wire:model.live="eventTypeId"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('eventTypeId') border-red-300 @enderror">
                                    <option value="">Selecione o tipo...</option>
                                    @foreach($eventTypes as $et)
                                        <option value="{{ $et->id }}">{{ $et->name }}</option>
                                    @endforeach
                                </select>
                                @error('eventTypeId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Event date --}}
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Data do evento</label>
                                <input wire:model.live="eventDate" type="date"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('eventDate') border-red-300 @enderror">
                                @error('eventDate') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Guest count --}}
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Número de convidados</label>
                                <input wire:model.live.debounce.600ms="guestCount" type="number" min="1" placeholder="Ex: 150"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('guestCount') border-red-300 @enderror">
                                @error('guestCount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Budget --}}
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Orçamento estimado (R$)</label>
                                <input wire:model.live.debounce.600ms="budget" type="number" min="0" step="0.01" placeholder="Ex: 50000"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition @error('budget') border-red-300 @enderror">
                                @error('budget') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Description --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-text mb-1.5">Descrição</label>
                                <textarea wire:model.live.debounce.1000ms="description" rows="3" placeholder="Descreva seu evento..."
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none @error('description') border-red-300 @enderror"></textarea>
                                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Message --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-text mb-1.5">Mensagem para convidados</label>
                                <textarea wire:model.live.debounce.1000ms="message" rows="2" placeholder="Uma mensagem especial para aparecer na landing page..."
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none @error('message') border-red-300 @enderror"></textarea>
                                @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Toggles --}}
                            <div class="sm:col-span-2 flex flex-col sm:flex-row gap-4">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <button type="button" wire:click="$toggle('public')" role="switch"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $public ? 'bg-primary' : 'bg-gray-300' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition {{ $public ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div>
                                        <p class="text-sm font-medium text-text">Evento público</p>
                                        <p class="text-xs text-gray-400">Aparece nos resultados de busca</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <button type="button" wire:click="$toggle('giftRegistryEnabled')" role="switch"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $giftRegistryEnabled ? 'bg-primary' : 'bg-gray-300' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition {{ $giftRegistryEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div>
                                        <p class="text-sm font-medium text-text">Lista de presentes</p>
                                        <p class="text-xs text-gray-400">Habilita lista de presentes no evento</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-400">
                                @if(!$eventId)
                                    Preencha título e tipo para criar o evento
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="/dashboard" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-text transition">
                                    Cancelar
                                </a>
                                <button type="submit"
                                    wire:loading.attr="disabled" wire:target="save"
                                    class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-btn transition inline-flex items-center gap-2 disabled:opacity-60">
                                    <span wire:loading.remove wire:target="save">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if(!$eventId)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            @endif
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 0v4z"></path>
                                        </svg>
                                    </span>
                                    <span wire:loading.remove wire:target="save">{{ $eventId ? 'Salvar' : 'Criar evento' }}</span>
                                    <span wire:loading wire:target="save">{{ $eventId ? 'Salvando...' : 'Criando...' }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

                {{-- ===== SERVICE CATEGORY TABS ===== --}}
                @foreach($categories as $cat)
                    @if($activeTab === 'cat-' . $cat->id)
                        <div class="p-6">

                            {{-- Category header --}}
                            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">{{ $cat->icon ?? '' }}</span>
                                    <div>
                                        <h2 class="font-semibold text-text">{{ $cat->name }}</h2>
                                        <p class="text-xs text-gray-400">
                                            {{ $servicesByCategory[$cat->id]->count() }} {{ $servicesByCategory[$cat->id]->count() === 1 ? 'opção disponível' : 'opções disponíveis' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Text search (individual per tab) --}}
                                <div class="relative shrink-0">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input
                                        wire:model.live.debounce.300ms="categorySearch.{{ $cat->id }}"
                                        type="text"
                                        placeholder="Buscar em {{ $cat->name }}..."
                                        class="pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition w-full sm:w-56">
                                </div>
                            </div>

                            {{-- No results --}}
                            @if($servicesByCategory[$cat->id]->isEmpty())
                                <div class="text-center py-12">
                                    <span class="text-4xl">{{ $cat->icon ?? '' }}</span>
                                    <p class="mt-3 text-sm text-gray-500">
                                        @if(!empty($categorySearch[$cat->id]) || $filterLocation !== '' || $filterMinPrice !== '' || $filterMaxPrice !== '')
                                            Nenhum resultado encontrado com os filtros aplicados.
                                        @else
                                            Nenhum serviço disponível nesta categoria ainda.
                                        @endif
                                    </p>
                                </div>
                            @else
                                {{-- Cards accordion --}}
                                <div class="space-y-3" x-data="{ open: null }">
                                    @if($cat->slug === $venueCategorySlug)
                                        {{-- ===== VENUE CARDS ===== --}}
                                        @foreach($servicesByCategory[$cat->id] as $venue)
                                            @php $isAttached = in_array($venue->id, $attachedVenueIds); @endphp
                                            <div class="border {{ $isAttached ? 'border-primary/40 bg-primary/3' : 'border-gray-100' }} rounded-xl overflow-hidden transition">

                                                {{-- Accordion header --}}
                                                <div class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition"
                                                    @click="open === {{ $venue->id }} ? open = null : open = {{ $venue->id }}">

                                                    {{-- Venue image --}}
                                                    <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0 bg-gray-100">
                                                        @if($venue->imageUrl)
                                                            <img src="{{ $venue->imageUrl }}" alt="{{ $venue->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-2xl">{{ $cat->icon ?? '' }}</div>
                                                        @endif
                                                    </div>

                                                    {{-- Info --}}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <p class="font-semibold text-text text-sm">{{ $venue->name }}</p>
                                                            @if($isAttached)
                                                                <span class="inline-flex items-center gap-1 text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                    Adicionado
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                            @if($venue->city){{ $venue->city }}@if($venue->state), {{ $venue->state }}@endif@endif
                                                            @if($venue->rating) · ★ {{ number_format($venue->rating, 1) }}@endif
                                                        </p>
                                                    </div>

                                                    {{-- Price + toggle --}}
                                                    <div class="flex items-center gap-3 shrink-0">
                                                        @if($venue->price)
                                                            <span class="text-sm font-semibold text-primary">
                                                                R$ {{ number_format($venue->price, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open === {{ $venue->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </div>
                                                </div>

                                                {{-- Accordion body --}}
                                                <div x-show="open === {{ $venue->id }}"
                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                                    class="border-t border-gray-100">
                                                    <div class="p-4 bg-gray-50">
                                                        @if($venue->description)
                                                            <p class="text-sm text-gray-600 mb-4">{{ $venue->description }}</p>
                                                        @endif

                                                        {{-- Gallery --}}
                                                        @if(!empty($venue->galleryUrls) && count($venue->galleryUrls) > 0)
                                                            <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
                                                                @foreach(array_slice($venue->galleryUrls, 0, 5) as $img)
                                                                    <img src="{{ $img }}" alt="" class="h-20 w-28 object-cover rounded-lg shrink-0">
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        {{-- Venue info --}}
                                                        <p class="text-xs text-gray-400 mb-4">
                                                            @if($venue->city)
                                                                Localização: <strong class="text-text">{{ $venue->city }}{{ $venue->state ? ', ' . $venue->state : '' }}</strong>
                                                            @endif
                                                            @if($venue->capacity) · Capacidade: <strong class="text-text">{{ $venue->capacity }} pessoas</strong>@endif
                                                        </p>

                                                        {{-- Action button --}}
                                                        <div class="flex items-center justify-between">
                                                            @if(!$eventId)
                                                                <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                                                                    Salve as informações do evento primeiro para adicionar um espaço.
                                                                </p>
                                                            @elseif($isAttached)
                                                                <button wire:click="detachVenue({{ $venue->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="detachVenue({{ $venue->id }})"
                                                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-xl transition disabled:opacity-60">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                    Remover do evento
                                                                </button>
                                                            @else
                                                                <button wire:click="attachVenue({{ $venue->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="attachVenue({{ $venue->id }})"
                                                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-white bg-primary hover:bg-primary-dark px-4 py-2 rounded-xl transition disabled:opacity-60">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                    </svg>
                                                                    Adicionar ao evento
                                                                </button>
                                                            @endif

                                                            <a href="/espacos/{{ $venue->slug }}" target="_blank"
                                                                class="text-xs text-secondary hover:underline">
                                                                Ver espaço →
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- ===== SERVICE CARDS ===== --}}
                                        @foreach($servicesByCategory[$cat->id] as $service)
                                            @php $isAttached = in_array($service->id, $attachedServiceIds); @endphp
                                            <div class="border {{ $isAttached ? 'border-primary/40 bg-primary/3' : 'border-gray-100' }} rounded-xl overflow-hidden transition">

                                                {{-- Accordion header --}}
                                                <div class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition"
                                                    @click="open === {{ $service->id }} ? open = null : open = {{ $service->id }}">

                                                    {{-- Service image --}}
                                                    <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0 bg-gray-100">
                                                        @if($service->imageUrl)
                                                            <img src="{{ $service->imageUrl }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-2xl">{{ $cat->icon ?? '' }}</div>
                                                        @endif
                                                    </div>

                                                    {{-- Info --}}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <p class="font-semibold text-text text-sm">{{ $service->name }}</p>
                                                            @if($isAttached)
                                                                <span class="inline-flex items-center gap-1 text-xs font-medium text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                    Adicionado
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                            {{ $service->supplierProfile->company_name ?? 'Fornecedor' }}
                                                            @if($service->rating)
                                                                · ★ {{ number_format($service->rating, 1) }}
                                                            @endif
                                                        </p>
                                                    </div>

                                                    {{-- Price + toggle --}}
                                                    <div class="flex items-center gap-3 shrink-0">
                                                        @if($service->price)
                                                            <span class="text-sm font-semibold text-primary">
                                                                R$ {{ number_format($service->price, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open === {{ $service->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </div>
                                                </div>

                                                {{-- Accordion body --}}
                                                <div x-show="open === {{ $service->id }}"
                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                                    class="border-t border-gray-100">
                                                    <div class="p-4 bg-gray-50">
                                                        @if($service->description)
                                                            <p class="text-sm text-gray-600 mb-4">{{ $service->description }}</p>
                                                        @endif

                                                        {{-- Gallery --}}
                                                        @if(!empty($service->resolvedImages) && count($service->resolvedImages) > 1)
                                                            <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
                                                                @foreach(array_slice($service->resolvedImages, 0, 5) as $img)
                                                                    <img src="{{ $img }}" alt="" class="h-20 w-28 object-cover rounded-lg shrink-0">
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        {{-- Supplier info --}}
                                                        @if($service->supplierProfile)
                                                            <p class="text-xs text-gray-400 mb-4">
                                                                Fornecedor: <strong class="text-text">{{ $service->supplierProfile->company_name }}</strong>
                                                                @if($service->supplierProfile->city)
                                                                    · {{ $service->supplierProfile->city }}@if($service->supplierProfile->state), {{ $service->supplierProfile->state }}@endif
                                                                @endif
                                                            </p>
                                                        @endif

                                                        {{-- Action button --}}
                                                        <div class="flex items-center justify-between">
                                                            @if(!$eventId)
                                                                <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                                                                    Salve as informações do evento primeiro para adicionar serviços.
                                                                </p>
                                                            @elseif($isAttached)
                                                                <button wire:click="detachService({{ $service->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="detachService({{ $service->id }})"
                                                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-xl transition disabled:opacity-60">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                    Remover do evento
                                                                </button>
                                                            @else
                                                                <button wire:click="attachService({{ $service->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    wire:target="attachService({{ $service->id }})"
                                                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-white bg-primary hover:bg-primary-dark px-4 py-2 rounded-xl transition disabled:opacity-60">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                    </svg>
                                                                    Adicionar ao evento
                                                                </button>
                                                            @endif

                                                            <a href="/servicos/{{ $cat->slug }}" target="_blank"
                                                                class="text-xs text-secondary hover:underline">
                                                                Ver todos em {{ $cat->name }} →
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif

                            {{-- Footer da aba --}}
                            <div class="flex items-center justify-between gap-3 pt-4 mt-4 border-t border-gray-100">
                                @php $totalAdded = count($attachedServiceIds) + count($attachedVenueIds); @endphp
                                <p class="text-sm text-gray-500">
                                    <span class="font-medium text-text">{{ $totalAdded }}</span>
                                    {{ $totalAdded === 1 ? 'item adicionado' : 'itens adicionados' }} ao evento
                                </p>
                                <div class="flex items-center gap-3">
                                    <a href="/dashboard" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-text transition">
                                        Cancelar
                                    </a>
                                    <button wire:click="save"
                                        wire:loading.attr="disabled" wire:target="save"
                                        class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-btn transition inline-flex items-center gap-2 disabled:opacity-60">
                                        <span wire:loading.remove wire:target="save">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                        <span wire:loading wire:target="save">
                                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 0v4z"></path>
                                            </svg>
                                        </span>
                                        <span wire:loading.remove wire:target="save">Salvar</span>
                                        <span wire:loading wire:target="save">Salvando...</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endif
                @endforeach

            </div>

        </div>
    </section>
</div>
