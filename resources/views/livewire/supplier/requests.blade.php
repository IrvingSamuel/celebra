<div>
    <section class="bg-bg min-h-screen py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="/dashboard" class="hover:text-primary transition">Painel</a>
                <span>/</span>
                <span class="text-text font-medium">Solicitações</span>
            </nav>

            <h1 class="text-2xl font-bold text-text mb-6">Solicitações de Orçamento</h1>

            {{-- Sem perfil --}}
            @if(! isset($counts['all']))
                <div class="bg-white rounded-card shadow-sm p-10 text-center">
                    <span class="text-4xl block mb-3">🏪</span>
                    <p class="font-semibold text-text mb-1">Configure seu perfil primeiro</p>
                    <a href="/fornecedor/perfil" class="inline-block bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition mt-3">
                        Configurar Perfil
                    </a>
                </div>
            @else

                {{-- Filter Tabs --}}
                <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
                    @foreach(['all' => 'Todas', 'pending' => 'Pendentes', 'accepted' => 'Aceitas', 'declined' => 'Recusadas'] as $key => $label)
                        <button wire:click="$set('filterStatus', '{{ $key }}')"
                                class="flex-shrink-0 px-4 py-2 rounded-pill text-sm font-medium transition
                                       {{ $filterStatus === $key ? 'bg-primary text-white' : 'bg-white text-text hover:bg-bg border border-gray-200' }}">
                            {{ $label }}
                            @if(isset($counts[$key]))
                                <span class="ml-1 text-xs {{ $filterStatus === $key ? 'text-white/80' : 'text-gray-400' }}">{{ $counts[$key] }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Empty state --}}
                @if($requests->isEmpty())
                    <div class="bg-white rounded-card shadow-sm p-10 text-center">
                        <span class="text-4xl block mb-3">📭</span>
                        <p class="font-semibold text-text mb-1">Nenhuma solicitação
                            @if($filterStatus !== 'all') com este status @endif
                        </p>
                        <p class="text-sm text-gray-500">Os pedidos de orçamento dos clientes aparecerão aqui.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($requests as $req)
                            @php
                                $statusConfig = match($req->status) {
                                    'accepted' => ['label' => 'Aceita', 'class' => 'bg-success/10 text-success'],
                                    'declined' => ['label' => 'Recusada', 'class' => 'bg-red-100 text-red-500'],
                                    default     => ['label' => 'Pendente', 'class' => 'bg-warning/10 text-warning'],
                                };
                            @endphp
                            <div class="bg-white rounded-card shadow-sm p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $statusConfig['class'] }}">
                                                {{ $statusConfig['label'] }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}</span>
                                        </div>
                                        <h3 class="font-semibold text-text">{{ $req->event_title }}</h3>
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            Serviço: <span class="text-text font-medium">{{ $req->service_name }}</span>
                                        </p>
                                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-gray-500">
                                            <span>👤 {{ $req->client_name }}</span>
                                            @if($req->event_date)
                                                <span>📅 {{ \Carbon\Carbon::parse($req->event_date)->format('d/m/Y') }}</span>
                                            @endif
                                            @if($req->guest_count)
                                                <span>👥 {{ $req->guest_count }} convidados</span>
                                            @endif
                                            @if($req->budget)
                                                <span>💰 Orçamento total: R$ {{ number_format($req->budget, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        @if($req->notes)
                                            <p class="mt-2 text-xs text-gray-600 bg-bg rounded-lg px-3 py-2">{{ $req->notes }}</p>
                                        @endif
                                        @if($req->status === 'accepted' && $req->price_agreed)
                                            <p class="mt-2 text-sm font-semibold text-success">
                                                Valor acordado: R$ {{ number_format($req->price_agreed, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    @if($req->status === 'pending')
                                        <div class="flex flex-col gap-2 flex-shrink-0">
                                            @if($acceptingId === $req->id)
                                                {{-- Accept form inline --}}
                                                <div class="flex flex-col gap-2 min-w-[180px]">
                                                    <input wire:model="priceAgreed" type="number" min="0" step="0.01"
                                                           placeholder="Valor acordado (R$)"
                                                           class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                                                    @error('priceAgreed') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                                    <button wire:click="confirmAccept"
                                                            class="bg-success hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                                                        Confirmar aceite
                                                    </button>
                                                    <button wire:click="cancelAccept"
                                                            class="text-xs text-gray-400 hover:text-text transition">Cancelar</button>
                                                </div>
                                            @else
                                                <button wire:click="openAccept({{ $req->id }})"
                                                        class="bg-success hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                                                    ✓ Aceitar
                                                </button>
                                                <button
                                                        x-on:click="SwalTheme.dangerDialog({ title: 'Recusar solicitação?', text: 'O cliente será notificado que sua solicitação foi recusada.', confirmButtonText: 'Recusar' }).then(r => r.isConfirmed && $wire.decline({{ $req->id }}))"
                                                        class="border border-red-200 text-red-400 hover:bg-red-50 text-xs font-semibold px-4 py-2 rounded-xl transition">
                                                    ✕ Recusar
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
    </section>
</div>
