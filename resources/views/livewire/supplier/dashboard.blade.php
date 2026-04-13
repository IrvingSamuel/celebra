<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-text">Painel do Fornecedor</h1>
                    <p class="mt-1 text-gray-500">Olá, {{ auth()->user()->name }}! Gerencie seus serviços e solicitações.</p>
                </div>
                <a href="/fornecedor/perfil" class="hidden sm:inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-pill transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar Perfil
                </a>
            </div>

            {{-- Sem perfil: banner CTA --}}
            @if(! $profile)
                <div class="bg-warning/10 border border-warning/30 rounded-card p-6 mb-8 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-10 h-10 bg-warning/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-text">Seu perfil ainda não foi configurado</p>
                        <p class="text-sm text-gray-600 mt-0.5">Complete seu perfil para aparecer nos resultados de busca e receber solicitações.</p>
                    </div>
                    <a href="/fornecedor/perfil" class="flex-shrink-0 bg-warning hover:bg-yellow-500 text-white text-sm font-semibold px-5 py-2.5 rounded-pill transition">
                        Completar Perfil
                    </a>
                </div>
            @else
                {{-- Profile Card --}}
                <div class="bg-white rounded-card shadow-sm p-6 mb-8">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($profile->logo)
                                @php $logoSrc = str_starts_with($profile->logo, 'http') ? $profile->logo : \Illuminate\Support\Facades\Storage::disk('minio')->url($profile->logo); @endphp
                                <img src="{{ $logoSrc }}" alt="{{ $profile->company_name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl">🏪</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-text">{{ $profile->company_name }}</h2>
                            @if($profile->city)
                                <p class="text-sm text-gray-500">{{ $profile->city }}, {{ $profile->state }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 text-xs {{ $profile->verified ? 'text-success' : 'text-warning' }}">
                                    @if($profile->verified)
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Verificado
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-.586A1 1 0 0110 12v-2a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        Aguardando verificação
                                    @endif
                                </span>
                                @if($profile->category)
                                    <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full">{{ $profile->category->icon }} {{ $profile->category->name }}</span>
                                @endif
                            </div>
                        </div>
                        <a href="/fornecedor/perfil" class="sm:hidden text-sm text-primary hover:underline">Editar</a>
                    </div>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-card p-5 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Serviços ativos</p>
                    <p class="text-3xl font-bold text-primary">{{ $servicesCount }}</p>
                </div>
                <div class="bg-white rounded-card p-5 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pendentes</p>
                    <p class="text-3xl font-bold text-warning">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white rounded-card p-5 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Aceitas</p>
                    <p class="text-3xl font-bold text-success">{{ $acceptedCount }}</p>
                </div>
                <div class="bg-white rounded-card p-5 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Receita estimada</p>
                    <p class="text-2xl font-bold text-text">R$&nbsp;{{ number_format($estimatedRevenue, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <a href="/fornecedor/servicos" class="bg-white rounded-card p-6 shadow-sm text-center hover:shadow-md transition group">
                    <div class="w-12 h-12 mx-auto mb-3 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary/20 transition">
                        <span class="text-xl">📦</span>
                    </div>
                    <h3 class="font-semibold text-text mb-1">Meus Serviços</h3>
                    <p class="text-sm text-gray-500">Cadastre e gerencie os serviços que você oferece.</p>
                    <span class="mt-3 inline-block text-xs font-semibold text-primary">Gerenciar →</span>
                </a>

                <a href="/fornecedor/solicitacoes" class="bg-white rounded-card p-6 shadow-sm text-center hover:shadow-md transition group relative">
                    <div class="w-12 h-12 mx-auto mb-3 bg-secondary/10 rounded-full flex items-center justify-center group-hover:bg-secondary/20 transition">
                        <span class="text-xl">📩</span>
                    </div>
                    @if($pendingCount > 0)
                        <span class="absolute top-4 right-4 bg-warning text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">{{ $pendingCount }}</span>
                    @endif
                    <h3 class="font-semibold text-text mb-1">Solicitações</h3>
                    <p class="text-sm text-gray-500">Veja e responda pedidos de orçamento recebidos.</p>
                    <span class="mt-3 inline-block text-xs font-semibold text-primary">Ver solicitações →</span>
                </a>

                <a href="/fornecedor/perfil" class="bg-white rounded-card p-6 shadow-sm text-center hover:shadow-md transition group">
                    <div class="w-12 h-12 mx-auto mb-3 bg-success/10 rounded-full flex items-center justify-center group-hover:bg-success/20 transition">
                        <span class="text-xl">⚙️</span>
                    </div>
                    <h3 class="font-semibold text-text mb-1">Meu Perfil</h3>
                    <p class="text-sm text-gray-500">Atualize informações da sua empresa e contato.</p>
                    <span class="mt-3 inline-block text-xs font-semibold text-primary">Editar perfil →</span>
                </a>
            </div>

        </div>
    </section>
</div>
