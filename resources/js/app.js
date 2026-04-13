import './bootstrap';
import Swal from 'sweetalert2';

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
