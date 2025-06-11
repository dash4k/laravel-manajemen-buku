import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
    if (window.laravelErrors && Array.isArray(window.laravelErrors)) {
        const errorMessages = window.laravelErrors.join('<br>');

        Swal.fire({
            title: 'Whoops...',
            html: errorMessages,
            icon: 'error',
            confirmButtonText: '  OK  ',
            confirmButtonColor: '#2176ff',
        });
    }
    
    if (window.laravelSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: window.laravelSuccess,
        });
    }
});