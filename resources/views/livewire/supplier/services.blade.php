<div>
    <section class="bg-bg min-h-screen py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="/dashboard" class="hover:text-primary transition">Painel</a>
                <span>/</span>
                <span class="text-text font-medium">Meus Serviços</span>
            </nav>

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-text">Meus Serviços</h1>
                @if($profile && ! $showForm)
                    <button wire:click="openCreate"
                            class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-pill transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Novo Serviço
                    </button>
                @endif
            </div>

            {{-- Sem perfil --}}
            @if(! $profile)
                <div class="bg-white rounded-card shadow-sm p-10 text-center">
                    <span class="text-4xl block mb-3">🏪</span>
                    <p class="font-semibold text-text mb-1">Configure seu perfil primeiro</p>
                    <p class="text-sm text-gray-500 mb-4">Para cadastrar serviços, você precisa ter um perfil de fornecedor ativo.</p>
                    <a href="/fornecedor/perfil" class="inline-block bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition">
                        Configurar Perfil
                    </a>
                </div>
            @else

                {{-- Formulário de criação / edição --}}
                @if($showForm)
                    <div class="bg-white rounded-card shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-semibold text-text mb-5">{{ $editingId ? 'Editar Serviço' : 'Novo Serviço' }}</h2>
                        <form wire:submit="save" class="space-y-4">

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Categoria do serviço <span class="text-red-500">*</span></label>
                                <select wire:model="categoryId"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition bg-white @error('categoryId') border-red-400 @enderror">
                                    <option value="">Selecione uma categoria...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Nome do serviço <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text" placeholder="Ex: Ensaio pré-wedding"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('name') border-red-400 @enderror">
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Descrição</label>
                                <textarea wire:model="description" rows="3" placeholder="Descreva o que está incluído..."
                                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition resize-none @error('description') border-red-400 @enderror"></textarea>
                                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Preço (R$) <span class="text-red-500">*</span></label>
                                <input wire:model="price" type="number" min="0" step="0.01" placeholder="0,00"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('price') border-red-400 @enderror">
                                @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Imagens --}}
                            <div>
                                <label class="block text-sm font-medium text-text mb-2">Imagens do serviço <span class="text-xs text-gray-400">(até 3 fotos, máx 5 MB cada)</span></label>

                                {{-- Imagens já salvas (edição) --}}
                                @if(count($existingImages) > 0)
                                    <div class="flex flex-wrap gap-3 mb-3">
                                        @foreach($existingImages as $idx => $imgPath)
                                            <div class="relative group w-24 h-24">
                                                <img src="{{ Storage::disk('minio')->url($imgPath) }}"
                                                     alt="Imagem {{ $idx + 1 }}"
                                                     class="w-full h-full object-cover rounded-xl border border-gray-200">
                                                <button type="button"
                                                        wire:click="removeExistingImage({{ $idx }})"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs leading-none opacity-0 group-hover:opacity-100 transition">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Slots de upload --}}
                                @php $slotsLeft = 3 - count($existingImages); @endphp
                                @if($slotsLeft > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        @foreach([1, 2, 3] as $slot)
                                            @if($slot <= $slotsLeft)
                                                @php $fieldName = 'imageFile' . $slot; @endphp
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Foto {{ $slot }}</label>
                                                    <div class="relative rounded-xl border-2 border-dashed border-gray-200 hover:border-primary/50 transition cursor-pointer overflow-hidden @error($fieldName) border-red-400 @enderror">
                                                        @if($this->$fieldName)
                                                            <img src="{{ $this->$fieldName->temporaryUrl() }}"
                                                                 class="w-full h-28 object-cover">
                                                        @else
                                                            <div class="flex flex-col items-center justify-center py-5 text-gray-400">
                                                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span class="text-xs">Clique para enviar</span>
                                                            </div>
                                                        @endif
                                                        <input wire:model="{{ $fieldName }}"
                                                               type="file" accept="image/*"
                                                               class="absolute inset-0 opacity-0 cursor-pointer">
                                                    </div>
                                                    <div wire:loading wire:target="{{ $fieldName }}" class="mt-1 text-xs text-primary">Carregando...</div>
                                                    @error($fieldName) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400">Limite de 3 imagens atingido. Remova uma para adicionar outra.</p>
                                @endif
                            </div>

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
                                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Salvar alterações' : 'Criar serviço' }}</span>
                                    <span wire:loading wire:target="save">Salvando...</span>
                                </button>
                                <button type="button" wire:click="cancelForm"
                                        class="text-sm text-gray-500 hover:text-primary transition">Cancelar</button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Lista de serviços --}}
                @if($services->isEmpty() && ! $showForm)
                    <div class="bg-white rounded-card shadow-sm p-10 text-center">
                        <span class="text-4xl block mb-3">📦</span>
                        <p class="font-semibold text-text mb-1">Nenhum serviço cadastrado ainda</p>
                        <p class="text-sm text-gray-500 mb-4">Adicione seus serviços para que os clientes possam encontrá-los.</p>
                        <button wire:click="openCreate"
                                class="inline-block bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition">
                            Criar primeiro serviço
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($services as $service)
                            <div class="bg-white rounded-card shadow-sm overflow-hidden {{ ! $service->active ? 'opacity-60' : '' }}">
                                @php $primaryImage = $service->primary_image; @endphp
                                @if($primaryImage)
                                    @if(str_starts_with($primaryImage, 'http'))
                                        <img src="{{ $primaryImage }}" alt="{{ $service->name }}" class="w-full h-40 object-cover">
                                    @else
                                        <img src="{{ Storage::disk('minio')->url($primaryImage) }}" alt="{{ $service->name }}" class="w-full h-40 object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-40 bg-bg flex items-center justify-center text-4xl">📸</div>
                                @endif
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-semibold text-text text-sm leading-tight">{{ $service->name }}</h3>
                                        @if(! $service->active)
                                            <span class="flex-shrink-0 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                                        @endif
                                    </div>
                                    @if($service->category)
                                        <p class="text-xs text-primary/80 font-medium mt-1">{{ $service->category->icon ? $service->category->icon . ' ' : '' }}{{ $service->category->name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $service->description }}</p>
                                    <p class="text-xs text-gray-400 mt-2">a partir de</p>
                                    <p class="text-primary font-bold">R$ {{ number_format($service->price, 0, ',', '.') }}</p>

                                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                                        <button wire:click="openEdit({{ $service->id }})"
                                                class="flex-1 text-xs font-medium text-primary hover:bg-primary/5 py-1.5 rounded-lg transition">
                                            Editar
                                        </button>
                                        <button
                                                x-on:click="SwalTheme.dangerDialog({ title: 'Desativar serviço?', text: 'O serviço ficará invisível para os clientes, mas pode ser reativado depois.', confirmButtonText: 'Desativar', confirmButtonColor: '#F59E0B' }).then(r => r.isConfirmed && $wire.delete({{ $service->id }}))"
                                                class="flex-1 text-xs font-medium text-red-400 hover:bg-red-50 py-1.5 rounded-lg transition">
                                            Desativar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            @endif

        </div>
    </section>
</div>
