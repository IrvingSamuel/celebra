<div class="min-h-screen bg-bg flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-card shadow-sm p-8">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-4">
                <span class="text-3xl font-bold text-primary">Celebra</span>
            </a>
            <h1 class="text-2xl font-bold text-text">Entrar</h1>
            <p class="text-sm text-gray-500 mt-1">Acesse sua conta na plataforma</p>
        </div>

        <form wire:submit="login" class="space-y-5">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-text mb-1">E-mail</label>
                <input wire:model="email" type="email" id="email" placeholder="seu@email.com"
                    class="w-full px-4 py-3 rounded-btn border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-text mb-1">Senha</label>
                <input wire:model="password" type="password" id="password" placeholder="Sua senha"
                    class="w-full px-4 py-3 rounded-btn border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                @error('password') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm text-gray-500">Lembrar de mim</span>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-btn transition"
                wire:loading.attr="disabled" wire:loading.class="opacity-50">
                <span wire:loading.remove>Entrar</span>
                <span wire:loading>Entrando...</span>
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Não tem conta? <a href="/cadastrar" class="text-primary font-medium hover:text-primary-dark transition">Cadastrar</a>
        </p>
    </div>
</div>
