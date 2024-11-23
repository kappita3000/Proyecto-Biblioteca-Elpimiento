document.addEventListener('DOMContentLoaded', function() {
    const editBookModal = document.getElementById('editBookModal');
    if (editBookModal) {
        editBookModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const titulo = button.getAttribute('data-titulo');
            const modalForm = editBookModal.querySelector('#editBookForm');
            modalForm.action = `/glibros/${id}`;
            modalForm.querySelector('#editTitulo').value = titulo;
        });
    }

    const deleteBookModal = document.getElementById('deleteBookModal');
    if (deleteBookModal) {
        deleteBookModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const modalForm = deleteBookModal.querySelector('#deleteBookForm');
            modalForm.action = `/glibros/${id}`;
        });
    }
});

