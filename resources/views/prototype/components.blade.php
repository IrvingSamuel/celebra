<x-layouts.prototype title="Celebra - Biblioteca de Componentes">
    <div class="max-w-6xl mx-auto py-16 px-8">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-text">🧩 Biblioteca de Componentes</h1>
            <p class="mt-2 text-gray-500">Componentes reutilizáveis documentados com estados e especificações</p>
        </div>

        {{-- ==================== BUTTONS ==================== --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-2">1. Botões</h2>
            <p class="text-sm text-gray-500 mb-6">Variantes: Primary, Secondary, Outline. Estados: Default, Hover, Disabled.</p>

            <div class="bg-bg rounded-card p-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Preview</h3>
                <div class="flex flex-wrap items-center gap-4 mb-8">
                    {{-- Primary --}}
                    <button class="px-6 py-3 bg-primary text-white font-semibold rounded-btn shadow-sm hover:bg-primary-dark transition-all duration-300">
                        Primary
                    </button>
                    <button class="px-6 py-3 bg-primary-dark text-white font-semibold rounded-btn shadow-sm">
                        Primary :hover
                    </button>
                    <button class="px-6 py-3 bg-primary/50 text-white/70 font-semibold rounded-btn cursor-not-allowed">
                        Primary :disabled
                    </button>

                    {{-- Secondary --}}
                    <button class="px-6 py-3 bg-secondary text-white font-semibold rounded-btn shadow-sm hover:bg-secondary-dark transition-all duration-300">
                        Secondary
                    </button>
                    <button class="px-6 py-3 bg-secondary-dark text-white font-semibold rounded-btn shadow-sm">
                        Secondary :hover
                    </button>
                    <button class="px-6 py-3 bg-secondary/50 text-white/70 font-semibold rounded-btn cursor-not-allowed">
                        Secondary :disabled
                    </button>

                    {{-- Outline --}}
                    <button class="px-6 py-3 border-2 border-primary text-primary font-semibold rounded-btn hover:bg-primary hover:text-white transition-all duration-300">
                        Outline
                    </button>
                    <button class="px-6 py-3 bg-primary text-white font-semibold rounded-btn border-2 border-primary">
                        Outline :hover
                    </button>
                    <button class="px-6 py-3 border-2 border-gray-300 text-gray-400 font-semibold rounded-btn cursor-not-allowed">
                        Outline :disabled
                    </button>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Especificações</h3>
                <div class="bg-white rounded-btn p-4 text-sm text-text space-y-1">
                    <p><strong>Padding:</strong> 12px 24px (py-3 px-6)</p>
                    <p><strong>Font:</strong> Poppins SemiBold 16px</p>
                    <p><strong>Border Radius:</strong> 8px (--radius-btn)</p>
                    <p><strong>Transição:</strong> all 300ms ease</p>
                    <p><strong>Sombra:</strong> shadow-sm (default), sem sombra (outline)</p>
                </div>
            </div>
        </section>

        {{-- ==================== INPUTS ==================== --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-2">2. Campos de Entrada</h2>
            <p class="text-sm text-gray-500 mb-6">Estados: Normal, Focus, Error, Disabled. Com label e helper text.</p>

            <div class="bg-bg rounded-card p-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Preview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Normal --}}
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Nome completo</label>
                        <input type="text" value="Irving Samuel" class="w-full px-4 py-3 border border-gray-300 rounded-btn text-text focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all" />
                        <p class="mt-1 text-xs text-gray-400">Estado: Normal</p>
                    </div>

                    {{-- Focus --}}
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Email</label>
                        <input type="email" value="irving@celebra.com" class="w-full px-4 py-3 border-2 border-primary rounded-btn text-text ring-2 ring-primary/30 focus:outline-none" />
                        <p class="mt-1 text-xs text-primary">Estado: Focus</p>
                    </div>

                    {{-- Error --}}
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Senha</label>
                        <input type="password" value="123" class="w-full px-4 py-3 border-2 border-error rounded-btn text-text ring-2 ring-error/30 focus:outline-none" />
                        <p class="mt-1 text-xs text-error">A senha precisa ter no mínimo 8 caracteres</p>
                    </div>

                    {{-- Disabled --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Campo bloqueado</label>
                        <input type="text" value="Não editável" disabled class="w-full px-4 py-3 border border-gray-200 rounded-btn text-gray-400 bg-gray-100 cursor-not-allowed" />
                        <p class="mt-1 text-xs text-gray-400">Estado: Disabled</p>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Especificações</h3>
                <div class="bg-white rounded-btn p-4 text-sm text-text space-y-1">
                    <p><strong>Padding:</strong> 12px 16px (py-3 px-4)</p>
                    <p><strong>Font:</strong> Poppins Regular 16px</p>
                    <p><strong>Border:</strong> 1px solid #D1D5DB (normal), 2px solid primary (focus), 2px solid error (error)</p>
                    <p><strong>Border Radius:</strong> 8px (--radius-btn)</p>
                    <p><strong>Focus Ring:</strong> ring-2 ring-primary/30</p>
                    <p><strong>Helper text:</strong> 12px, cor contextual (gray, primary, error)</p>
                </div>
            </div>
        </section>

        {{-- ==================== CARDS ==================== --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-2">3. Cards</h2>
            <p class="text-sm text-gray-500 mb-6">Venue Card e Service Card — componentes chave da plataforma.</p>

            <div class="bg-bg rounded-card p-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Preview — Venue Card</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-card shadow-card hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer">
                        <div class="h-48 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                            <span class="text-4xl">💒</span>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded-pill">Casamento</span>
                                <span class="px-2 py-0.5 bg-secondary/10 text-secondary text-xs font-medium rounded-pill">15 Anos</span>
                            </div>
                            <h3 class="text-lg font-semibold text-text group-hover:text-primary transition-colors">Espaço Jardim das Flores</h3>
                            <p class="text-sm text-text-light mt-1">📍 Recife, PE</p>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-1">
                                    <span class="text-warning">★</span>
                                    <span class="text-sm font-medium text-text">4.8</span>
                                    <span class="text-xs text-gray-400">(124)</span>
                                </div>
                                <p class="text-sm font-bold text-primary">A partir de R$ 8.000</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-card shadow-card hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer">
                        <div class="h-48 bg-gradient-to-br from-secondary/20 to-primary/20 flex items-center justify-center">
                            <span class="text-4xl">📸</span>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded-pill">Fotografia</span>
                            </div>
                            <h3 class="text-lg font-semibold text-text group-hover:text-primary transition-colors">Studio Momento Perfeito</h3>
                            <p class="text-sm text-text-light mt-1">📍 Olinda, PE</p>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-1">
                                    <span class="text-warning">★</span>
                                    <span class="text-sm font-medium text-text">4.9</span>
                                    <span class="text-xs text-gray-400">(87)</span>
                                </div>
                                <p class="text-sm font-bold text-primary">A partir de R$ 2.500</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Especificações</h3>
                <div class="bg-white rounded-btn p-4 text-sm text-text space-y-1">
                    <p><strong>Border Radius:</strong> 12px (--radius-card)</p>
                    <p><strong>Shadow:</strong> --shadow-card (0 2px 8px rgba(0,0,0,0.08))</p>
                    <p><strong>Hover Shadow:</strong> shadow-lg</p>
                    <p><strong>Image:</strong> height 192px (h-48), object-cover</p>
                    <p><strong>Padding (body):</strong> 20px (p-5)</p>
                    <p><strong>Tags:</strong> pill radius, bg primary/10 ou secondary/10</p>
                    <p><strong>Transição:</strong> all 300ms ease</p>
                </div>
            </div>
        </section>

        {{-- ==================== NAVIGATION ==================== --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-2">4. Navegação</h2>
            <p class="text-sm text-gray-500 mb-6">Header com brand, links, autenticação condicional e menu mobile.</p>

            <div class="bg-bg rounded-card p-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Preview — Header</h3>
                <div class="bg-white rounded-card shadow-card overflow-hidden mb-8">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-8">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">✦</span>
                                </div>
                                <span class="text-xl font-bold text-text">Celebra</span>
                            </div>
                            <nav class="flex items-center gap-6 text-sm font-medium text-text-light">
                                <a class="text-primary font-semibold">Início</a>
                                <a class="hover:text-primary transition-colors">Espaços</a>
                                <a class="hover:text-primary transition-colors">Serviços ▾</a>
                                <a class="text-secondary hover:text-secondary-dark transition-colors">✨ Planejar Evento</a>
                            </nav>
                        </div>
                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 text-sm font-medium text-text-light hover:text-primary transition-colors">Entrar</button>
                            <button class="px-4 py-2 text-sm font-semibold bg-primary text-white rounded-btn hover:bg-primary-dark transition-all">Cadastrar</button>
                        </div>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Especificações</h3>
                <div class="bg-white rounded-btn p-4 text-sm text-text space-y-1">
                    <p><strong>Altura:</strong> ~64px</p>
                    <p><strong>Background:</strong> white, shadow-sm no scroll</p>
                    <p><strong>Brand:</strong> gradiente primary→secondary, pill, ícone ✦</p>
                    <p><strong>Links:</strong> Poppins Medium 14px, text-light, hover:text-primary</p>
                    <p><strong>Link ativo:</strong> text-primary, font-semibold</p>
                    <p><strong>CTA Planejar:</strong> text-secondary, ícone sparkle ✨</p>
                    <p><strong>Mobile:</strong> menu hamburger com slide-in overlay</p>
                </div>
            </div>
        </section>

        {{-- ==================== CHAT BUBBLE (FREE COMPONENT) ==================== --}}
        <section>
            <h2 class="text-2xl font-bold text-text mb-2">5. Chat Bubble — Celi (Componente Livre)</h2>
            <p class="text-sm text-gray-500 mb-6">Bolhas de conversa da assistente IA Celi — usadas na página "Planejar Evento".</p>

            <div class="bg-bg rounded-card p-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Preview</h3>
                <div class="max-w-lg mx-auto space-y-4 mb-8">
                    {{-- Celi message --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                            <span class="text-white text-xs">✦</span>
                        </div>
                        <div class="bg-white rounded-card rounded-tl-none p-4 shadow-card max-w-sm">
                            <p class="text-sm text-text">Olá! Sou a <strong>Celi</strong>, sua assistente de eventos! 🎉 Como posso ajudar hoje?</p>
                            <span class="text-xs text-gray-400 mt-2 block">14:32</span>
                        </div>
                    </div>

                    {{-- User message --}}
                    <div class="flex items-start gap-3 flex-row-reverse">
                        <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center shrink-0">
                            <span class="text-white text-xs font-bold">U</span>
                        </div>
                        <div class="bg-secondary text-white rounded-card rounded-tr-none p-4 shadow-card max-w-sm">
                            <p class="text-sm">Quero organizar uma festa de 15 anos para 200 pessoas em Recife!</p>
                            <span class="text-xs text-white/60 mt-2 block">14:33</span>
                        </div>
                    </div>

                    {{-- Celi response --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shrink-0">
                            <span class="text-white text-xs">✦</span>
                        </div>
                        <div class="bg-white rounded-card rounded-tl-none p-4 shadow-card max-w-sm">
                            <p class="text-sm text-text">Que incrível! 👑 Encontrei <strong>3 espaços</strong> perfeitos para uma festa de 15 anos com 200 convidados em Recife. Vou te mostrar as melhores opções!</p>
                            <span class="text-xs text-gray-400 mt-2 block">14:33</span>
                        </div>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Floating Action Button (FAB)</h3>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-text">FAB — Falar com a Celi</p>
                        <p class="text-xs text-gray-400">Fixo no canto inferior direito (bottom-6 right-6)</p>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Especificações</h3>
                <div class="bg-white rounded-btn p-4 text-sm text-text space-y-1">
                    <p><strong>Avatar Celi:</strong> 32px, gradiente primary→secondary, ícone ✦</p>
                    <p><strong>Bolha Celi:</strong> bg-white, shadow-card, rounded-card com rounded-tl-none</p>
                    <p><strong>Bolha Usuário:</strong> bg-secondary, text-white, rounded-card com rounded-tr-none</p>
                    <p><strong>Texto:</strong> 14px Regular, markdown renderizado (bold, links, listas)</p>
                    <p><strong>Timestamp:</strong> 12px, text-gray-400 (Celi) ou text-white/60 (user)</p>
                    <p><strong>FAB:</strong> 56px (w-14 h-14), gradiente, shadow-lg, hover:scale(1.1), fixed bottom-6 right-6</p>
                    <p><strong>Max-width bolha:</strong> ~320px (max-w-sm)</p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.prototype>
