import './bootstrap';
import Swal from 'sweetalert2';

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

window.Swal = Swal;

window.SwalTheme = {
    confirmDialog(options) {
        return Swal.fire({
            confirmButtonColor: '#FF477E',
            cancelButtonColor: '#9CA3AF',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: {
                popup: '!rounded-xl !font-[Poppins]',
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
            icon: 'question',
            confirmButtonColor: '#10B981',
            ...options,
        });
    },
};

// VLibras loader — carrega o plugin oficial do governo para Libras
(function initVLibras(){
    try {
        const lang = document.documentElement.lang || navigator.language || 'pt-BR';
        if (!/pt/i.test(lang)) return; // somente para pt
        if (document.getElementById('vlibras-script')) return;
        const script = document.createElement('script');
        script.id = 'vlibras-script';
        script.src = 'https://vlibras.gov.br/app/vlibras-plugin.js';
        script.defer = true;
        script.onload = () => {
            // cria wrapper mínimo se não existir
            if (!document.querySelector('div[vw]')) {
                const wrapper = document.createElement('div');
                wrapper.setAttribute('vw', '');
                wrapper.className = 'enabled';
                wrapper.innerHTML = '<div vw-access-button class="vw-access-button" aria-hidden="true"></div><div vw-plugin-wrapper aria-hidden="true"><div class="vw-plugin-top-wrapper"></div></div>';
                document.body.appendChild(wrapper);
            }
        };
        document.head.appendChild(script);
    } catch (e) {
        console.error('VLibras init error', e);
    }
})();
