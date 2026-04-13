<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Celebra - Protótipo IHC' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-text antialiased">
    {{-- Sidebar Navigation --}}
    <div class="flex min-h-screen">
        <aside class="w-64 bg-dark text-white flex-shrink-0 sticky top-0 h-screen overflow-y-auto">
            <div class="p-6">
                <a href="/prototype" class="flex items-center gap-2 mb-8">
                    <span class="text-xl font-bold text-primary">Celebra</span>
                    <span class="text-xs bg-secondary/20 text-secondary-light px-2 py-0.5 rounded-full">IHC</span>
                </a>

                <nav class="space-y-1">
                    @php
                        $currentPath = request()->path();
                        $pages = [
                            ['url' => '/prototype', 'icon' => '📋', 'label' => 'Cover', 'paths' => ['prototype', 'prototype/cover']],
                            ['url' => '/prototype/moodboard', 'icon' => '🎨', 'label' => 'Moodboard', 'paths' => ['prototype/moodboard']],
                            ['url' => '/prototype/style-guide', 'icon' => '📐', 'label' => 'Style Guide', 'paths' => ['prototype/style-guide']],
                            ['url' => '/prototype/components', 'icon' => '🧩', 'label' => 'Component Library', 'paths' => ['prototype/components']],
                            ['url' => '/prototype/prototype', 'icon' => '📱', 'label' => 'Prototype', 'paths' => ['prototype/prototype']],
                        ];
                    @endphp

                    @foreach($pages as $page)
                        <a href="{{ $page['url'] }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-btn text-sm font-medium transition {{ in_array($currentPath, $page['paths']) ? 'bg-white/10 text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                            <span>{{ $page['icon'] }}</span>
                            {{ $page['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="mt-8 pt-8 border-t border-white/10">
                    <a href="/" class="flex items-center gap-2 px-4 py-3 rounded-btn text-sm font-medium text-white/40 hover:text-white/80 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Voltar ao site
                    </a>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
