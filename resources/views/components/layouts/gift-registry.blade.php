<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Lista de Presentes · Celebra' }}</title>

    {{-- OG Meta --}}
    @if(isset($ogImage))
    <meta property="og:image" content="{{ $ogImage }}">
    @endif
    @if(isset($ogDescription))
    <meta property="og:description" content="{{ $ogDescription }}">
    @endif
    <meta property="og:title" content="{{ $title ?? 'Lista de Presentes · Celebra' }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&family=playfair-display:400,600,700i" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .gr-serif { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>
<body class="bg-[#faf9f7] font-sans text-[#1c1917] antialiased min-h-screen">

{{ $slot }}

{{-- Branding footer --}}
<div class="py-8 text-center">
    <a href="/" class="inline-flex items-center gap-1.5 text-xs text-[#a8a29e] hover:text-primary transition">
        Feito com ❤️ na
        <span class="font-semibold text-primary">Celebra</span>
        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>
        </svg>
    </a>
</div>

</body>
</html>
