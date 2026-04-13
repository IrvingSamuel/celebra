<!DOCTYPE html>
<html lang="pt-BR" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Celebra' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet"/>
    @if(isset($theme))
    <style>
        :root {
            --ep-primary: {{ $theme['primary'] }};
            --ep-accent:  {{ $theme['accent'] }};
            --ep-bg:      {{ $theme['bg'] }};
            --ep-alt-bg:  {{ $theme['alt_bg'] ?? $theme['bg'] }};
            --ep-text:    {{ $theme['text'] }};
            --ep-font:    {{ $theme['font'] }};
        }
        body { background: var(--ep-bg); font-family: var(--ep-font); color: var(--ep-text); }

        /* Scrollbar temática */
        ::-webkit-scrollbar              { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track        { background: var(--ep-bg); }
        ::-webkit-scrollbar-thumb        { background: var(--ep-primary); border-radius: 999px; border: 2px solid var(--ep-bg); }
        ::-webkit-scrollbar-thumb:hover  { opacity: .85; }
        * { scrollbar-width: thin; scrollbar-color: var(--ep-primary) var(--ep-bg); }

        /* Scroll reveal */
        .ep-reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .55s ease, transform .55s ease;
        }
        .ep-reveal.ep-visible {
            opacity: 1;
            transform: translateY(0);
        }
        /* Hero is already visible on load */
        section:first-of-type { opacity: 1 !important; transform: none !important; }

        /* Anchor offset so button scroll lands cleanly */
        section[id] { scroll-margin-top: 16px; }

        /* Curved top on section immediately after hero */
        section.ep-hero + section {
            border-radius: 2rem 2rem 0 0;
            margin-top: -2rem;
            position: relative;
            z-index: 1;
        }


    </style>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen">
{{ $slot }}
<footer class="py-6 text-center text-sm" style="color: var(--ep-text, #6b7280); opacity: 0.6;">
    Criado com ❤️ na <a href="/" style="color: var(--ep-primary, #e11d48);">Celebra</a>
</footer>
<script>
(function () {
    const io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                e.target.classList.add('ep-visible');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    function observe() {
        document.querySelectorAll('section, .ep-card').forEach(function (el) {
            if (!el.classList.contains('ep-reveal')) {
                el.classList.add('ep-reveal');
                io.observe(el);
            }
        });
    }

    // Run on load and after Livewire updates
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', observe);
    } else {
        observe();
    }
    document.addEventListener('livewire:navigated', observe);
    document.addEventListener('livewire:update', observe);
})();
</script>
</body>
</html>
