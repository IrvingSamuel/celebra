<x-layouts.prototype title="Celebra - Moodboard">
    <div class="max-w-6xl mx-auto py-16 px-8">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-text">🎨 Moodboard</h1>
            <p class="mt-2 text-gray-500">Painel de referências visuais que expressam a personalidade da marca Celebra</p>
        </div>

        {{-- Section: Color Inspiration --}}
        <section class="mb-16">
            <h2 class="text-xl font-semibold text-text mb-2">Paleta de Cores — Inspiração</h2>
            <p class="text-sm text-gray-500 mb-6">Cores vibrantes e festivas que transmitem energia e celebração, com contrastes de tons sóbrios para profissionalismo.</p>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center">
                    <div class="aspect-square rounded-card bg-[#FF477E] mb-2 shadow-sm"></div>
                    <p class="text-xs font-medium text-text">Coral Vibrante</p>
                    <p class="text-xs text-gray-400">#FF477E</p>
                </div>
                <div class="text-center">
                    <div class="aspect-square rounded-card bg-[#5465FF] mb-2 shadow-sm"></div>
                    <p class="text-xs font-medium text-text">Azul Elétrico</p>
                    <p class="text-xs text-gray-400">#5465FF</p>
                </div>
                <div class="text-center">
                    <div class="aspect-square rounded-card bg-[#F4F4F4] mb-2 shadow-sm border border-gray-200"></div>
                    <p class="text-xs font-medium text-text">Cinza Neutro</p>
                    <p class="text-xs text-gray-400">#F4F4F4</p>
                </div>
                <div class="text-center">
                    <div class="aspect-square rounded-card bg-[#49516F] mb-2 shadow-sm"></div>
                    <p class="text-xs font-medium text-text">Azul Grafite</p>
                    <p class="text-xs text-gray-400">#49516F</p>
                </div>
                <div class="text-center">
                    <div class="aspect-square rounded-card bg-[#1F2937] mb-2 shadow-sm"></div>
                    <p class="text-xs font-medium text-text">Escuro Profundo</p>
                    <p class="text-xs text-gray-400">#1F2937</p>
                </div>
            </div>

            <div class="mt-4 p-4 bg-bg rounded-btn">
                <p class="text-xs text-gray-500"><strong>Justificativa:</strong> O coral (#FF477E) é a cor principal — transmite festejo, calor e energia, remetendo a celebrações e flores. O azul elétrico (#5465FF) como secundária contrasta e traz confiança e tecnologia (IA). Os neutros equilibram, criando hierarquia visual sem poluir o layout.</p>
            </div>
        </section>

        {{-- Section: Typography --}}
        <section class="mb-16">
            <h2 class="text-xl font-semibold text-text mb-2">Tipografia — Referência</h2>
            <p class="text-sm text-gray-500 mb-6">A Poppins foi escolhida por ser geométrica, moderna e highly legible, com personalidade amigável mas profissional.</p>

            <div class="bg-bg rounded-card p-8">
                <div class="space-y-4">
                    <div class="p-4 bg-white rounded-btn">
                        <p class="text-5xl font-bold text-text">Poppins Bold</p>
                        <p class="text-xs text-gray-400 mt-2">Headlines e títulos principais — impacto visual forte</p>
                    </div>
                    <div class="p-4 bg-white rounded-btn">
                        <p class="text-2xl font-semibold text-text">Poppins SemiBold</p>
                        <p class="text-xs text-gray-400 mt-2">Subtítulos e seções — hierarquia clara</p>
                    </div>
                    <div class="p-4 bg-white rounded-btn">
                        <p class="text-base text-text">Poppins Regular — Corpo de texto padrão para leitura confortável e fluida.</p>
                        <p class="text-xs text-gray-400 mt-2">Body text — legibilidade em parágrafos longos</p>
                    </div>
                    <div class="p-4 bg-white rounded-btn">
                        <p class="text-sm font-light text-gray-500">Poppins Light — Textos auxiliares e metadados</p>
                        <p class="text-xs text-gray-400 mt-2">Captions e labels — discretas mas presentes</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 bg-bg rounded-btn">
                <p class="text-xs text-gray-500"><strong>Justificativa:</strong> A Poppins é geométrica e uniforme, o que a torna versátil para interfaces. Seus caracteres arredondados transmitem acolhimento — essencial para uma plataforma de eventos. A família tipográfica completa (Light a Bold) permite criar hierarquia sem precisar de uma segunda fonte.</p>
            </div>
        </section>

        {{-- Section: Visual References --}}
        <section class="mb-16">
            <h2 class="text-xl font-semibold text-text mb-2">Referências de Interface</h2>
            <p class="text-sm text-gray-500 mb-6">Interfaces modernas de plataformas de marketplace e booking que inspiram o design da Celebra.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Reference 1 --}}
                <div class="bg-bg rounded-card p-6">
                    <div class="aspect-video rounded-btn bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center mb-4">
                        <div class="text-center">
                            <span class="text-4xl">🏠</span>
                            <p class="text-xs text-gray-400 mt-2">Airbnb-style cards</p>
                        </div>
                    </div>
                    <h3 class="font-semibold text-text text-sm">Cards com imagem dominante</h3>
                    <p class="text-xs text-gray-500 mt-1">Referência Airbnb — cards com imagem grande, informações essenciais (preço, avaliação, localização) num layout limpo. O foco visual é na fotografia, com texto mínimo.</p>
                </div>

                {{-- Reference 2 --}}
                <div class="bg-bg rounded-card p-6">
                    <div class="aspect-video rounded-btn bg-gradient-to-br from-secondary/20 to-secondary/5 flex items-center justify-center mb-4">
                        <div class="text-center">
                            <span class="text-4xl">💬</span>
                            <p class="text-xs text-gray-400 mt-2">Chat conversacional</p>
                        </div>
                    </div>
                    <h3 class="font-semibold text-text text-sm">Interface de chat assistida por IA</h3>
                    <p class="text-xs text-gray-500 mt-1">Referência ChatGPT/assistentes virtuais — bolhas de conversa, input com botão de envio, tipagem suave. O diálogo guiado reduz fricção e torna o planejamento mais humano.</p>
                </div>

                {{-- Reference 3 --}}
                <div class="bg-bg rounded-card p-6">
                    <div class="aspect-video rounded-btn bg-gradient-to-br from-success/20 to-success/5 flex items-center justify-center mb-4">
                        <div class="text-center">
                            <span class="text-4xl">📱</span>
                            <p class="text-xs text-gray-400 mt-2">Design system</p>
                        </div>
                    </div>
                    <h3 class="font-semibold text-text text-sm">Sistema de filtros com pills</h3>
                    <p class="text-xs text-gray-500 mt-1">Referência plataformas SaaS — filtros como botões pill, navegação por categorias com ícones emoji, busca em destaque. Experiência mobile-first com elementos clicáveis grandes.</p>
                </div>

                {{-- Reference 4 --}}
                <div class="bg-bg rounded-card p-6">
                    <div class="aspect-video rounded-btn bg-gradient-to-br from-warning/20 to-warning/5 flex items-center justify-center mb-4">
                        <div class="text-center">
                            <span class="text-4xl">✦</span>
                            <p class="text-xs text-gray-400 mt-2">Landing temática</p>
                        </div>
                    </div>
                    <h3 class="font-semibold text-text text-sm">Landing page personalizada</h3>
                    <p class="text-xs text-gray-500 mt-1">Referência Zankyou/Casar.com — landing pages de lista de presentes com identidade visual do evento, progresso de itens, e CTAs claros para convidados.</p>
                </div>
            </div>

            <div class="mt-4 p-4 bg-bg rounded-btn">
                <p class="text-xs text-gray-500"><strong>Justificativa:</strong> As referências foram escolhidas por representar diferentes aspectos da Celebra: cards de marketplace (Airbnb), chat com IA (ChatGPT), filtros modernos (plataformas SaaS) e landing pages de presentes (Zankyou). Cada uma traz boas práticas de UX que aplicamos na plataforma.</p>
            </div>
        </section>

        {{-- Section: Visual Tone --}}
        <section class="mb-16">
            <h2 class="text-xl font-semibold text-text mb-2">Tom Visual</h2>
            <p class="text-sm text-gray-500 mb-6">Palavras-chave que definem a personalidade visual da marca.</p>

            <div class="flex flex-wrap gap-3">
                @foreach(['Festivo', 'Acolhedor', 'Moderno', 'Confiável', 'Vibrante', 'Acessível', 'Elegante', 'Intuitivo'] as $word)
                    <span class="px-5 py-2.5 bg-bg rounded-pill text-sm font-medium text-text border border-gray-200">{{ $word }}</span>
                @endforeach
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-bg rounded-btn text-center">
                    <div class="w-16 h-16 mx-auto rounded-card bg-white shadow-sm mb-2"></div>
                    <p class="text-xs font-medium text-text">Border Radius: 12px</p>
                    <p class="text-xs text-gray-400">Cards arredondados — amigáveis</p>
                </div>
                <div class="p-4 bg-bg rounded-btn text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-white shadow-sm mb-2"></div>
                    <p class="text-xs font-medium text-text">Border Radius: 100px</p>
                    <p class="text-xs text-gray-400">Pills e botões — orgânicos</p>
                </div>
                <div class="p-4 bg-bg rounded-btn text-center">
                    <div class="w-16 h-16 mx-auto rounded-card bg-white shadow-lg mb-2"></div>
                    <p class="text-xs font-medium text-text">Sombras suaves</p>
                    <p class="text-xs text-gray-400">Elevação sutil — clean</p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.prototype>
