<x-layouts.prototype title="Celebra - Cover">
    <div class="max-w-4xl mx-auto py-16 px-8">
        {{-- Cover Header --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-3 mb-6">
                <span class="text-5xl font-bold text-primary">Celebra</span>
                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>
                </svg>
            </div>
            <p class="text-xl text-gray-500 font-light">Plataforma de Planejamento de Eventos</p>
        </div>

        {{-- Concept --}}
        <section class="mb-12 bg-bg rounded-card p-8">
            <h2 class="text-2xl font-bold text-text mb-4">📌 Conceito</h2>
            <p class="text-text leading-relaxed">
                A <strong>Celebra</strong> é uma plataforma web que conecta pessoas que desejam organizar eventos (casamentos, formaturas, aniversários, festas de 15 anos, confraternizações e conferências) com os melhores fornecedores e espaços. A plataforma conta com uma assistente de IA chamada <strong>Celi</strong>, que realiza planejamento conversacional — entendendo as necessidades do cliente e recomendando espaços e serviços de forma personalizada.
            </p>
        </section>

        {{-- Problem --}}
        <section class="mb-12 bg-bg rounded-card p-8">
            <h2 class="text-2xl font-bold text-text mb-4">🎯 Problema que resolve</h2>
            <p class="text-text leading-relaxed">
                Planejar eventos é estressante e fragmentado: é necessário pesquisar em dezenas de sites, comparar preços manualmente, e coordenar múltiplos fornecedores. A Celebra centraliza tudo em um só lugar, com recomendações inteligentes baseadas no tipo de evento, orçamento e preferências do usuário. A assistente Celi elimina a complexidade, guiando o cliente em uma conversa natural até as melhores opções.
            </p>
        </section>

        {{-- Target Audience --}}
        <section class="mb-12 bg-bg rounded-card p-8">
            <h2 class="text-2xl font-bold text-text mb-4">👥 Público-alvo</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-text mb-2">Clientes</h3>
                    <p class="text-text/80 text-sm leading-relaxed">Jovens adultos de 22 a 40 anos que estão planejando eventos especiais — casamentos, formaturas, festas de aniversário, eventos corporativos. Valorizam praticidade, bom gosto e custo-benefício.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-text mb-2">Fornecedores</h3>
                    <p class="text-text/80 text-sm leading-relaxed">Pequenos e médios empresários de serviços para eventos — fotógrafos, buffets, DJs, decoradores, espaços para festas — que buscam mais visibilidade e clientes qualificados.</p>
                </div>
            </div>
        </section>

        {{-- Event Types --}}
        <section class="mb-12 bg-bg rounded-card p-8">
            <h2 class="text-2xl font-bold text-text mb-4">🎊 Tipos de Evento</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach([
                    ['icon' => '', 'name' => 'Casamento'],
                    ['icon' => '', 'name' => 'Festa de 15 Anos'],
                    ['icon' => '', 'name' => 'Aniversário'],
                    ['icon' => '', 'name' => 'Formatura'],
                    ['icon' => '', 'name' => 'Confraternização'],
                    ['icon' => '', 'name' => 'Conferência'],
                ] as $event)
                    <div class="flex items-center gap-3 p-4 bg-white rounded-btn">
                        <span class="text-2xl">{{ $event['icon'] }}</span>
                        <span class="text-sm font-medium text-text">{{ $event['name'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Team --}}
        <section class="bg-bg rounded-card p-8">
            <h2 class="text-2xl font-bold text-text mb-4">👨‍💻 Integrantes</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach(['Irving Samuel', 'George Luis', 'João Gabryel', 'Paulo Macêdo'] as $member)
                    <div class="flex items-center gap-3 p-4 bg-white rounded-btn">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-primary">{{ collect(explode(' ', $member))->map(fn($n) => mb_substr($n, 0, 1))->implode('') }}</span>
                        </div>
                        <span class="text-sm font-medium text-text">{{ $member }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-6 text-center">Disciplina de Interface Humano-Computador (IHC) — 2026</p>
        </section>
    </div>
</x-layouts.prototype>
