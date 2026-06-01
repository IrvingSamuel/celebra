<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Celebra - Planeje Seus Eventos' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg font-sans text-text antialiased">
    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-primary">Celebra</span>
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>
                    </svg>
                </a>

                {{-- Navigation Links --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm font-medium text-text hover:text-primary transition">Início</a>
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-sm font-medium text-text hover:text-primary transition">
                            Serviços
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            @foreach(\App\Models\ServiceCategory::orderBy('sort_order')->get() as $cat)
                                <a href="/servicos/{{ $cat->slug }}" class="block px-4 py-2 text-sm text-text hover:bg-bg hover:text-primary transition">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <a href="/planejar" class="text-sm font-medium text-primary hover:text-primary-dark transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path transform="translate(3, 0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                        Planejar Evento
                    </a>
                </div>

                {{-- Auth Buttons --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="/dashboard" class="text-sm font-medium text-text hover:text-primary transition">Meu Painel</a>
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-500 hover:text-primary transition">Sair</button>
                        </form>
                    @else
                        <a href="/cadastrar" class="text-sm font-medium text-text hover:text-primary transition">Cadastrar</a>
                        <a href="/entrar" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-pill transition">
                            Entrar
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button class="md:hidden p-2 text-text hover:text-primary" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-100 mt-2 pt-4">
                <div class="flex flex-col gap-3">
                    <a href="/" class="text-sm font-medium text-text hover:text-primary transition">Início</a>
                    <a href="/servicos" class="text-sm font-medium text-text hover:text-primary transition">Serviços</a>
                    <a href="/planejar" class="text-sm font-medium text-primary hover:text-primary-dark transition">Planejar Evento</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="/admin/fornecedores" class="text-sm font-medium text-warning hover:text-yellow-600 transition">Admin</a>
                        @endif
                        <a href="/dashboard" class="text-sm font-medium text-text hover:text-primary transition">Meu Painel</a>
                    @else
                        <a href="/entrar" class="text-sm font-medium text-text hover:text-primary transition">Entrar</a>
                        <a href="/cadastrar" class="text-sm font-medium text-text hover:text-primary transition">Cadastrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Floating Assistant Button --}}
    @auth
    <a href="/planejar" class="fixed bottom-6 right-6 z-50 bg-secondary hover:bg-secondary-dark text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 group" title="Falar com a Celi">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path transform="translate(3, 0)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
        </svg>
        <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-dark text-white text-xs font-medium px-3 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition">Celi - Assistente IA</span>
    </a>
    @endauth

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Brand --}}
                <div>
                    <a href="/" class="flex items-center gap-2 mb-4">
                        <span class="text-2xl font-bold text-primary">Celebra</span>
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                    </a>
                    <p class="text-sm text-gray-500 leading-relaxed">Planeje o evento dos seus sonhos com os melhores fornecedores e espaços do Brasil.</p>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="font-semibold text-text mb-4">Serviços</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="/servicos/decoracao" class="hover:text-primary transition">Decoração</a></li>
                        <li><a href="/servicos/fotografia" class="hover:text-primary transition">Fotografia</a></li>
                        <li><a href="/servicos/buffet" class="hover:text-primary transition">Buffet</a></li>
                        <li><a href="/servicos/musica" class="hover:text-primary transition">Música</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-text mb-4">Plataforma</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="/planejar" class="hover:text-primary transition">Planejar Evento</a></li>
                        <li><a href="/espacos" class="hover:text-primary transition">Espaços</a></li>
                        <li><a href="/prototype" class="hover:text-primary transition">Protótipo IHC</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-text mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary transition">Privacidade</a></li>
                        <li><a href="#" class="hover:text-primary transition">Termos de Uso</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-8 pt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} Celebra. Todos os direitos reservados.
            </div>
        </div>
    </footer>
    {{-- Container para VLibras widget --}}
    <div id="vlibras-container"></div>
    
    {{-- Script inline para inicializar VLibras após load --}}
    <script>
        window.addEventListener('load', function() {
            // Aguarda a API do VLibras estar disponível
            if (typeof window.VLibras !== 'undefined' && window.VLibras.Widget) {
                try {
                    // Cria nova instância do widget com o container
                    new window.VLibras.Widget('https://vlibras.gov.br/app');
                } catch (e) {
                    console.warn('VLibras Widget instantiation warning:', e);
                }
            }
        });
    </script>
</body>
</html>
