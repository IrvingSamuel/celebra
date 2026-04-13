<x-layouts.prototype title="Celebra - Style Guide">
    <div class="max-w-6xl mx-auto py-16 px-8">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-text">📐 Style Guide</h1>
            <p class="mt-2 text-gray-500">Fundamentos visuais formalizados para o design system da Celebra</p>
        </div>

        {{-- COLOR PALETTE --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-6">Paleta de Cores</h2>

            {{-- Primary --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Primária</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <div class="h-24 rounded-card bg-primary-light mb-2"></div>
                        <p class="text-sm font-medium text-text">primary-light</p>
                        <p class="text-xs text-gray-400">#FF7AA2</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-primary mb-2"></div>
                        <p class="text-sm font-medium text-text">primary</p>
                        <p class="text-xs text-gray-400">#FF477E</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-primary-dark mb-2"></div>
                        <p class="text-sm font-medium text-text">primary-dark</p>
                        <p class="text-xs text-gray-400">#B73058</p>
                    </div>
                </div>
            </div>

            {{-- Secondary --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Secundária</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <div class="h-24 rounded-card bg-secondary-light mb-2"></div>
                        <p class="text-sm font-medium text-text">secondary-light</p>
                        <p class="text-xs text-gray-400">#8B96FF</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-secondary mb-2"></div>
                        <p class="text-sm font-medium text-text">secondary</p>
                        <p class="text-xs text-gray-400">#5465FF</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-secondary-dark mb-2"></div>
                        <p class="text-sm font-medium text-text">secondary-dark</p>
                        <p class="text-xs text-gray-400">#3A48B0</p>
                    </div>
                </div>
            </div>

            {{-- Neutrals --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Neutros</h3>
                <div class="grid grid-cols-5 gap-4">
                    <div>
                        <div class="h-24 rounded-card bg-white mb-2 border border-gray-200"></div>
                        <p class="text-sm font-medium text-text">white</p>
                        <p class="text-xs text-gray-400">#FFFFFF</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-bg mb-2"></div>
                        <p class="text-sm font-medium text-text">bg</p>
                        <p class="text-xs text-gray-400">#F4F4F4</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-text-light mb-2"></div>
                        <p class="text-sm font-medium text-text">text-light</p>
                        <p class="text-xs text-gray-400">#6B7280</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-text mb-2"></div>
                        <p class="text-sm font-medium text-text">text</p>
                        <p class="text-xs text-gray-400">#49516F</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-dark mb-2"></div>
                        <p class="text-sm font-medium text-text">dark</p>
                        <p class="text-xs text-gray-400">#1F2937</p>
                    </div>
                </div>
            </div>

            {{-- Feedback --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Feedback</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <div class="h-24 rounded-card bg-success mb-2"></div>
                        <p class="text-sm font-medium text-text">success</p>
                        <p class="text-xs text-gray-400">#10B981</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-warning mb-2"></div>
                        <p class="text-sm font-medium text-text">warning</p>
                        <p class="text-xs text-gray-400">#F59E0B</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-error mb-2"></div>
                        <p class="text-sm font-medium text-text">error</p>
                        <p class="text-xs text-gray-400">#EF4444</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-card bg-info mb-2"></div>
                        <p class="text-sm font-medium text-text">info</p>
                        <p class="text-xs text-gray-400">#3B82F6</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- TYPOGRAPHY --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-6">Escala Tipográfica</h2>
            <p class="text-sm text-gray-500 mb-6">Fonte: <strong>Poppins</strong> (Google Fonts) — Hierarquia definida com sizes consistentes.</p>

            <div class="bg-bg rounded-card p-8 space-y-6">
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">H1</span>
                    <span class="text-5xl font-bold text-text leading-tight">Heading 1</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">48px / Bold (700)</span>
                </div>
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">H2</span>
                    <span class="text-4xl font-bold text-text">Heading 2</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">36px / Bold (700)</span>
                </div>
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">H3</span>
                    <span class="text-3xl font-semibold text-text">Heading 3</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">28px / SemiBold (600)</span>
                </div>
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">H4</span>
                    <span class="text-2xl font-semibold text-text">Heading 4</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">24px / SemiBold (600)</span>
                </div>
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">Body</span>
                    <span class="text-base text-text">Body text — The quick brown fox jumps over the lazy dog.</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">16px / Regular (400)</span>
                </div>
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">Caption</span>
                    <span class="text-sm text-text-light">Caption — Texto auxiliar e metadados de interface</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">14px / Regular (400)</span>
                </div>
                <div class="flex items-baseline gap-6 p-4 bg-white rounded-btn">
                    <span class="text-xs font-mono text-gray-400 w-16 shrink-0">Label</span>
                    <span class="text-xs font-medium text-text">LABEL — TAGS E BADGES</span>
                    <span class="text-xs text-gray-400 ml-auto shrink-0">12px / Medium (500)</span>
                </div>
            </div>
        </section>

        {{-- SPACING --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-6">Sistema de Espaçamentos</h2>
            <p class="text-sm text-gray-500 mb-6">Base em múltiplos de <strong>4px</strong> para consistência em todo o layout.</p>

            <div class="bg-bg rounded-card p-8">
                <div class="space-y-3">
                    @foreach([
                        ['token' => '4', 'px' => '4px', 'rem' => '0.25rem'],
                        ['token' => '8', 'px' => '8px', 'rem' => '0.5rem'],
                        ['token' => '12', 'px' => '12px', 'rem' => '0.75rem'],
                        ['token' => '16', 'px' => '16px', 'rem' => '1rem'],
                        ['token' => '20', 'px' => '20px', 'rem' => '1.25rem'],
                        ['token' => '24', 'px' => '24px', 'rem' => '1.5rem'],
                        ['token' => '32', 'px' => '32px', 'rem' => '2rem'],
                        ['token' => '40', 'px' => '40px', 'rem' => '2.5rem'],
                        ['token' => '48', 'px' => '48px', 'rem' => '3rem'],
                        ['token' => '64', 'px' => '64px', 'rem' => '4rem'],
                        ['token' => '80', 'px' => '80px', 'rem' => '5rem'],
                    ] as $space)
                        <div class="flex items-center gap-4 p-2 bg-white rounded-btn">
                            <span class="text-xs font-mono text-gray-400 w-12">{{ $space['token'] }}</span>
                            <div class="bg-secondary/20 rounded" style="width: {{ $space['px'] }}; height: 24px;"></div>
                            <span class="text-xs text-gray-500">{{ $space['px'] }} / {{ $space['rem'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- GRID --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-6">Grid</h2>
            <p class="text-sm text-gray-500 mb-6">12 colunas, gutter de 24px (gap-6), max-width 1280px (max-w-7xl).</p>

            <div class="bg-bg rounded-card p-8">
                <div class="grid grid-cols-12 gap-2">
                    @for($i = 0; $i < 12; $i++)
                        <div class="h-16 bg-secondary/20 rounded-btn flex items-center justify-center">
                            <span class="text-xs font-mono text-secondary">{{ $i + 1 }}</span>
                        </div>
                    @endfor
                </div>
                <div class="mt-4 grid grid-cols-12 gap-2">
                    <div class="col-span-4 h-12 bg-primary/20 rounded-btn flex items-center justify-center">
                        <span class="text-xs text-primary">4 cols</span>
                    </div>
                    <div class="col-span-8 h-12 bg-primary/20 rounded-btn flex items-center justify-center">
                        <span class="text-xs text-primary">8 cols</span>
                    </div>
                </div>
                <div class="mt-2 grid grid-cols-12 gap-2">
                    <div class="col-span-3 h-12 bg-secondary/20 rounded-btn flex items-center justify-center">
                        <span class="text-xs text-secondary">3 cols</span>
                    </div>
                    <div class="col-span-6 h-12 bg-secondary/20 rounded-btn flex items-center justify-center">
                        <span class="text-xs text-secondary">6 cols</span>
                    </div>
                    <div class="col-span-3 h-12 bg-secondary/20 rounded-btn flex items-center justify-center">
                        <span class="text-xs text-secondary">3 cols</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ICONOGRAPHY --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-text mb-6">Iconografia</h2>
            <p class="text-sm text-gray-500 mb-6">Estilo: <strong>Outline</strong> — Base 24×24, stroke 2px. Complementado por emojis para categorias.</p>

            <div class="bg-bg rounded-card p-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Ícones SVG (Heroicons Outline)</h3>
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-4">
                    @foreach([
                        'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                        'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
                        'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                        'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                        'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                    ] as $path)
                        <div class="w-12 h-12 bg-white rounded-btn flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-text" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                            </svg>
                        </div>
                    @endforeach
                </div>

                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4 mt-8">Emojis (Categorias)</h3>
                <div class="grid grid-cols-6 sm:grid-cols-12 gap-4">
                    @foreach(['💍', '👑', '🎂', '🎓', '🎉', '🎤', '💒', '📸', '🎵', '💐', '👗', '🎁'] as $emoji)
                        <div class="w-12 h-12 bg-white rounded-btn flex items-center justify-center shadow-sm">
                            <span class="text-xl">{{ $emoji }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- VISUAL TONE --}}
        <section>
            <h2 class="text-2xl font-bold text-text mb-6">Tom Visual</h2>
            <p class="text-sm text-gray-500 mb-6">Definições formais que estabelecem a identidade do design system.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-bg rounded-card p-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Border Radius</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-primary/20 rounded-btn"></div>
                            <div>
                                <p class="text-sm font-medium text-text">Button: 8px</p>
                                <p class="text-xs text-gray-400">--radius-btn</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-secondary/20 rounded-card"></div>
                            <div>
                                <p class="text-sm font-medium text-text">Card: 12px</p>
                                <p class="text-xs text-gray-400">--radius-card</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-10 bg-success/20 rounded-pill"></div>
                            <div>
                                <p class="text-sm font-medium text-text">Pill: 100px</p>
                                <p class="text-xs text-gray-400">--radius-pill</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-bg rounded-card p-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Sombras</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-card shadow-sm"></div>
                            <div>
                                <p class="text-sm font-medium text-text">Small</p>
                                <p class="text-xs text-gray-400">shadow-sm</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-card shadow-card"></div>
                            <div>
                                <p class="text-sm font-medium text-text">Card</p>
                                <p class="text-xs text-gray-400">--shadow-card</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-card shadow-lg"></div>
                            <div>
                                <p class="text-sm font-medium text-text">Large</p>
                                <p class="text-xs text-gray-400">shadow-lg</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-bg rounded-card p-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Transições</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-white rounded-btn">
                            <p class="text-sm font-medium text-text">Duração</p>
                            <p class="text-xs text-gray-400">300ms ease</p>
                        </div>
                        <div class="p-4 bg-white rounded-btn">
                            <p class="text-sm font-medium text-text">Hover: scale</p>
                            <p class="text-xs text-gray-400">transform: scale(1.05)</p>
                        </div>
                        <div class="p-4 bg-white rounded-btn">
                            <p class="text-sm font-medium text-text">Hover: color</p>
                            <p class="text-xs text-gray-400">primary → primary-dark</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.prototype>
