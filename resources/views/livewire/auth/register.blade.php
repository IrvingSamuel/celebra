<div class="min-h-screen bg-bg flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-card shadow-sm p-8">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-4">
                <span class="text-3xl font-bold text-primary">Celebra</span>
            </a>
            <h1 class="text-2xl font-bold text-text">Criar conta</h1>
            <p class="text-sm text-gray-500 mt-1">Junte-se à maior plataforma de eventos</p>
        </div>

        <form wire:submit="register" class="space-y-5">
            {{-- Role Selection --}}
            <div>
                <label class="block text-sm font-medium text-text mb-2">Eu sou</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="role" value="client" class="peer sr-only">
                        <div class="p-4 rounded-card border-2 border-gray-200 text-center transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <span class="text-2xl block mb-1">🎉</span>
                            <span class="text-sm font-medium text-text">Cliente</span>
                            <p class="text-xs text-gray-400 mt-0.5">Quero planejar eventos</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="role" value="supplier" class="peer sr-only">
                        <div class="p-4 rounded-card border-2 border-gray-200 text-center transition peer-checked:border-primary peer-checked:bg-primary/5">
                            <span class="text-2xl block mb-1">🏪</span>
                            <span class="text-sm font-medium text-text">Fornecedor</span>
                            <p class="text-xs text-gray-400 mt-0.5">Ofereço serviços</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-text mb-1">Nome completo</label>
                <input wire:model="name" type="text" id="name" placeholder="Seu nome"
                    class="w-full px-4 py-3 rounded-btn border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

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
                <input wire:model="password" type="password" id="password" placeholder="Mínimo 8 caracteres"
                    class="w-full px-4 py-3 rounded-btn border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                @error('password') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-text mb-1">Confirmar senha</label>
                <input wire:model="password_confirmation" type="password" id="password_confirmation" placeholder="Repita a senha"
                    class="w-full px-4 py-3 rounded-btn border border-gray-200 text-sm text-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-btn transition"
                wire:loading.attr="disabled" wire:loading.class="opacity-50">
                <span wire:loading.remove>Criar conta</span>
                <span wire:loading>Criando...</span>
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Já tem conta? <a href="/entrar" class="text-primary font-medium hover:text-primary-dark transition">Entrar</a>
        </p>
    </div>
</div>
