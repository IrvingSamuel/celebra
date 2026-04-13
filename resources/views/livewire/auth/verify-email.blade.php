<div class="min-h-screen bg-bg flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-card shadow-sm p-8 text-center">

        <a href="/" class="inline-flex items-center gap-2 mb-6">
            <span class="text-3xl font-bold text-primary">Celebra</span>
        </a>

        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-5">
            <span class="text-4xl text-primary">✦</span>
        </div>

        <h1 class="text-2xl font-bold text-text mb-2">Verifique seu e-mail</h1>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
            Enviamos um link de confirmação para
            <strong class="text-text">{{ auth()->user()->email }}</strong>.<br>
            Clique no link do e-mail para ativar sua conta.
        </p>

        @if(auth()->user()->isSupplier())
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4 mb-6 text-left">
                <p class="text-sm font-semibold text-primary mb-1">📋 Conta de fornecedor</p>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Após confirmar seu e-mail, nossa equipe irá revisar e aprovar seu cadastro.
                    Você será notificado quando sua conta estiver ativa na plataforma.
                </p>
            </div>
        @endif

        @if($resent)
            <div class="bg-success/10 border border-success/30 text-success text-sm font-medium px-4 py-3 rounded-xl mb-5 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Novo e-mail de confirmação enviado!
            </div>
        @endif

        <button wire:click="resend"
                wire:loading.attr="disabled"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold text-sm px-6 py-2.5 rounded-pill transition disabled:opacity-60 mb-4">
            <span wire:loading.remove wire:target="resend">Reenviar e-mail de confirmação</span>
            <span wire:loading wire:target="resend">Enviando...</span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-primary transition">
                Sair da conta
            </button>
        </form>

    </div>
</div>
