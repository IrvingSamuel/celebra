<div>
    <section class="bg-bg min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="/dashboard" class="hover:text-primary transition">Painel</a>
                <span>/</span>
                <span class="text-text font-medium">Meu Perfil</span>
            </nav>

            <h1 class="text-2xl font-bold text-text mb-6">Editar Perfil do Fornecedor</h1>

            @if($saved)
                <div class="bg-success/10 border border-success/30 text-success text-sm font-medium px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Perfil atualizado com sucesso!
                </div>
            @endif

            <form wire:submit="save" class="bg-white rounded-card shadow-sm p-8 space-y-6">

                {{-- Company Name --}}
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">Nome da empresa <span class="text-red-500">*</span></label>
                    <input wire:model="company_name" type="text" placeholder="Ex: Studio Momento Perfeito"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('company_name') border-red-400 @enderror">
                    @error('company_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">Descrição</label>
                    <textarea wire:model="description" rows="4" placeholder="Conte um pouco sobre sua empresa, experiência e diferenciais..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition resize-none @error('description') border-red-400 @enderror"></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">Telefone / WhatsApp</label>
                    <input wire:model="phone" type="text" placeholder="(11) 99999-9999"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('phone') border-red-400 @enderror">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- City / State --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-text mb-1.5">Cidade</label>
                        <input wire:model="city" type="text" placeholder="São Paulo"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('city') border-red-400 @enderror">
                        @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">UF</label>
                        <input wire:model="state" type="text" placeholder="SP" maxlength="2"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition uppercase @error('state') border-red-400 @enderror">
                        @error('state') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Logo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">Logotipo da empresa</label>
                    <div class="flex items-center gap-4">
                        {{-- Preview --}}
                        @if($logoFile)
                            <img src="{{ $logoFile->temporaryUrl() }}" alt="Preview" class="w-16 h-16 object-cover rounded-full border border-gray-200 flex-shrink-0">
                        @elseif($logo)
                            @php $logoSrc = str_starts_with($logo, 'http') ? $logo : \Illuminate\Support\Facades\Storage::disk('minio')->url($logo); @endphp
                            <img src="{{ $logoSrc }}" alt="Logo atual" class="w-16 h-16 object-cover rounded-full border border-gray-200 flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-full border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 flex-shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        {{-- File input --}}
                        <div class="flex-1">
                            <label class="relative flex items-center gap-2 cursor-pointer">
                                <div class="flex items-center gap-2 border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-600 hover:border-primary hover:text-primary transition @error('logoFile') border-red-400 @enderror">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    {{ $logoFile ? $logoFile->getClientOriginalName() : 'Escolher arquivo...' }}
                                </div>
                                <input wire:model="logoFile" type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            </label>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG ou WEBP • Máx. 2 MB</p>
                            <div wire:loading wire:target="logoFile" class="text-xs text-primary mt-1">Carregando...</div>
                        </div>
                    </div>
                    @error('logoFile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-between pt-2">
                    <a href="/dashboard" class="text-sm text-gray-500 hover:text-primary transition">← Voltar ao painel</a>
                    <button type="submit"
                            class="bg-primary hover:bg-primary-dark text-white font-semibold text-sm px-8 py-2.5 rounded-pill transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">Salvar alterações</span>
                        <span wire:loading wire:target="save">Salvando...</span>
                    </button>
                </div>
            </form>

        </div>
    </section>
</div>
