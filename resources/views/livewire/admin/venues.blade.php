<div>
    <section class="bg-bg min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <a href="/admin/fornecedores" class="font-semibold text-primary hover:underline">Admin</a>
                        <span>/</span>
                        <span class="text-text">Espaços</span>
                    </div>
                    <h1 class="text-3xl font-bold text-text">Gerenciar Espaços</h1>
                    <p class="mt-1 text-gray-500">Cadastre e gerencie os espaços para eventos da plataforma</p>
                </div>
                @if(! $showForm)
                    <button wire:click="openCreate"
                            class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-pill transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Novo Espaço
                    </button>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-card p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</p>
                        <p class="text-3xl font-bold text-text">{{ $totalCount }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-card p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-success" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Ativos</p>
                        <p class="text-3xl font-bold text-success">{{ $activeCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            @if($showForm)
                <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm p-6 mb-8">
                    <h3 class="text-lg font-semibold text-text mb-6">{{ $editingId ? 'Editar Espaço' : 'Novo Espaço' }}</h3>
                    <form wire:submit="save" class="space-y-5">

                        {{-- Nome --}}
                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Nome do espaço <span class="text-red-500">*</span></label>
                            <input wire:model="name" type="text" placeholder="Ex: Haras Santa Maria"
                                   class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('name') border-red-400 @enderror">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tipo + Capacidade + Preço --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Tipo <span class="text-red-500">*</span></label>
                                <select wire:model="type"
                                        class="w-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('type') border-red-400 @enderror">
                                    <option value="indoor">🏛️ Indoor (fechado)</option>
                                    <option value="outdoor">🌿 Outdoor (aberto)</option>
                                    <option value="ambos">✨ Ambos (indoor + outdoor)</option>
                                </select>
                                @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Capacidade (pessoas)</label>
                                <input wire:model="capacity" type="number" min="1" max="99999" placeholder="Ex: 200"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('capacity') border-red-400 @enderror">
                                @error('capacity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Preço base (R$)</label>
                                <input wire:model="price" type="number" min="0" step="0.01" placeholder="0,00"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('price') border-red-400 @enderror">
                                @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Localização --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-text mb-1.5">Cidade <span class="text-red-500">*</span></label>
                                <input wire:model="city" type="text" placeholder="Ex: São Paulo"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('city') border-red-400 @enderror">
                                @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">UF <span class="text-red-500">*</span></label>
                                <input wire:model="state" type="text" maxlength="2" placeholder="SP"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm uppercase focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('state') border-red-400 @enderror">
                                @error('state') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Endereço</label>
                            <input wire:model="address" type="text" placeholder="Rua, número, bairro..."
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('address') border-red-400 @enderror">
                            @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Descrição --}}
                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Descrição</label>
                            <textarea wire:model="description" rows="4" placeholder="Descreva o espaço, diferenciais, estrutura disponível..."
                                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition resize-none @error('description') border-red-400 @enderror"></textarea>
                            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Imagem principal --}}
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Imagem principal</label>
                            @if($existingImage)
                                <div class="flex items-center gap-3 mb-3">
                                    @php $src = str_starts_with($existingImage, 'http') ? $existingImage : \Illuminate\Support\Facades\Storage::disk('minio')->url($existingImage); @endphp
                                    <img src="{{ $src }}" alt="Imagem atual" class="w-24 h-24 object-cover rounded-xl border border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Imagem atual</p>
                                        <p class="text-xs text-gray-400">Envie uma nova para substituir</p>
                                    </div>
                                </div>
                            @endif
                            <div class="relative rounded-xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition cursor-pointer overflow-hidden max-w-xs @error('imageFile') border-red-400 @enderror">
                                @if($imageFile)
                                    <img src="{{ $imageFile->temporaryUrl() }}" class="w-full h-36 object-cover">
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs">Clique para enviar</span>
                                    </div>
                                @endif
                                <input wire:model="imageFile" type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <div wire:loading wire:target="imageFile" class="mt-1 text-xs text-primary">Carregando...</div>
                            @error('imageFile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Galeria --}}
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Galeria <span class="text-xs text-gray-400">(até 3 fotos adicionais, máx 5 MB cada)</span></label>

                            @if(count($existingGallery) > 0)
                                <div class="flex flex-wrap gap-3 mb-3">
                                    @foreach($existingGallery as $idx => $imgPath)
                                        <div class="relative group w-24 h-24">
                                            @php $gSrc = str_starts_with($imgPath, 'http') ? $imgPath : \Illuminate\Support\Facades\Storage::disk('minio')->url($imgPath); @endphp
                                            <img src="{{ $gSrc }}" alt="Galeria {{ $idx + 1 }}" class="w-full h-full object-cover rounded-xl border border-gray-200">
                                            <button type="button" wire:click="removeExistingGalleryImage({{ $idx }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @php $slotsLeft = 3 - count($existingGallery); @endphp
                            @if($slotsLeft > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 max-w-lg">
                                    @foreach([1, 2, 3] as $slot)
                                        @if($slot <= $slotsLeft)
                                            @php $fieldName = 'galleryFile' . $slot; @endphp
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1">Foto {{ $slot }}</label>
                                                <div class="relative rounded-xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition cursor-pointer overflow-hidden @error($fieldName) border-red-400 @enderror">
                                                    @if($this->$fieldName)
                                                        <img src="{{ $this->$fieldName->temporaryUrl() }}" class="w-full h-24 object-cover">
                                                    @else
                                                        <div class="flex flex-col items-center justify-center py-5 text-gray-400">
                                                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <span class="text-xs">Enviar</span>
                                                        </div>
                                                    @endif
                                                    <input wire:model="{{ $fieldName }}" type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                                </div>
                                                <div wire:loading wire:target="{{ $fieldName }}" class="mt-1 text-xs text-primary">Carregando...</div>
                                                @error($fieldName) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Ativo --}}
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input wire:model="active" type="checkbox" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:bg-primary after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                            <span class="text-sm text-text">Ativo (visível para clientes)</span>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit"
                                    class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition">
                                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Salvar alterações' : 'Cadastrar espaço' }}</span>
                                <span wire:loading wire:target="save">Salvando...</span>
                            </button>
                            <button type="button" wire:click="cancelForm" class="text-sm text-gray-500 hover:text-primary transition">Cancelar</button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Search --}}
            @if(! $showForm)
                <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm p-4 mb-6">
                    <div class="flex items-center gap-2 bg-bg rounded-full px-4 py-2">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search"
                               type="text" placeholder="Buscar por nome ou cidade..."
                               class="w-full bg-transparent text-sm text-text placeholder-gray-400 border-none focus:outline-none focus:ring-0">
                    </div>
                </div>
            @endif

            {{-- Table --}}
            @if(! $showForm)
                <div class="bg-white dark:bg-gray-800 rounded-card shadow-sm overflow-hidden">
                    @if($venues->isEmpty())
                        <div class="py-16 text-center">
                            <span class="text-4xl block mb-3">🏙️</span>
                            <p class="font-semibold text-text">Nenhum espaço encontrado</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4">Cadastre o primeiro espaço para eventos da plataforma.</p>
                            <button wire:click="openCreate"
                                    class="inline-block bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition">
                                Cadastrar espaço
                            </button>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-bg">
                                        <th class="px-5 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Espaço</th>
                                        <th class="px-5 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Tipo</th>
                                        <th class="px-5 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Capacidade</th>
                                        <th class="px-5 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Preço base</th>
                                        <th class="px-5 py-3 text-left font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Status</th>
                                        <th class="px-5 py-3 text-right font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                    @foreach($venues as $venue)
                                        <tr class="hover:bg-bg/50 dark:hover:bg-gray-700/50 transition">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if($venue->image)
                                                        @php $imgSrc = str_starts_with($venue->image, 'http') ? $venue->image : \Illuminate\Support\Facades\Storage::disk('minio')->url($venue->image); @endphp
                                                        <img src="{{ $imgSrc }}" alt="{{ $venue->name }}" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                                    @else
                                                        <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-lg shrink-0">🏛️</div>
                                                    @endif
                                                    <div>
                                                        <p class="font-semibold text-text">{{ $venue->name }}</p>
                                                        <p class="text-xs text-gray-400">{{ $venue->city }}, {{ $venue->state }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                @if($venue->type === 'indoor')
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full">🏛️ Indoor</span>
                                                @elseif($venue->type === 'outdoor')
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2.5 py-1 rounded-full">🌿 Outdoor</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-purple-700 bg-purple-50 px-2.5 py-1 rounded-full">✨ Ambos</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-text">
                                                @if($venue->capacity)
                                                    <span class="font-medium">{{ number_format($venue->capacity, 0, ',', '.') }}</span>
                                                    <span class="text-xs text-gray-400"> pessoas</span>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-text">
                                                @if($venue->price)
                                                    R$ {{ number_format($venue->price, 0, ',', '.') }}
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <button wire:click="toggleActive({{ $venue->id }})"
                                                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full transition
                                                            {{ $venue->active
                                                                ? 'text-success bg-success/10 hover:bg-success/20'
                                                                : 'text-gray-500 bg-gray-100 hover:bg-gray-200' }}">
                                                    @if($venue->active)
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        Ativo
                                                    @else
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-3.707-9.293a1 1 0 011.414-1.414L10 9.586l2.293-2.293a1 1 0 011.414 1.414L11.414 11l2.293 2.293a1 1 0 01-1.414 1.414L10 12.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 11 6.293 8.707z" clip-rule="evenodd"/></svg>
                                                        Inativo
                                                    @endif
                                                </button>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button wire:click="openEdit({{ $venue->id }})"
                                                            class="text-xs font-medium text-primary hover:underline">Editar</button>
                                                    <button wire:click="delete({{ $venue->id }})"
                                                            wire:confirm="Tem certeza que deseja excluir '{{ addslashes($venue->name) }}'? Esta ação não pode ser desfeita."
                                                            class="text-xs font-medium text-red-500 hover:underline">Excluir</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($venues->hasPages())
                            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                                {{ $venues->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            @endif

        </div>
    </section>
</div>
