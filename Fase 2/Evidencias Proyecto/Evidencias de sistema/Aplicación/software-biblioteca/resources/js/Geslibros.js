document.addEventListener('DOMContentLoaded', function () {
    const editBookModal = document.getElementById('editBookModal');

    // Mostrar datos en el modal al abrir
    if (editBookModal) {
        editBookModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Botón que activó el modal

            // Obtener atributos del botón
            const id = button.getAttribute('data-id');
            const titulo = button.getAttribute('data-titulo');
            const autor = button.getAttribute('data-autor');
            const genero = button.getAttribute('data-genero');
            const categoria = button.getAttribute('data-categoria');
            const editorial = button.getAttribute('data-editorial');
            const repisa = button.getAttribute('data-repisa');
            const cantidad = button.getAttribute('data-cantidad');
            const descripcion = button.getAttribute('data-descripcion');
            const disponible = button.getAttribute('data-disponible');

            // Asignar valores a los campos del modal solo si no están presentes (para evitar sobrescribir old())
            const modalForm = editBookModal.querySelector('#editBookForm');
            modalForm.action = `/glibros/${id}`; // Ruta para el update

            const fields = [
                { id: 'editBookId', value: id },
                { id: 'editTitulo', value: titulo },
                { id: 'editAutor', value: autor },
                { id: 'editGenero', value: genero },
                { id: 'editCategoria', value: categoria },
                { id: 'editEditorial', value: editorial },
                { id: 'editRepisa', value: repisa },
                { id: 'editCantidad', value: cantidad },
                { id: 'editDescripcion', value: descripcion }
            ];

            // Rellenar campos si no tienen valores previos
            fields.forEach(field => {
                const input = modalForm.querySelector(`#${field.id}`);
                if (input && !input.value) {
                    input.value = field.value;
                }
            });

            // Configurar el checkbox de "Disponible"
            const disponibleCheckbox = modalForm.querySelector('#editDisponible');
            if (disponibleCheckbox) {
                disponibleCheckbox.checked = disponible === '1'; // Marcar si es '1'
            }
        });
    }

    // Detectar errores y abrir automáticamente el modal
    if (editBookModal && document.querySelector('.alert-danger')) {
        const bootstrapModal = new bootstrap.Modal(editBookModal);
        bootstrapModal.show();
    }





    // **MODAL DE ELIMINACIÓN**
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


document.addEventListener("DOMContentLoaded", function () {
    const inputs = [
        { field: "autor", type: "autor" },
        { field: "genero", type: "genero" },
        { field: "categoria", type: "categoria" },
        { field: "editorial", type: "editorial" },
        { field: "repisa", type: "repisa" },
    ];

    inputs.forEach((input) => {
        const textInput = document.getElementById(input.field);
        const suggestionsBox = document.createElement("div");
        suggestionsBox.classList.add("dropdown-menu");
        suggestionsBox.style.position = "absolute";
        suggestionsBox.style.display = "none";

        // Agregar el contenedor de sugerencias al DOM
        textInput.parentElement.appendChild(suggestionsBox);

        textInput.addEventListener("input", function () {
            const query = this.value;

            if (query.length > 1) {
                // Ajustar el ancho y la posición del cuadro de sugerencias
                suggestionsBox.style.width = `${textInput.offsetWidth}px`;
                suggestionsBox.style.left = `${textInput.getBoundingClientRect().left + window.scrollX}px`;
                suggestionsBox.style.top = `${textInput.getBoundingClientRect().bottom + window.scrollY}px`;

                // Hacer solicitud al backend
                fetch(`/autocomplete?type=${input.type}&q=${query}`)
                    .then((response) => response.json())
                    .then((data) => {
                        suggestionsBox.innerHTML = ""; // Limpiar las sugerencias anteriores
                        if (data.length > 0) {
                            data.forEach((item) => {
                                const suggestionItem = document.createElement("div");
                                suggestionItem.className = "dropdown-item";
                                suggestionItem.textContent = item.text;
                                suggestionItem.dataset.id = item.id;

                                suggestionItem.addEventListener("click", function () {
                                    textInput.value = this.textContent;
                                    suggestionsBox.style.display = "none"; // Ocultar sugerencias
                                });

                                suggestionsBox.appendChild(suggestionItem);
                            });
                            suggestionsBox.style.display = "block"; // Mostrar sugerencias
                        } else {
                            suggestionsBox.style.display = "none";
                        }
                    })
                    .catch((error) => console.error("Error al obtener sugerencias:", error));
            } else {
                suggestionsBox.style.display = "none";
            }
        });

        // Ocultar sugerencias si se hace clic fuera del campo o de las sugerencias
        document.addEventListener("click", function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== textInput) {
                suggestionsBox.style.display = "none";
            }
        });
    });
});
