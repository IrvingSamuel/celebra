<div>
    <section class="bg-bg min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <span class="font-semibold text-primary">Admin</span>
                        <span>/</span>
                        <span class="text-text">Fornecedores</span>
                    </div>
                    <h1 class="text-3xl font-bold text-text">Gerenciar Fornecedores</h1>
                    <p class="mt-1 text-gray-500">Aprove e gerencie os fornecedores da plataforma</p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-card p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</p>
                        <p class="text-3xl font-bold text-text">{{ $totalCount }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-card p-5 shadow-sm flex items-center gap-4 cursor-pointer hover:shadow-md transition" wire:click="$set('filter', 'verified')">
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Verificados</p>
                        <p class="text-3xl font-bold text-success">{{ $verifiedCount }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-card p-5 shadow-sm flex items-center gap-4 cursor-pointer hover:shadow-md transition" wire:click="$set('filter', 'pending')">
                    <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-warning" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-.586A1 1 0 0110 12v-2a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pendentes</p>
                        <p class="text-3xl font-bold text-warning">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Filters + Search --}}
            <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm p-4 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <div class="flex-1 flex items-center gap-2 bg-bg rounded-full px-4 py-2">
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.300ms="search"
                           type="text" placeholder="Buscar por nome, email ou cidade..."
                           class="w-full bg-transparent text-sm text-text placeholder-gray-400 border-none focus:outline-none focus:ring-0">
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="$set('filter', 'all')"
                            class="px-4 py-2 rounded-full text-xs font-medium transition {{ $filter === 'all' ? 'bg-secondary text-white' : 'bg-bg text-gray-600 hover:bg-gray-200' }}">
                        Todos
                    </button>
                    <button wire:click="$set('filter', 'verified')"
                            class="px-4 py-2 rounded-full text-xs font-medium transition {{ $filter === 'verified' ? 'bg-success text-white' : 'bg-bg text-gray-600 hover:bg-gray-200' }}">
                        Verificados
                    </button>
                    <button wire:click="$set('filter', 'pending')"
                            class="px-4 py-2 rounded-full text-xs font-medium transition {{ $filter === 'pending' ? 'bg-warning text-white' : 'bg-bg text-gray-600 hover:bg-gray-200' }}">
                        Pendentes
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm overflow-hidden">
                @if($suppliers->isEmpty())
                    <div class="py-16 text-center">
                        <span class="text-4xl block mb-3">🔍</span>
                        <p class="font-semibold text-text">Nenhum fornecedor encontrado</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tente ajustar os filtros de busca.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-700 bg-bg">
                                    <th class="text-left font-semibold text-gray-500 dark:text-gray-400 px-6 py-3 text-xs uppercase tracking-wide">Fornecedor</th>
                                    <th class="text-left font-semibold text-gray-500 dark:text-gray-400 px-4 py-3 text-xs uppercase tracking-wide hidden sm:table-cell">Cidade</th>
                                    <th class="text-center font-semibold text-gray-500 dark:text-gray-400 px-4 py-3 text-xs uppercase tracking-wide">GIGs</th>
                                    <th class="text-center font-semibold text-gray-500 dark:text-gray-400 px-4 py-3 text-xs uppercase tracking-wide">Status</th>
                                    <th class="text-right font-semibold text-gray-500 dark:text-gray-400 px-6 py-3 text-xs uppercase tracking-wide">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @foreach($suppliers as $supplier)
                                    <tr class="hover:bg-bg/50 dark:hover:bg-gray-700/50 transition group">
                                        {{-- Fornecedor --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0 overflow-hidden">
                                                    @if($supplier->logo)
                                                        @php $logoSrc = str_starts_with($supplier->logo, 'http') ? $supplier->logo : \Illuminate\Support\Facades\Storage::disk('minio')->url($supplier->logo); @endphp
                                                        <img src="{{ $logoSrc }}" alt="{{ $supplier->company_name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-lg">🏪</span>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-text truncate">{{ $supplier->company_name }}</p>
                                                    <p class="text-xs text-gray-400 truncate">{{ $supplier->user->email ?? '—' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- Cidade --}}
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                                            {{ $supplier->city ? $supplier->city . ($supplier->state ? ', ' . $supplier->state : '') : '—' }}
                                        </td>
                                        {{-- GIGs --}}
                                        <td class="px-4 py-4 text-center">
                                            <span class="text-sm font-semibold text-text">{{ $supplier->services_count }}</span>
                                        </td>
                                        {{-- Status --}}
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex flex-col items-center gap-1.5">
                                                @if($supplier->verified)
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2.5 py-1 rounded-full">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        Aprovado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-warning bg-warning/10 px-2.5 py-1 rounded-full">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-.586A1 1 0 0110 12v-2a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                        Pendente
                                                    </span>
                                                @endif
                                                @if($supplier->user?->email_verified_at)
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-info bg-info/10 px-2.5 py-1 rounded-full">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                                                        E-mail confirmado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        E-mail pendente
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        {{-- Ações --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="/admin/fornecedores/{{ $supplier->id }}/gigs"
                                                   class="inline-flex items-center gap-1.5 text-xs font-medium text-secondary hover:bg-secondary/10 px-3 py-1.5 rounded-lg transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                                    GIGs
                                                </a>
                                                @if($supplier->verified)
                                                    <button
                                                            x-on:click="SwalTheme.dangerDialog({ title: 'Revogar verificação?', text: 'O fornecedor {{ $supplier->company_name }} perderá o status verificado.', confirmButtonText: 'Revogar' }).then(r => r.isConfirmed && $wire.revoke({{ $supplier->id }}))"
                                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                        Revogar
                                                    </button>
                                                @else
                                                    @if($supplier->user?->email_verified_at)
                                                        <button
                                                                x-on:click="SwalTheme.successDialog({ title: 'Aprovar fornecedor?', text: '{{ $supplier->company_name }} será marcado como verificado e seus GIGs ficarão visíveis.', confirmButtonText: 'Aprovar' }).then(r => r.isConfirmed && $wire.approve({{ $supplier->id }}))"
                                                                class="inline-flex items-center gap-1.5 text-xs font-medium text-success hover:bg-success/10 px-3 py-1.5 rounded-lg transition">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Aprovar
                                                        </button>
                                                    @else
                                                        <span title="Aguardando confirmação de e-mail"
                                                              class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-300 cursor-not-allowed px-3 py-1.5 rounded-lg">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                            Aguardando e-mail
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($suppliers->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $suppliers->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </section>
</div>
