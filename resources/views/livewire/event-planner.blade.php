<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 bg-secondary/10 text-secondary px-4 py-2 rounded-full text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path transform="translate(3, 0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    Assistente Celi
                </div>
                <h1 class="text-3xl font-bold text-text">Planejar Evento</h1>
                <p class="mt-2 text-gray-500">Converse com a Celi para receber recomendações personalizadas</p>
            </div>

            <div class="flex gap-6">
                {{-- Sidebar: Conversation History --}}
                <div class="hidden lg:block w-72 shrink-0">
                    <div class="bg-gray-100 rounded-card shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-gray-100">
                            <button wire:click="newConversation"
                                class="w-full flex items-center justify-center gap-2 bg-secondary hover:bg-secondary-dark text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Nova Conversa
                            </button>
                        </div>
                        <div class="max-h-[480px] overflow-y-auto">
                            @forelse($conversations as $conv)
                                <div class="group flex items-center gap-2 px-4 py-3 border-b border-gray-50 hover:bg-bg transition cursor-pointer {{ $activeConversationId === $conv->id ? 'bg-secondary/5 border-l-2 border-l-secondary' : '' }}">
                                    <button wire:click="loadConversation({{ $conv->id }})" class="flex-1 text-left min-w-0">
                                        <p class="text-sm font-medium text-text truncate">{{ $conv->title ?? 'Conversa' }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $conv->updated_at->diffForHumans() }}</p>
                                    </button>
                                    <button
                                        x-on:click="SwalTheme.dangerDialog({ title: 'Excluir conversa?', text: 'Esta ação não pode ser desfeita.', confirmButtonText: 'Excluir' }).then(r => r.isConfirmed && $wire.deleteConversation({{ $conv->id }}))"
                                        class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-red-500 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <p class="text-xs text-gray-400">Suas conversas com a Celi aparecerão aqui</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Chat Container --}}
                <div class="flex-1 min-w-0">
                    {{-- Mobile: Session selector --}}
                    @if($conversations->count() > 0)
                    <div class="lg:hidden mb-4 flex items-center gap-2">
                        <button wire:click="newConversation"
                            class="flex items-center gap-1 bg-secondary hover:bg-secondary-dark text-white text-sm font-medium px-3 py-2 rounded-xl transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nova
                        </button>
                        <select wire:change="loadConversation($event.target.value)"
                            class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 text-text focus:ring-2 focus:ring-secondary focus:border-transparent">
                            <option value="">Selecionar conversa...</option>
                            @foreach($conversations as $conv)
                                <option value="{{ $conv->id }}" {{ $activeConversationId === $conv->id ? 'selected' : '' }}>
                                    {{ $conv->title ?? 'Conversa' }} — {{ $conv->updated_at->diffForHumans() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="bg-gray-100 rounded-card shadow-sm overflow-hidden">
                        {{-- Messages --}}
                        <div class="h-[500px] overflow-y-auto p-6 space-y-4" id="chat-messages">
                            @foreach($messages as $message)
                                @if($message['role'] === 'assistant')
                                    {{-- Celi message --}}
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                                            <span class="text-white text-xs">✦</span>
                                        </div>
                                        <div class="bg-white rounded-card rounded-tl-none p-4 shadow-card max-w-sm">
                                            <div class="text-sm text-text leading-relaxed prose prose-sm max-w-none [&_a:not(.celi-card)]:text-secondary [&_a:not(.celi-card)]:underline [&_a:not(.celi-card)]:font-medium">
                                                {!! $this->formatMessageContent($message['content']) !!}
                                            </div>
                                            @if(!empty($message['time']))
                                                <span class="text-xs text-gray-400 mt-2 block">{{ $message['time'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    {{-- User message --}}
                                    <div class="flex items-start gap-3 flex-row-reverse">
                                        <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center shrink-0">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <div class="bg-secondary text-white rounded-card rounded-tr-none p-4 shadow-card max-w-sm">
                                            <div class="text-sm leading-relaxed prose prose-sm max-w-none prose-invert [&_a:not(.celi-card)]:text-white [&_a:not(.celi-card)]:underline">
                                                {!! \Illuminate\Support\Str::markdown($message['content'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                            </div>
                                            @if(!empty($message['time']))
                                                <span class="text-xs text-white/60 mt-2 block">{{ $message['time'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Flash action buttons (appear after at least one AI response) --}}
                            @php
                                $hasAssistantMessage = collect($messages)->contains('role', 'assistant')
                                    && collect($messages)->contains('role', 'user');
                            @endphp
                            @if($hasAssistantMessage)
                                <div wire:loading.remove wire:target="fetchResponse"
                                    class="flex flex-wrap gap-2 pl-11 pt-1">
                                    <button wire:click="createEventFromChat"
                                        wire:loading.attr="disabled" wire:target="createEventFromChat"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-full transition shadow-sm disabled:opacity-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Criar evento com base nesta conversa
                                    </button>
                                    <a href="/dashboard"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-bg text-text text-sm font-medium rounded-full border border-gray-200 hover:border-gray-300 transition shadow-sm">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Ver meus eventos
                                    </a>
                                </div>
                            @endif

                            <div wire:loading.flex wire:target="fetchResponse" class="items-start gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                                    <span class="text-white text-xs">✦</span>
                                </div>
                                <div class="bg-white rounded-card rounded-tl-none p-4 shadow-card">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Celi está digitando</span>
                                        <span class="flex gap-0.5">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Input --}}
                        <div class="border-t border-gray-100 p-4">
                            <form wire:submit="sendMessage" class="flex items-center gap-3">
                                <input wire:model="userMessage" type="text" placeholder="Digite sua mensagem..."
                                    class="flex-1 px-4 py-3 rounded-full border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition"
                                    wire:loading.attr="disabled" wire:target="sendMessage, quickSend, fetchResponse"
                                    autocomplete="off">
                                <button type="submit" class="bg-secondary hover:bg-secondary-dark text-white p-3 rounded-full transition disabled:opacity-50"
                                    wire:loading.attr="disabled" wire:target="sendMessage, quickSend, fetchResponse">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="mt-6 flex flex-wrap gap-2 justify-center">
                        @foreach($eventTypes as $et)
                            <button wire:click="quickSend('Quero planejar {{ strtolower($et->name) }}')"
                                wire:loading.attr="disabled" wire:target="sendMessage, quickSend, fetchResponse"
                                class="px-4 py-2 bg-white rounded-full text-sm text-text hover:bg-primary hover:text-white transition shadow-sm disabled:opacity-50">
                                {{ $et->icon }} {{ $et->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            width: 6rem;
            height: 5.5rem;
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
        }
        .celi-card-title {
            display: block;
            font-weight: 600;
            font-size: 0.825rem;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .celi-card-sub {
            display: block;
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 0.1rem;
        }
        .celi-card-price {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #e91e8c;
            margin-top: 0.2rem;
        }
        .celi-card-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.675rem;
            color: #94a3b8;
            margin-top: 0.15rem;
        }
        .celi-card-rating {
            color: #f59e0b;
            font-weight: 600;
        }
        .celi-card-arrow {
            font-size: 1.1rem;
            color: #cbd5e1;
            padding-right: 0.75rem;
            flex-shrink: 0;
            transition: color 0.2s;
        }
        .celi-card:hover .celi-card-arrow {
            color: #e91e8c;
        }
    </style>

    @script
    <script>
        // Auto-scroll on new messages
        const observer = new MutationObserver(() => {
            const el = document.getElementById('chat-messages');
            if (el) el.scrollTop = el.scrollHeight;
        });
        const chatEl = document.getElementById('chat-messages');
        if (chatEl) observer.observe(chatEl, { childList: true, subtree: true });

        // Make all links in assistant messages open in new tab
        document.addEventListener('click', (e) => {
            const link = e.target.closest('#chat-messages a');
            if (link && !link.hasAttribute('target')) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });
    </script>
    @endscript
</div>
