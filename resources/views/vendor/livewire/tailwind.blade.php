@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginação" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-btn">
                    Anterior
                </span>
            @else
                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-text bg-white border border-gray-200 rounded-btn hover:bg-gray-50 transition">
                    Anterior
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-text bg-white border border-gray-200 rounded-btn hover:bg-gray-50 transition">
                    Próximo
                </button>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-btn">
                    Próximo
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Mostrando
                    <span class="font-semibold text-text">{{ $paginator->firstItem() }}</span>
                    até
                    <span class="font-semibold text-text">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-semibold text-text">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-btn shadow-sm gap-1">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-2.5 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-btn" aria-disabled="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-2.5 py-2 text-sm font-medium text-text bg-white border border-gray-200 rounded-btn hover:bg-gray-50 transition" aria-label="Anterior">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                    @endif

                    {{-- Pages --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default rounded-btn">...</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="relative inline-flex items-center px-3.5 py-2 text-sm font-bold text-white bg-primary border border-primary rounded-btn" aria-current="page">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-text bg-white border border-gray-200 rounded-btn hover:bg-gray-50 transition">{{ $page }}</button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-2.5 py-2 text-sm font-medium text-text bg-white border border-gray-200 rounded-btn hover:bg-gray-50 transition" aria-label="Próximo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-2.5 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 cursor-not-allowed rounded-btn" aria-disabled="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
