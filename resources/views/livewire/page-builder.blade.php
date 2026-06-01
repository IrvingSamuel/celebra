<div class="flex flex-col h-screen overflow-hidden">

    {{-- TOP BAR --}}
    <header class="h-14 bg-white border-b border-gray-200 flex items-center px-4 gap-3 shrink-0 z-30 shadow-sm">
        <a href="/dashboard" class="text-gray-400 hover:text-gray-700 transition mr-1" title="Voltar ao painel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <span class="text-sm font-semibold text-gray-700 truncate max-w-xs">{{ $event->title }}</span>
        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full shrink-0">Editor</span>

        <div class="flex-1"></div>

        {{-- Save feedback --}}
        @if($saved)
            <span class="text-xs text-green-600 flex items-center gap-1 animate-pulse">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Salvo
            </span>
        @endif

        {{-- Preview --}}
        @if($published && auth()->user()->slug)
            <a href="/{{ auth()->user()->slug }}/{{ $event->slug }}" target="_blank"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver página
            </a>
        @endif

        {{-- Publish toggle --}}
        <button wire:click="togglePublish"
                class="flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-full transition
                    {{ $published ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            @if($published)
                <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> Publicado
            @else
                <span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span> Rascunho
            @endif
        </button>

        <button wire:click="save"
                class="bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2 rounded-full transition">
            <span wire:loading.remove wire:target="save">Salvar</span>
            <span wire:loading wire:target="save">Salvando...</span>
        </button>
    </header>

    {{-- MAIN AREA --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- LEFT SIDEBAR --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col overflow-y-auto shrink-0">

            {{-- Theme --}}
            <div class="p-4 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Tema</p>
                <div class="grid grid-cols-5 gap-1.5 mb-3">
                    @foreach($themes as $key => $t)
                        <button wire:click="$set('theme', '{{ $key }}')" wire:key="theme-{{ $key }}"
                                title="{{ $t['name'] }}"
                                class="w-9 h-9 rounded-full border-2 transition
                                    {{ $theme === $key ? 'border-gray-800 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background: {{ $t['primary'] }}">
                        </button>
                    @endforeach
                </div>
                <label class="block text-xs text-gray-500 mb-1">Cor personalizada</label>
                <div class="flex items-center gap-2">
                    <input wire:model.live="primaryColor" type="color" value="{{ $primaryColor ?: '#e11d48' }}"
                           class="w-8 h-8 rounded cursor-pointer border border-gray-200">
                    <span class="text-xs text-gray-400 font-mono">{{ $primaryColor ?: 'padrão' }}</span>
                    @if($primaryColor)
                        <button wire:click="$set('primaryColor', '')" class="text-xs text-gray-400 hover:text-red-500">✕</button>
                    @endif
                </div>
            </div>

            {{-- Block palette --}}
            <div class="p-4 flex-1">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Adicionar Bloco</p>
                <div class="space-y-1.5">

                    @foreach([
                        ['hero',      '',  'Hero / Banner',       'Cabeçalho com foto e título'],
                        ['message',   '',  'Mensagem',            'Texto livre com imagem opcional'],
                        ['countdown', '',  'Contagem Regressiva', 'Contador automático da data'],
                        ['gallery',   '',  'Galeria',             'Grade de fotos'],
                        ['gifts',     '',  'Lista de Presentes',  'Exibe sua lista de presentes'],
                        ['schedule',  '',  'Programação',         'Linha do tempo do evento'],
                        ['location',  '',  'Local',               'Endereço e link para o mapa'],
                        ['rsvp',      '',  'Confirmação RSVP',    'Formulário de presença'],
                    ] as [$btype, $icon, $label, $desc])
                        <button wire:click="addBlock('{{ $btype }}')"
                                class="w-full text-left flex items-start gap-2.5 px-3 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition group">
                            <span class="text-xl mt-0.5">{{ $icon }}</span>
                            <div>
                                <p class="text-sm font-medium leading-tight">{{ $label }}</p>
                                <p class="text-xs text-gray-400 group-hover:text-rose-400">{{ $desc }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- CANVAS --}}
        <div class="flex-1 overflow-y-auto bg-gray-100 p-6" id="canvas-wrapper">

            @if(empty($blocks))
                <div class="max-w-xl mx-auto bg-white rounded-2xl p-12 text-center shadow-sm border-2 border-dashed border-gray-200">
                    <span class="text-5xl block mb-4">🎨</span>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Sua página está em branco</h3>
                    <p class="text-sm text-gray-400">Clique em um bloco na barra lateral para começar a construir.</p>
                </div>
            @else
                <div id="blocks-canvas" class="max-w-2xl mx-auto space-y-3">
                    @foreach($blocks as $block)
                        <div wire:key="block-{{ $block['id'] }}" data-block-id="{{ $block['id'] }}"
                             class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden
                                {{ ($block['visible'] ?? true) ? '' : 'opacity-50' }}
                                {{ $editingBlockId === $block['id'] ? 'ring-2 ring-rose-400' : '' }}">

                            {{-- Block header bar --}}
                            <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                                {{-- Drag handle --}}
                                <span class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 transition text-lg leading-none select-none" title="Arrastar">⠿</span>

                                {{-- Type label --}}
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    @switch($block['type'])
                                        @case('hero')       Hero @break
                                        @case('message')    Mensagem @break
                                        @case('countdown')  Contagem @break
                                        @case('gallery')    Galeria @break
                                        @case('gifts')      Presentes @break
                                        @case('schedule')   Programação @break
                                        @case('location')   Local @break
                                        @case('rsvp')       RSVP @break
                                    @endswitch
                                </span>

                                <div class="flex-1"></div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1">
                                    <button wire:click="moveBlock('{{ $block['id'] }}', 'up')" title="Mover para cima"
                                            class="w-7 h-7 rounded-lg hover:bg-gray-200 transition flex items-center justify-center text-gray-400 hover:text-gray-600 text-xs">▲</button>
                                    <button wire:click="moveBlock('{{ $block['id'] }}', 'down')" title="Mover para baixo"
                                            class="w-7 h-7 rounded-lg hover:bg-gray-200 transition flex items-center justify-center text-gray-400 hover:text-gray-600 text-xs">▼</button>
                                    <button wire:click="toggleBlockVisibility('{{ $block['id'] }}')" title="{{ ($block['visible'] ?? true) ? 'Ocultar' : 'Mostrar' }}"
                                            class="w-7 h-7 rounded-lg hover:bg-gray-200 transition flex items-center justify-center text-gray-400 hover:text-gray-600">
                                        @if($block['visible'] ?? true)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        @endif
                                    </button>
                                    <button wire:click="openEdit('{{ $block['id'] }}')"
                                            class="w-7 h-7 rounded-lg hover:bg-rose-50 transition flex items-center justify-center text-gray-400 hover:text-rose-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button"
                                            x-on:click="SwalTheme.dangerDialog({ title: 'Remover este bloco?', text: 'Esta ação não pode ser desfeita.', confirmButtonText: 'Remover' }).then(r => r.isConfirmed && $wire.removeBlock('{{ $block['id'] }}'))"
                                            class="w-7 h-7 rounded-lg hover:bg-red-50 transition flex items-center justify-center text-gray-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Block mini-preview --}}
                            <div class="px-4 py-3 text-xs text-gray-500">
                                @switch($block['type'])
                                    @case('hero')
                                        <p class="font-semibold text-gray-800 truncate">{{ $block['content']['title'] ?? '—' }}</p>
                                        <p class="text-gray-400 truncate">{{ $block['content']['subtitle'] ?? '' }}</p>
                                        @break
                                    @case('message')
                                        <p class="font-semibold text-gray-800 truncate">{{ $block['content']['heading'] ?? '—' }}</p>
                                        <p class="text-gray-400 line-clamp-2">{{ $block['content']['text'] ?? '' }}</p>
                                        @break
                                    @case('countdown')
                                        <p class="font-semibold text-gray-800">{{ $block['content']['heading'] ?? 'Contagem Regressiva' }}</p>
                                        <p class="text-gray-400">Calculada automaticamente pela data do evento</p>
                                        @break
                                    @case('gifts')
                                        <p class="font-semibold text-gray-800">{{ $block['content']['heading'] ?? 'Lista de Presentes' }}</p>
                                        <p class="text-gray-400">{{ $block['content']['subtitle'] ?? '' }}</p>
                                        @break
                                    @case('schedule')
                                        <p class="font-semibold text-gray-800">{{ $block['content']['heading'] ?? 'Programação' }}</p>
                                        <p class="text-gray-400">{{ count($block['content']['items'] ?? []) }} itens</p>
                                        @break
                                    @case('location')
                                        <p class="font-semibold text-gray-800">{{ $block['content']['venue_name'] ?: 'Local do Evento' }}</p>
                                        <p class="text-gray-400 truncate">{{ $block['content']['address'] ?? '' }}</p>
                                        @break
                                    @case('rsvp')
                                        <p class="font-semibold text-gray-800">{{ $block['content']['heading'] ?? 'Confirmação de Presença' }}</p>
                                        <p class="text-gray-400">{{ $block['content']['subtitle'] ?? '' }}</p>
                                        @break
                                    @case('gallery')
                                        <p class="font-semibold text-gray-800">{{ $block['content']['heading'] ?? 'Galeria' }}</p>
                                        <p class="text-gray-400">{{ count($block['content']['images'] ?? []) }} imagens</p>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- RIGHT EDIT DRAWER --}}
        @if($editingBlockId)
            <div class="w-96 bg-white border-l border-gray-200 flex flex-col overflow-hidden shrink-0 shadow-xl z-20">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-800 text-sm">Editar bloco</h3>
                    <button wire:click="closeEdit" class="text-gray-400 hover:text-gray-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">
                    @php
                        $editingBlock = collect($blocks)->firstWhere('id', $editingBlockId);
                        $btype = $editingBlock['type'] ?? '';
                    @endphp

                    {{-- ── HERO ─────────────────────────── --}}
                    @if($btype === 'hero')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título principal</label>
                            <input wire:model="editContent.title" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Subtítulo</label>
                            <input wire:model="editContent.subtitle" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                        </div>
                        <div x-data="{ imgMode: 'url' }">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Imagem de fundo</label>
                            <div class="flex gap-1 mb-2">
                                <button type="button" @click="imgMode='url'"
                                        :class="imgMode==='url' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Colar URL</button>
                                <button type="button" @click="imgMode='upload'"
                                        :class="imgMode==='upload' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Upload</button>
                            </div>
                            <div x-show="imgMode==='url'">
                                <input wire:model="editContent.bg_image" type="url" placeholder="https://..."
                                       class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                            </div>
                            <div x-show="imgMode==='upload'" class="space-y-2">
                                <input wire:model="heroUpload" type="file" accept="image/*"
                                       class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                                <div wire:loading wire:target="heroUpload" class="text-xs text-gray-400">Carregando...</div>
                                <button wire:click="uploadHeroBg" wire:loading.attr="disabled" wire:target="uploadHeroBg"
                                        class="w-full text-xs bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-lg py-1.5 transition">Enviar imagem</button>
                                @error('heroUpload') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            @if($editContent['bg_image'] ?? '')
                                <img src="{{ $editContent['bg_image'] }}" alt="preview" class="mt-2 w-full h-24 object-cover rounded-lg">
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Modo da imagem</label>
                            <select wire:model="editContent.bg_fit"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                                <option value="cover">Cover (preencher e cortar)</option>
                                <option value="contain">Contain (mostrar inteira)</option>
                                <option value="fill">Fill (esticar)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Âncora da imagem</label>
                            <select wire:model="editContent.bg_anchor"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                                <option value="top-left">Topo esquerdo</option>
                                <option value="top">Topo centro</option>
                                <option value="top-right">Topo direito</option>
                                <option value="left">Centro esquerdo</option>
                                <option value="center">Centro</option>
                                <option value="right">Centro direito</option>
                                <option value="bottom-left">Base esquerda</option>
                                <option value="bottom">Base centro</option>
                                <option value="bottom-right">Base direita</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Opacidade do overlay: {{ $editContent['overlay_opacity'] ?? 50 }}%</label>
                            <input wire:model="editContent.overlay_opacity" type="range" min="0" max="90" step="5" class="w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Texto do botão CTA</label>
                            <input wire:model="editContent.button_text" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">CTA destino</label>
                            <select wire:model="editContent.button_target"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-rose-300 focus:border-rose-400 outline-none">
                                <option value="gifts">Lista de Presentes</option>
                                <option value="rsvp">Confirmação de Presença</option>
                                <option value="">Nenhum</option>
                            </select>
                        </div>
                    @endif

                    {{-- ── MESSAGE ──────────────────────── --}}
                    @if($btype === 'message')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Texto</label>
                            <textarea wire:model="editContent.text" rows="5"
                                      class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none resize-none"></textarea>
                        </div>
                        <div x-data="{ imgMode: 'url' }">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Imagem (opcional)</label>
                            <div class="flex gap-1 mb-2">
                                <button type="button" @click="imgMode='url'"
                                        :class="imgMode==='url' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Colar URL</button>
                                <button type="button" @click="imgMode='upload'"
                                        :class="imgMode==='upload' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Upload</button>
                            </div>
                            <div x-show="imgMode==='url'">
                                <input wire:model="editContent.image" type="url" placeholder="https://..."
                                       class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                            </div>
                            <div x-show="imgMode==='upload'" class="space-y-2">
                                <input wire:model="messageUpload" type="file" accept="image/*"
                                       class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                                <div wire:loading wire:target="messageUpload" class="text-xs text-gray-400">Carregando...</div>
                                <button wire:click="uploadMessageImage" wire:loading.attr="disabled" wire:target="uploadMessageImage"
                                        class="w-full text-xs bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-lg py-1.5 transition">Enviar imagem</button>
                                @error('messageUpload') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            @if($editContent['image'] ?? '')
                                <img src="{{ $editContent['image'] }}" alt="preview" class="mt-2 w-full h-24 object-cover rounded-lg">
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Lado da imagem</label>
                            <select wire:model="editContent.image_side"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-rose-300 outline-none">
                                <option value="right">Direita</option>
                                <option value="left">Esquerda</option>
                                <option value="top">Acima</option>
                                <option value="">Sem imagem</option>
                            </select>
                        </div>
                    @endif

                    {{-- ── COUNTDOWN ────────────────────── --}}
                    @if($btype === 'countdown')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nota abaixo do contador</label>
                            <input wire:model="editContent.note_text" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <p class="text-xs text-gray-400 bg-gray-50 rounded-lg p-3">A data usada é a data do evento: <strong>{{ $event->event_date?->format('d/m/Y') ?? 'não definida' }}</strong>. Edite-a no planejador.</p>
                    @endif

                    {{-- ── GIFTS ────────────────────────── --}}
                    @if($btype === 'gifts')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título da seção</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Subtítulo</label>
                            <input wire:model="editContent.subtitle" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Colunas</label>
                            <select wire:model="editContent.columns"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-rose-300 outline-none">
                                <option value="2">2 colunas</option>
                                <option value="3">3 colunas</option>
                                <option value="4">4 colunas</option>
                            </select>
                        </div>
                    @endif

                    {{-- ── SCHEDULE ─────────────────────── --}}
                    @if($btype === 'schedule')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Itens da programação</label>
                            @foreach($editContent['items'] ?? [] as $idx => $itm)
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-lg">{{ $itm['icon'] ?? '' }}</span>
                                    <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">{{ $itm['time'] }}</span>
                                    <span class="text-sm flex-1 truncate">{{ $itm['label'] }}</span>
                                    <button wire:click="removeScheduleItem({{ $idx }})" class="text-red-400 hover:text-red-600 transition text-xs">✕</button>
                                </div>
                            @endforeach
                            <div class="bg-gray-50 rounded-xl p-3 space-y-2 mt-2">
                                <p class="text-xs font-medium text-gray-400 mb-2">Novo item:</p>
                                <div class="flex gap-2">
                                    <input wire:model="newScheduleTime" type="text" placeholder="18:00"
                                           class="w-20 rounded-lg border border-gray-200 px-2 py-1.5 text-xs focus:ring-1 focus:ring-rose-300 outline-none">
                                    <input wire:model="newScheduleLabel" type="text" placeholder="Cerimônia"
                                           class="flex-1 rounded-lg border border-gray-200 px-2 py-1.5 text-xs focus:ring-1 focus:ring-rose-300 outline-none">
                                    <input wire:model="newScheduleIcon" type="text" placeholder=""
                                           class="w-12 rounded-lg border border-gray-200 px-2 py-1.5 text-xs text-center focus:ring-1 focus:ring-rose-300 outline-none">
                                </div>
                                <button wire:click="addScheduleItem"
                                        class="w-full text-xs bg-rose-600 hover:bg-rose-700 text-white rounded-lg py-1.5 transition">
                                    + Adicionar
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- ── LOCATION ─────────────────────── --}}
                    @if($btype === 'location')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nome do local</label>
                            <input wire:model="editContent.venue_name" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Endereço completo</label>
                            <textarea wire:model="editContent.address" rows="2"
                                      class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Link Google Maps</label>
                            <input wire:model="editContent.maps_url" type="url" placeholder="https://maps.google.com/..."
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div x-data="{ imgMode: 'url' }">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Foto do local (opcional)</label>
                            <div class="flex gap-1 mb-2">
                                <button type="button" @click="imgMode='url'"
                                        :class="imgMode==='url' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Colar URL</button>
                                <button type="button" @click="imgMode='upload'"
                                        :class="imgMode==='upload' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Upload</button>
                            </div>
                            <div x-show="imgMode==='url'">
                                <input wire:model="editContent.image" type="url" placeholder="https://..."
                                       class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                            </div>
                            <div x-show="imgMode==='upload'" class="space-y-2">
                                <input wire:model="locationUpload" type="file" accept="image/*"
                                       class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                                <div wire:loading wire:target="locationUpload" class="text-xs text-gray-400">Carregando...</div>
                                <button wire:click="uploadLocationImage" wire:loading.attr="disabled" wire:target="uploadLocationImage"
                                        class="w-full text-xs bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-lg py-1.5 transition">Enviar imagem</button>
                                @error('locationUpload') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            @if($editContent['image'] ?? '')
                                <img src="{{ $editContent['image'] }}" alt="preview" class="mt-2 w-full h-24 object-cover rounded-lg">
                            @endif
                        </div>
                    @endif

                    {{-- ── RSVP ─────────────────────────── --}}
                    @if($btype === 'rsvp')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Subtítulo</label>
                            <input wire:model="editContent.subtitle" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Máx. acompanhantes</label>
                            <input wire:model="editContent.max_per_person" type="number" min="1" max="20"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                    @endif

                    {{-- ── GALLERY ──────────────────────── --}}
                    @if($btype === 'gallery')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Título</label>
                            <input wire:model="editContent.heading" type="text"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-300 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Colunas</label>
                            <select wire:model="editContent.columns"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-rose-300 outline-none">
                                <option value="2">2 colunas</option>
                                <option value="3">3 colunas</option>
                                <option value="4">4 colunas</option>
                            </select>
                        </div>
                        <div x-data="{ imgMode: 'url' }">
                            <label class="block text-xs font-medium text-gray-500 mb-2">Imagens</label>
                            @if(!empty($editContent['images']))
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    @foreach($editContent['images'] as $gIdx => $gUrl)
                                        <div class="relative group">
                                            <img src="{{ $gUrl }}" alt="Foto" class="w-full h-16 object-cover rounded-lg">
                                            <button wire:click="removeGalleryImage({{ $gIdx }})"
                                                    class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex gap-1 mb-2">
                                <button type="button" @click="imgMode='url'"
                                        :class="imgMode==='url' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Colar URL</button>
                                <button type="button" @click="imgMode='upload'"
                                        :class="imgMode==='upload' ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-gray-100 text-gray-500'"
                                        class="text-xs px-3 py-1 rounded-lg transition">Upload</button>
                            </div>
                            <div x-show="imgMode==='url'" class="flex gap-2">
                                <input wire:model="newGalleryUrl" type="url" placeholder="https://..."
                                       class="flex-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs focus:ring-1 focus:ring-rose-300 outline-none">
                                <button wire:click="addGalleryImage"
                                        class="text-xs bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg transition">+</button>
                            </div>
                            <div x-show="imgMode==='upload'" class="space-y-2">
                                <input wire:model="galleryUpload" type="file" accept="image/*"
                                       class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                                <div wire:loading wire:target="galleryUpload" class="text-xs text-gray-400">Carregando...</div>
                                <button wire:click="uploadGalleryImage" wire:loading.attr="disabled" wire:target="uploadGalleryImage"
                                        class="w-full text-xs bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-lg py-1.5 transition">+ Adicionar à galeria</button>
                                @error('galleryUpload') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Drawer footer --}}
                <div class="p-4 border-t border-gray-100 flex gap-2 shrink-0 bg-white">
                    <button wire:click="saveBlockEdit"
                            wire:loading.attr="disabled"
                            wire:target="heroUpload,messageUpload,locationUpload,galleryUpload"
                            class="flex-1 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                        Aplicar
                    </button>
                    <button wire:click="closeEdit"
                            class="px-4 text-sm text-gray-500 hover:text-gray-700 transition">
                        Cancelar
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- SortableJS drag-and-drop initialization --}}
<script>
    let _sortableInstance = null;

    function initBuilderSortable() {
        const canvas = document.getElementById('blocks-canvas');
        if (!canvas) return;
        if (_sortableInstance) {
            try { _sortableInstance.destroy(); } catch(e) {}
            _sortableInstance = null;
        }
        _sortableInstance = Sortable.create(canvas, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'opacity-40',
            onEnd: function () {
                const order = Array.from(canvas.children)
                    .map(el => el.dataset.blockId)
                    .filter(Boolean);
                const wireId = canvas.closest('[wire\\:id]')?.getAttribute('wire:id');
                if (wireId && order.length) {
                    Livewire.find(wireId).call('reorderBlocks', order);
                }
            }
        });
    }

    document.addEventListener('livewire:initialized', initBuilderSortable);

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(() => {
            requestAnimationFrame(initBuilderSortable);
        });
    });
</script>
