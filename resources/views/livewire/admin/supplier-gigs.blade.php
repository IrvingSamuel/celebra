<div>
    <section class="bg-bg min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="/admin/fornecedores" class="hover:text-primary transition">Admin</a>
                <span>/</span>
                <a href="/admin/fornecedores" class="hover:text-primary transition">Fornecedores</a>
                <span>/</span>
                <span class="text-text font-medium">{{ $supplier->company_name }}</span>
            </nav>

            {{-- Supplier info bar --}}
            <div class="bg-white rounded-card shadow-sm p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center shrink-0 overflow-hidden">
                    @if($supplier->logo)
                        @php $logoSrc = str_starts_with($supplier->logo, 'http') ? $supplier->logo : \Illuminate\Support\Facades\Storage::disk('minio')->url($supplier->logo); @endphp
                        <img src="{{ $logoSrc }}" alt="{{ $supplier->company_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl">🏪</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl font-bold text-text">{{ $supplier->company_name }}</h1>
                        @if($supplier->verified)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verificado
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-warning bg-warning/10 px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-.586A1 1 0 0110 12v-2a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Aguardando verificação
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $supplier->user->email ?? '' }}
                        @if($supplier->city) · {{ $supplier->city }}{{ $supplier->state ? ', ' . $supplier->state : '' }} @endif
                        @if($supplier->category) · {{ $supplier->category->icon }} {{ $supplier->category->name }} @endif
                    </p>
                    @if($supplier->description)
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $supplier->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @if($supplier->verified)
                        {{-- No action needed, already verified --}}
                    @else
                        <a href="/admin/fornecedores"
                           onclick="if(!confirm('Aprovar e verificar {{ addslashes($supplier->company_name) }}?')) return false; fetch('/admin/api/approve/{{ $supplier->id }}', {method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector(\'meta[name=csrf-token]\').content}}).then(()=>location.reload())"
                           class="hidden">noop</a>
                    @endif
                </div>
            </div>

            {{-- GIGs header --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text">GIGs do Fornecedor <span class="text-base font-normal text-gray-400">({{ $services->count() }})</span></h2>
                @if(! $showForm)
                    <button wire:click="openCreate"
                            class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-pill transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Novo GIG
                    </button>
                @endif
            </div>

            {{-- Form --}}
            @if($showForm)
                <div class="bg-white rounded-card shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-text mb-5">{{ $editingId ? 'Editar GIG' : 'Novo GIG' }}</h3>
                    <form wire:submit="save" class="space-y-4">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Categoria <span class="text-red-500">*</span></label>
                                <select wire:model="categoryId"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition bg-white @error('categoryId') border-red-400 @enderror">
                                    <option value="">Selecione...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-text mb-1.5">Preço (R$) <span class="text-red-500">*</span></label>
                                <input wire:model="price" type="number" min="0" step="0.01" placeholder="0,00"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('price') border-red-400 @enderror">
                                @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Nome do GIG <span class="text-red-500">*</span></label>
                            <input wire:model="name" type="text" placeholder="Ex: Cobertura fotográfica completa"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition @error('name') border-red-400 @enderror">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Descrição</label>
                            <textarea wire:model="description" rows="3" placeholder="Descreva o serviço..."
                                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition resize-none @error('description') border-red-400 @enderror"></textarea>
                            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Imagens --}}
                        <div>
                            <label class="block text-sm font-medium text-text mb-2">Imagens <span class="text-xs text-gray-400">(até 3, máx 5 MB cada)</span></label>

                            @if(count($existingImages) > 0)
                                <div class="flex flex-wrap gap-3 mb-3">
                                    @foreach($existingImages as $idx => $imgPath)
                                        <div class="relative group w-24 h-24">
                                            @php $src = str_starts_with($imgPath, 'http') ? $imgPath : \Illuminate\Support\Facades\Storage::disk('minio')->url($imgPath); @endphp
                                            <img src="{{ $src }}" alt="Imagem {{ $idx + 1 }}" class="w-full h-full object-cover rounded-xl border border-gray-200">
                                            <button type="button" wire:click="removeExistingImage({{ $idx }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

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
                                                        <img src="{{ $this->$fieldName->temporaryUrl() }}" class="w-full h-28 object-cover">
                                                    @else
                                                        <div class="flex flex-col items-center justify-center py-5 text-gray-400">
                                                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <span class="text-xs">Clique para enviar</span>
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
                                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Salvar alterações' : 'Criar GIG' }}</span>
                                <span wire:loading wire:target="save">Salvando...</span>
                            </button>
                            <button type="button" wire:click="cancelForm" class="text-sm text-gray-500 hover:text-primary transition">Cancelar</button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- GIGs grid --}}
            @if($services->isEmpty() && ! $showForm)
                <div class="bg-white rounded-card shadow-sm p-12 text-center">
                    <span class="text-4xl block mb-3">📦</span>
                    <p class="font-semibold text-text mb-1">Nenhum GIG cadastrado</p>
                    <p class="text-sm text-gray-500 mb-4">Este fornecedor ainda não tem serviços cadastrados.</p>
                    <button wire:click="openCreate"
                            class="inline-block bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition">
                        Adicionar GIG
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($services as $service)
                        <div class="bg-white rounded-card shadow-sm overflow-hidden {{ ! $service->active ? 'opacity-60' : '' }}">
                            @php $primaryImage = $service->primary_image; @endphp
                            @if($primaryImage)
                                @php $imgSrc = str_starts_with($primaryImage, 'http') ? $primaryImage : \Illuminate\Support\Facades\Storage::disk('minio')->url($primaryImage); @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $service->name }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-bg flex items-center justify-center text-4xl">📸</div>
                            @endif
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="font-semibold text-text text-sm leading-tight">{{ $service->name }}</h4>
                                    @if(! $service->active)
                                        <span class="flex-shrink-0 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                                    @else
                                        <span class="flex-shrink-0 text-xs bg-success/10 text-success px-2 py-0.5 rounded-full">Ativo</span>
                                    @endif
                                </div>
                                @if($service->category)
                                    <p class="text-xs text-primary/80 font-medium">{{ $service->category->icon ? $service->category->icon . ' ' : '' }}{{ $service->category->name }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $service->description }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-primary font-bold text-sm">R$ {{ number_format($service->price, 0, ',', '.') }}</p>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-xs text-gray-500">{{ number_format($service->rating, 1) }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                                    <button wire:click="openEdit({{ $service->id }})"
                                            class="flex-1 text-xs font-medium text-secondary hover:bg-secondary/10 py-1.5 rounded-lg transition">
                                        Editar
                                    </button>
                                    <button wire:click="toggleActive({{ $service->id }})"
                                            class="flex-1 text-xs font-medium {{ $service->active ? 'text-warning hover:bg-warning/10' : 'text-success hover:bg-success/10' }} py-1.5 rounded-lg transition">
                                        {{ $service->active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                    <button
                                            x-on:click="SwalTheme.dangerDialog({ title: 'Excluir GIG permanentemente?', text: 'Esta ação não pode ser desfeita. O GIG será removido do sistema.', confirmButtonText: 'Excluir' }).then(r => r.isConfirmed && $wire.delete({{ $service->id }}))"
                                            class="flex-1 text-xs font-medium text-red-400 hover:bg-red-50 py-1.5 rounded-lg transition">
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
</div>
