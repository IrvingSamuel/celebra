import './bootstrap';
import Swal from 'sweetalert2';
import { initHomeAnimations } from './home-animations';

// ============================================
// Gerenciamento de Tema (Dark Mode)
// ============================================

/**
 * Aplica o tema ao <html> e persiste no localStorage.
 * @param {'dark'|'light'} theme
 */
window.applyTheme = function (theme) {
    const root = document.documentElement;
    if (theme === 'dark') {
        root.setAttribute('data-theme', 'dark');
    } else {
        root.removeAttribute('data-theme');
    }
    localStorage.setItem('theme', theme);

    // Atualiza ícones de toggle em toda a página
    document.querySelectorAll('[data-theme-icon-sun]').forEach(el => {
        el.classList.toggle('hidden', theme !== 'dark');
    });
    document.querySelectorAll('[data-theme-icon-moon]').forEach(el => {
        el.classList.toggle('hidden', theme === 'dark');
    });
};

window.toggleTheme = function () {
    const current = document.documentElement.getAttribute('data-theme');
    window.applyTheme(current === 'dark' ? 'light' : 'dark');
};

// ============================================
// Gerenciamento de Alto Contraste
// ============================================

/**
 * Aplica o modo de contraste ao <html> e persiste no localStorage.
 * @param {'high'|'normal'} contrast
 */
window.applyContrast = function (contrast) {
    const root = document.documentElement;
    if (contrast === 'high') {
        root.setAttribute('data-contrast', 'high');
    } else {
        root.setAttribute('data-contrast', 'normal');
        root.removeAttribute('data-contrast');
    }
    localStorage.setItem('contrast', contrast);

    // Atualiza ícones e aria-label de todos os botões de contraste
    document.querySelectorAll('[data-contrast-icon-on]').forEach(el => {
        el.classList.toggle('hidden', contrast !== 'high');
    });
    document.querySelectorAll('[data-contrast-icon-off]').forEach(el => {
        el.classList.toggle('hidden', contrast === 'high');
    });
    document.querySelectorAll('[data-contrast-toggle]').forEach(el => {
        el.setAttribute('aria-pressed', contrast === 'high' ? 'true' : 'false');
        el.setAttribute('aria-label', contrast === 'high' ? 'Desativar alto contraste' : 'Ativar alto contraste');
    });
};

window.toggleContrast = function () {
    const current = document.documentElement.getAttribute('data-contrast');
    window.applyContrast(current === 'high' ? 'normal' : 'high');
};

window.Swal = Swal;

window.SwalTheme = {
    confirmDialog(options) {
        return Swal.fire({
            confirmButtonColor: '#FF477E',
            cancelButtonColor: '#9CA3AF',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            background: '#FFFFFF',
            color: '#1F2937',
            customClass: {
                popup: '!rounded-xl !font-[Poppins] !shadow-card !border !border-gray-200',
                title: '!text-text !text-lg !font-semibold',
                htmlContainer: '!text-sm !text-text-light',
                actions: '!mt-6 !gap-3',
                confirmButton: '!rounded-lg !text-sm !font-semibold !px-5 !py-2',
                cancelButton: '!rounded-lg !text-sm !font-medium !px-5 !py-2',
            },
            ...options,
        });
    },
    dangerDialog(options) {
        return window.SwalTheme.confirmDialog({
            icon: 'warning',
            confirmButtonColor: '#EF4444',
            ...options,
        });
    },
    successDialog(options) {
        return window.SwalTheme.confirmDialog({
            icon: 'success',
            confirmButtonColor: '#10B981',
            ...options,
        });
    },
    infoDialog(options) {
        return Swal.fire({
            icon: 'info',
            confirmButtonColor: '#3B82F6',
            showCancelButton: false,
            customClass: {
                popup: '!rounded-xl !font-[Poppins]',
                confirmButton: '!rounded-lg !text-sm !font-semibold !px-5 !py-2',
            },
            ...options,
        });
    },
    errorDialog(options) {
        return Swal.fire({
            icon: 'error',
            confirmButtonColor: '#EF4444',
            showCancelButton: false,
            customClass: {
                popup: '!rounded-xl !font-[Poppins]',
                confirmButton: '!rounded-lg !text-sm !font-semibold !px-5 !py-2',
            },
            ...options,
        });
    },
};

window.addEventListener('DOMContentLoaded', () => {
    initHomeAnimations();

    // Procura os botões que você colocou no HTML pelos IDs deles
    const btnAumentar = document.getElementById('btn-aumentar');
    const btnDiminuir = document.getElementById('btn-diminuir');
    const btnNormal = document.getElementById('btn-normal');

    let tamanhoAtual = 100; 

    function aplicarTamanho(novoTamanho) {
        tamanhoAtual = novoTamanho;
        // Altera o tamanho da fonte do site inteiro mudando o body
        document.body.style.fontSize = tamanhoAtual + '%';
    }

    // Se o botão existir na tela, ativa o clique dele
    if (btnAumentar) {
        btnAumentar.addEventListener('click', () => {
            if (tamanhoAtual < 140) aplicarTamanho(tamanhoAtual + 10);
        });
    }

    if (btnDiminuir) {
        btnDiminuir.addEventListener('click', () => {
            if (tamanhoAtual > 80) aplicarTamanho(tamanhoAtual - 10);
        });
    }

    if (btnNormal) {
        btnNormal.addEventListener('click', () => {
            aplicarTamanho(100);
        });
    }
});