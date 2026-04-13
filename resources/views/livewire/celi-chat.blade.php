<div
    x-data="{ open: false, showHistory: false }"
    x-on:keydown.escape.window="open = false"
>
    {{-- ================================================================ --}}
    {{-- FAB Button — seguindo Component Library: w-14 h-14, gradiente,  --}}
    {{-- shadow-lg, hover:scale-110, fixed bottom-6 right-6              --}}
    {{-- ================================================================ --}}
    <button
        x-on:click="open = !open; if (open) { showHistory = false; $nextTick(() => { let el = document.getElementById('celi-mini-messages'); if (el) el.scrollTop = el.scrollHeight; }) }"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300"
        title="Falar com a Celi"
        aria-label="Abrir chat com a Celi"
    >
        {{-- Ícone chat (fechado) → X (aberto) --}}
        <svg x-show="!open" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg x-show="open" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- ================================================================ --}}
    {{-- Chat Panel                                                       --}}
    {{-- ================================================================ --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-24 right-6 z-50 w-[400px] min-h-[70vh] max-h-[600px] flex flex-col bg-white rounded-card shadow-xl overflow-hidden"
        style="display: none;"
    >

        {{-- ---- Header ---- --}}
        <div class="bg-gradient-to-r from-primary to-secondary px-4 py-3 flex items-center gap-3 shrink-0">
            {{-- Avatar Celi --}}
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                <span class="text-white text-sm">✦</span>
            </div>
            {{-- Nome / subtítulo --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white leading-tight">Celi</p>
                <p class="text-xs text-white/70 leading-tight">Assistente IA · Sempre online</p>
            </div>
            {{-- Botão histórico --}}
            <button
                x-on:click="showHistory = !showHistory"
                x-bind:class="showHistory ? 'bg-white/30' : 'hover:bg-white/20'"
                class="p-1.5 rounded-lg transition"
                title="Histórico de conversas"
                aria-label="Ver histórico"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
            {{-- Nova conversa --}}
            <button
                wire:click="newConversation"
                class="p-1.5 rounded-lg hover:bg-white/20 transition"
                title="Nova conversa"
                aria-label="Nova conversa"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
            {{-- Abrir página completa --}}
            <a
                href="/planejar"
                target="_blank"
                rel="noopener noreferrer"
                class="p-1.5 rounded-lg hover:bg-white/20 transition"
                title="Abrir em tela cheia"
                aria-label="Abrir página completa"
            >
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>

        {{-- ---- Painel de Histórico (colapsível) ---- --}}
        <div
            x-show="showHistory"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="bg-bg border-b border-gray-200 max-h-52 overflow-y-auto shrink-0"
            style="display: none;"
        >
            @forelse($conversations as $conv)
                <div class="group flex items-center gap-2 px-3 py-2.5 border-b border-gray-100 hover:bg-white transition cursor-pointer {{ $activeConversationId === $conv->id ? 'bg-secondary/5 border-l-2 border-l-secondary' : '' }}">
                    <button
                        wire:click="loadConversation({{ $conv->id }})"
                        x-on:click="showHistory = false"
                        class="flex-1 text-left min-w-0"
                    >
                        <p class="text-xs font-medium text-text truncate">{{ $conv->title ?? 'Conversa' }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $conv->updated_at->diffForHumans() }}</p>
                    </button>
                    <button
                        x-on:click="SwalTheme.dangerDialog({ title: 'Excluir conversa?', text: 'Esta ação não pode ser desfeita.', confirmButtonText: 'Excluir' }).then(r => r.isConfirmed && $wire.deleteConversation({{ $conv->id }}))"
                        class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-red-500 transition shrink-0"
                        aria-label="Excluir conversa"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            @empty
                <div class="p-5 text-center">
                    <p class="text-xs text-gray-400">Nenhuma conversa ainda.</p>
                </div>
            @endforelse
        </div>

        {{-- ---- Área de Mensagens ---- --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-bg" id="celi-mini-messages" style="min-height:0;">
            @foreach($messages as $message)
                @if($message['role'] === 'assistant')
                    {{-- Bolha Celi: bg-white, shadow-card, rounded-card rounded-tl-none --}}
                    <div class="flex items-start gap-2">
                        <div class="w-7 h-7 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                            <span class="text-white text-[10px]">✦</span>
                        </div>
                        <div class="bg-white rounded-card rounded-tl-none p-3 shadow-card max-w-[260px]">
                            <div class="text-sm text-text leading-relaxed prose prose-sm max-w-none [&_a:not(.celi-card)]:text-secondary [&_a:not(.celi-card)]:underline [&_a:not(.celi-card)]:font-medium">
                                {!! $this->formatMessageContent($message['content']) !!}
                            </div>
                            @if(!empty($message['time']))
                                <span class="text-[10px] text-gray-400 mt-1.5 block">{{ $message['time'] }}</span>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Bolha usuário: bg-secondary, text-white, rounded-card rounded-tr-none --}}
                    <div class="flex items-start gap-2 flex-row-reverse">
                        <div class="w-7 h-7 bg-secondary rounded-full flex items-center justify-center shrink-0">
                            <span class="text-white text-[10px] font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div class="bg-secondary text-white rounded-card rounded-tr-none p-3 shadow-card max-w-[260px]">
                            <div class="text-sm leading-relaxed prose prose-sm max-w-none prose-invert [&_a:not(.celi-card)]:text-white [&_a:not(.celi-card)]:underline">
                                {!! \Illuminate\Support\Str::markdown($message['content'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                            </div>
                            @if(!empty($message['time']))
                                <span class="text-[10px] text-white/60 mt-1.5 block">{{ $message['time'] }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Indicador de digitação --}}
            <div wire:loading.flex wire:target="fetchResponse" class="items-start gap-2">
                <div class="w-7 h-7 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                    <span class="text-white text-[10px]">✦</span>
                </div>
                <div class="bg-white rounded-card rounded-tl-none p-3 shadow-card">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-500">Celi está digitando</span>
                        <span class="flex gap-0.5">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---- Quick Actions (só na mensagem inicial) ---- --}}
        @if(count($messages) === 1)
            <div class="px-3 py-2 bg-bg border-t border-gray-100 flex gap-1.5 overflow-x-auto shrink-0" style="scrollbar-width:none;">
                @foreach($eventTypes as $et)
                    <button
                        wire:click="quickSend('Quero planejar {{ strtolower($et->name) }}')"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage, quickSend, fetchResponse"
                        class="px-3 py-1.5 bg-white rounded-full text-xs text-text hover:bg-primary hover:text-white transition shadow-sm whitespace-nowrap disabled:opacity-50 shrink-0"
                    >{{ $et->icon }} {{ $et->name }}</button>
                @endforeach
            </div>
        @endif

        {{-- ---- Campo de Entrada ---- --}}
        <div class="border-t border-gray-100 px-3 py-3 bg-white shrink-0">
            <form wire:submit="sendMessage" class="flex items-center gap-2">
                <input
                    wire:model="userMessage"
                    type="text"
                    placeholder="Mensagem para a Celi..."
                    autocomplete="off"
                    wire:loading.attr="disabled"
                    wire:target="sendMessage, quickSend, fetchResponse"
                    class="flex-1 px-4 py-2.5 rounded-full border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition"
                >
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="sendMessage, quickSend, fetchResponse"
                    class="w-9 h-9 bg-secondary hover:bg-secondary-dark text-white rounded-full flex items-center justify-center transition disabled:opacity-50 shrink-0"
                    aria-label="Enviar mensagem"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Estilos dos cards de sugestão --}}
    <style>
        .celi-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            overflow: hidden;
            margin: 0.5rem 0;
            text-decoration: none !important;
            color: inherit !important;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .celi-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #c0a;
            transform: translateY(-1px);
        }
        .celi-card-img {
            width: 5rem;
            height: 5rem;
            object-fit: cover;
            flex-shrink: 0;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .celi-card-body {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            max-height: 5rem;
        }
        .celi-card-title {
            display: block;
            font-weight: 600;
            font-size: 0.75rem;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .celi-card-sub {
            display: block;
            font-size: 0.65rem;
            color: #94a3b8;
            margin-top: 0.1rem;
        }
        .celi-card-price {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #e91e8c;
            margin-top: 0.2rem;
        }
        .celi-card-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.625rem;
            color: #94a3b8;
            margin-top: 0.1rem;
        }
        .celi-card-rating {
            color: #f59e0b;
            font-weight: 600;
        }
        .celi-card-arrow {
            font-size: 1rem;
            color: #cbd5e1;
            padding-right: 0.5rem;
            flex-shrink: 0;
            transition: color 0.2s;
        }
        .celi-card:hover .celi-card-arrow {
            color: #e91e8c;
        }
    </style>

    @script
    <script>
        // Auto-scroll quando novas mensagens chegam
        const miniObserver = new MutationObserver(() => {
            const el = document.getElementById('celi-mini-messages');
            if (el) el.scrollTop = el.scrollHeight;
        });
        const miniChat = document.getElementById('celi-mini-messages');
        if (miniChat) {
            miniObserver.observe(miniChat, { childList: true, subtree: true });
        }

        // Links de assistente abrem em nova aba
        document.addEventListener('click', (e) => {
            const link = e.target.closest('#celi-mini-messages a');
            if (link && !link.hasAttribute('target')) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });
    </script>
    @endscript
</div>
