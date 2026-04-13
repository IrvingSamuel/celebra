<x-layouts.prototype title="Celebra - Protótipo">
    <div class="max-w-6xl mx-auto py-16 px-8">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-text">🖥️ Protótipo</h1>
            <p class="mt-2 text-gray-500">Mockup navegável das telas principais da plataforma Celebra</p>
        </div>

        {{-- INTRO --}}
        <section class="mb-16">
            <div class="bg-gradient-to-br from-primary/5 to-secondary/5 rounded-card p-8 border border-primary/10">
                <h2 class="text-xl font-bold text-text mb-3">Fluxo Principal do Usuário</h2>
                <p class="text-sm text-text-light mb-6">O protótipo funcional implementado em Laravel + Livewire está disponível nas rotas raiz da aplicação. Abaixo, as telas principais com links diretos.</p>

                <div class="flex flex-wrap gap-3">
                    <a href="/" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-btn hover:bg-primary-dark transition-all">
                        🏠 Home
                    </a>
                    <a href="/espacos" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary text-white text-sm font-semibold rounded-btn hover:bg-secondary-dark transition-all">
                        💒 Espaços
                    </a>
                    <a href="/planejar" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white text-sm font-semibold rounded-btn hover:opacity-90 transition-all">
                        ✨ Planejar com a Celi
                    </a>
                    <a href="/cadastrar" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary text-sm font-semibold rounded-btn hover:bg-primary hover:text-white transition-all">
                        📝 Cadastro
                    </a>
                    <a href="/entrar" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-secondary text-secondary text-sm font-semibold rounded-btn hover:bg-secondary hover:text-white transition-all">
                        🔑 Login
                    </a>
                </div>
            </div>
        </section>

        {{-- SCREEN 1: HOME --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-4">Tela 1 — Home</h2>
            <div class="bg-white rounded-card shadow-card overflow-hidden">
                {{-- Mock browser chrome --}}
                <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="bg-white rounded-pill px-4 py-1 text-xs text-gray-500 font-mono">eventos.eflow.space/</div>
                    </div>
                </div>

                {{-- Mock header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center">
                                <span class="text-white text-xs">✦</span>
                            </div>
                            <span class="text-lg font-bold text-text">Celebra</span>
                        </div>
                        <nav class="flex gap-5 text-xs font-medium text-text-light">
                            <span class="text-primary font-semibold">Início</span>
                            <span>Espaços</span>
                            <span>Serviços</span>
                            <span class="text-secondary">✨ Planejar</span>
                        </nav>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-xs text-text-light">Entrar</span>
                        <span class="px-3 py-1 bg-primary text-white text-xs rounded-btn">Cadastrar</span>
                    </div>
                </div>

                {{-- Mock hero --}}
                <div class="bg-gradient-to-br from-primary/5 to-secondary/5 px-8 py-16 text-center">
                    <h2 class="text-3xl font-bold text-text mb-3">Planeje o evento dos seus sonhos</h2>
                    <p class="text-sm text-text-light mb-6 max-w-md mx-auto">Conectamos você aos melhores fornecedores com a ajuda da Celi, sua assistente inteligente.</p>
                    <div class="flex items-center justify-center gap-3">
                        <span class="px-6 py-2 bg-primary text-white text-sm font-semibold rounded-btn">Começar</span>
                        <span class="px-6 py-2 border border-primary text-primary text-sm rounded-btn">Explorar</span>
                    </div>
                </div>

                {{-- Mock event types --}}
                <div class="px-8 py-8">
                    <h3 class="text-lg font-bold text-text mb-4 text-center">Tipos de Evento</h3>
                    <div class="grid grid-cols-6 gap-3">
                        @foreach(['Casamento', 'Festa de 15 Anos', 'Aniversário', 'Formatura', 'Confraternização', 'Conferência'] as $type)
                            <div class="text-center p-3 bg-bg rounded-card hover:shadow-card transition-all cursor-pointer">
                                <span class="text-xs font-medium text-text">{{ $type }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-8 py-4 text-center text-xs text-gray-400 border-t border-gray-100">
                    Continua: "Como funciona" → Categorias → Espaços em destaque → CTA → Footer
                </div>
            </div>
        </section>

        {{-- SCREEN 2: VENUE LISTING --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-4">Tela 2 — Listagem de Espaços</h2>
            <div class="bg-white rounded-card shadow-card overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="bg-white rounded-pill px-4 py-1 text-xs text-gray-500 font-mono">eventos.eflow.space/espacos</div>
                    </div>
                </div>

                <div class="p-8">
                    <h3 class="text-2xl font-bold text-text mb-2">Espaços para Eventos</h3>
                    <p class="text-sm text-text-light mb-6">Encontre o lugar perfeito para a sua celebração</p>

                    {{-- Search bar --}}
                    <div class="flex gap-3 mb-6">
                        <div class="flex-1 px-4 py-2 border border-gray-300 rounded-btn text-xs text-gray-400">🔍 Buscar espaços...</div>
                        <span class="px-4 py-2 bg-primary text-white text-xs rounded-btn">Filtrar</span>
                    </div>

                    {{-- Mock grid --}}
                    <div class="grid grid-cols-3 gap-4">
                        @for($i = 0; $i < 3; $i++)
                            <div class="rounded-card border border-gray-100 overflow-hidden">
                                <div class="h-28 bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center">
                                    <span class="text-2xl">💒</span>
                                </div>
                                <div class="p-3">
                                    <p class="text-xs font-medium text-text">Espaço Exemplo {{ $i + 1 }}</p>
                                    <p class="text-xs text-gray-400">📍 Recife, PE</p>
                                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-50">
                                        <span class="text-xs"><span class="text-warning">★</span> 4.8</span>
                                        <span class="text-xs font-bold text-primary">R$ 8.000</span>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>

        {{-- SCREEN 3: PLANNER / CELI --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-4">Tela 3 — Planejar com a Celi (IA)</h2>
            <div class="bg-white rounded-card shadow-card overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="bg-white rounded-pill px-4 py-1 text-xs text-gray-500 font-mono">eventos.eflow.space/planejar</div>
                    </div>
                </div>

                <div class="flex h-96">
                    {{-- Sidebar --}}
                    <div class="w-56 bg-bg p-4 border-r border-gray-200">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center">
                                <span class="text-white text-xs">✦</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-text">Celi</p>
                                <p class="text-xs text-success">● Online</p>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-400 uppercase mb-3">Atalhos</p>
                        <div class="space-y-2">
                            @foreach(['Casamento', 'Festa de 15 Anos', 'Aniversário', 'Formatura'] as $btn)
                                <div class="px-3 py-2 bg-white rounded-btn text-xs text-text hover:shadow-sm cursor-pointer">{{ $btn }}</div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Chat area --}}
                    <div class="flex-1 flex flex-col">
                        <div class="flex-1 p-6 space-y-4 overflow-y-auto">
                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                                    <span class="text-white text-[8px]">✦</span>
                                </div>
                                <div class="bg-bg rounded-card rounded-tl-none p-3 max-w-xs">
                                    <p class="text-xs text-text">Olá! Sou a <strong>Celi</strong> 🎉 Que tipo de evento você quer planejar?</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2 flex-row-reverse">
                                <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center shrink-0">
                                    <span class="text-white text-[8px] font-bold">U</span>
                                </div>
                                <div class="bg-secondary text-white rounded-card rounded-tr-none p-3 max-w-xs">
                                    <p class="text-xs">Quero fazer uma festa de 15 anos incrível!</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                                    <span class="text-white text-[8px]">✦</span>
                                </div>
                                <div class="bg-bg rounded-card rounded-tl-none p-3 max-w-xs">
                                    <p class="text-xs text-text">Que demais! 👑 Vou te ajudar a encontrar os melhores espaços e fornecedores. Quantos convidados?</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border-t border-gray-100">
                            <div class="flex gap-2">
                                <div class="flex-1 px-4 py-2 border border-gray-300 rounded-btn text-xs text-gray-400">Digite sua mensagem...</div>
                                <div class="w-10 h-10 bg-primary rounded-btn flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SCREEN 4: REGISTER --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-4">Tela 4 — Cadastro</h2>
            <div class="bg-white rounded-card shadow-card overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="bg-white rounded-pill px-4 py-1 text-xs text-gray-500 font-mono">eventos.eflow.space/cadastrar</div>
                    </div>
                </div>

                <div class="p-8 flex items-center justify-center bg-bg">
                    <div class="bg-white rounded-card shadow-card p-8 w-full max-w-md">
                        <div class="text-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-white text-sm">✦</span>
                            </div>
                            <h3 class="text-lg font-bold text-text">Criar conta</h3>
                            <p class="text-xs text-gray-400">Escolha seu tipo de conta</p>
                        </div>

                        {{-- Role selection --}}
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div class="p-4 border-2 border-primary rounded-card text-center bg-primary/5">
                                <span class="text-2xl">🎉</span>
                                <p class="text-xs font-semibold text-primary mt-1">Cliente</p>
                            </div>
                            <div class="p-4 border border-gray-200 rounded-card text-center hover:border-secondary/50 cursor-pointer">
                                <span class="text-2xl">💼</span>
                                <p class="text-xs font-semibold text-text-light mt-1">Fornecedor</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Nome completo</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-btn text-xs text-gray-400">Irving Samuel</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Email</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-btn text-xs text-gray-400">irving@email.com</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Senha</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-btn text-xs text-gray-400">••••••••</div>
                            </div>
                            <div class="px-6 py-3 bg-primary text-white text-xs font-semibold rounded-btn text-center">Cadastrar</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FLOW DIAGRAM --}}
        <section>
            <h2 class="text-2xl font-bold text-text mb-4">Mapa de Navegação</h2>
            <div class="bg-bg rounded-card p-8">
                <div class="flex flex-col items-center gap-4">
                    {{-- Level 1 --}}
                    <div class="px-6 py-3 bg-primary text-white font-bold rounded-btn text-sm">🏠 Home</div>
                    <svg class="w-4 h-8" viewBox="0 0 16 32"><line x1="8" y1="0" x2="8" y2="32" stroke="#49516F" stroke-width="2"/></svg>

                    {{-- Level 2 --}}
                    <div class="flex items-center gap-4">
                        <div class="px-4 py-2 bg-secondary text-white rounded-btn text-xs font-medium">💒 Espaços</div>
                        <div class="px-4 py-2 bg-secondary text-white rounded-btn text-xs font-medium">📋 Serviços</div>
                        <div class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-btn text-xs font-medium">✨ Planejar</div>
                        <div class="px-4 py-2 border border-primary text-primary rounded-btn text-xs font-medium">📝 Cadastro</div>
                        <div class="px-4 py-2 border border-secondary text-secondary rounded-btn text-xs font-medium">🔑 Login</div>
                    </div>
                    <svg class="w-4 h-8" viewBox="0 0 16 32"><line x1="8" y1="0" x2="8" y2="32" stroke="#49516F" stroke-width="2"/></svg>

                    {{-- Level 3 --}}
                    <div class="flex items-center gap-4">
                        <div class="px-4 py-2 bg-white border border-gray-200 rounded-btn text-xs font-medium text-text">📄 Detalhe Espaço</div>
                        <div class="px-4 py-2 bg-white border border-gray-200 rounded-btn text-xs font-medium text-text">📊 Dashboard</div>
                        <div class="px-4 py-2 bg-white border border-gray-200 rounded-btn text-xs font-medium text-text">🎁 Presentes</div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-white rounded-btn">
                    <p class="text-xs text-gray-500 text-center">
                        <strong>Rotas do protótipo funcional:</strong>
                        <code class="text-primary">/</code> · <code class="text-primary">/espacos</code> · <code class="text-primary">/espacos/{slug}</code> · <code class="text-primary">/servicos/{categoria?}</code> · <code class="text-primary">/planejar</code> · <code class="text-primary">/cadastrar</code> · <code class="text-primary">/entrar</code> · <code class="text-primary">/dashboard</code> · <code class="text-primary">/presentes/{slug}</code>
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.prototype>
