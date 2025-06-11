import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById('addButton');
    const cancelButton = document.getElementById('cancelButton');

    const formContainer = document.getElementById('formContainer');
    const bukuForm = document.getElementById('bukuForm');

    let editing = false;
    const cover = document.getElementById('cover');

    const inputs = {
        judul: document.getElementById('judul'),
        penulis: document.getElementById('penulis'),
        penerbit: document.getElementById('penerbit'),
        genre: document.getElementById('genre'),
        status: document.getElementById('status'),
        harga: document.getElementById('harga'),
    };
    
    function toggleForm(show = true) {
        formContainer.classList.toggle('opacity-0', !show);
        formContainer.classList.toggle('pointer-events-none', !show);
        formContainer.classList.toggle('opacity-100', show);
    }

    function resetFormAction() {
        const defaultAction = bukuForm.dataset.defaultAction;
        bukuForm.action = defaultAction;

        const method = bukuForm.querySelector('input[name="_method"]');
        if (method) method.remove();

        editing = false;
    }

    function setFormActionForEdit(id) {
        bukuForm.action = `/buku/${id}`;
        bukuForm.method = "POST";
        
        const existingMethodInput = bukuForm.querySelector('input[name="_method"]');
        if (existingMethodInput) {
            existingMethodInput.remove();
        }

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        bukuForm.appendChild(methodInput);

        editing = true;
    }

    function populateForm(data) {
        inputs.judul.value = data.judul;
        inputs.penulis.value = data.penulis;
        inputs.penerbit.value = data.penerbit;
        inputs.genre.value = data.genre;
        inputs.status.value = data.status;
        inputs.harga.value = data.harga;
    }

    addButton.addEventListener('click', () => {
        bukuForm.reset();
        resetFormAction();
        toggleForm(true);
    });

    cancelButton.addEventListener('click', () => toggleForm(false));

    document.querySelectorAll('.editButton').forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('.bukuRow');
            const data = {
                id: row.dataset.id,
                judul: row.dataset.judul,
                penulis: row.dataset.penulis,
                penerbit: row.dataset.penerbit,
                genre: row.dataset.genre,
                status: row.dataset.status,
                harga: row.dataset.harga,
            };
            populateForm(data);
            setFormActionForEdit(data.id);
            toggleForm(true);
        });
    });

    document.querySelectorAll('.deleteButton').forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('.bukuRow');
            const id = row.dataset.id;

            Swal.fire({
                title: 'Delete Confirmation',
                text: "Data buku akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/buku/${id}`;

                    const csrf = document.querySelector('input[name="_token"]').cloneNode();
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    bukuForm.addEventListener('submit', (e) => {
        const errors = [];

        if (!inputs.judul.value.trim()) errors.push('Judul wajib diisi.');
        if (!inputs.penulis.value.trim()) errors.push('Penulis wajib diisi.');
        if (!inputs.penerbit.value.trim()) errors.push('Penerbit wajib diisi.');
        if (!inputs.genre.value.trim()) errors.push('Genre wajib diisi.');
        if (!inputs.status.value.trim()) errors.push('Status wajib diisi.');
        
        const hargaValue = parseFloat(inputs.harga.value.replace(',', '.'));
        if (isNaN(hargaValue) || hargaValue < 0) {
            errors.push('Harga harus berupa angka positif.');
        }

        if (!editing) {
            if (!cover.value) {
                errors.push('Cover wajib diisi.');
            }
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                title: errors.length > 1 ? 'Incorrect Inputs' : 'Incorrect Input',
                html: errors.join('<br>'),
                icon: 'error',
                confirmButtonText: 'Perbaiki'
            });
        }
    });
});