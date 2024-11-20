document.addEventListener('DOMContentLoaded', function () {
    const registradoRadio = document.getElementById('registrado');
    const noRegistradoRadio = document.getElementById('noRegistrado');

    const prestamoFormRegistrado = document.getElementById('prestamoFormRegistrado');
    const prestamoFormNoRegistrado = document.getElementById('prestamoFormNoRegistrado');

    // Verificación de existencia de elementos antes de usarlos
    if (registradoRadio && noRegistradoRadio && prestamoFormRegistrado && prestamoFormNoRegistrado) {
        
        registradoRadio.addEventListener('change', function () {
            if (registradoRadio.checked) {
                // Mostrar el formulario de usuario registrado
                prestamoFormRegistrado.style.display = 'block';
                prestamoFormNoRegistrado.style.display = 'none';

                // Deshabilitar los campos del formulario no registrado
                prestamoFormNoRegistrado.querySelectorAll('input, select').forEach(input => {
                    input.disabled = true;
                });

                // Habilitar campos del formulario registrado
                prestamoFormRegistrado.querySelectorAll('input, select').forEach(input => {
                    input.disabled = false;
                });
            }
        });

        noRegistradoRadio.addEventListener('change', function () {
            if (noRegistradoRadio.checked) {
                // Mostrar el formulario de usuario no registrado
                prestamoFormRegistrado.style.display = 'none';
                prestamoFormNoRegistrado.style.display = 'block';

                // Deshabilitar los campos del formulario registrado
                prestamoFormRegistrado.querySelectorAll('input, select').forEach(input => {
                    input.disabled = true;
                });

                // Habilitar campos del formulario no registrado
                prestamoFormNoRegistrado.querySelectorAll('input, select').forEach(input => {
                    input.disabled = false;
                });
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Variables de búsqueda para Usuarios Registrados
    const usuarioSearch = document.getElementById("usuario_search");
    const usuarioResults = document.getElementById("usuario_results");
    const usuarioIdInput = document.getElementById("usuario_id");

    // Variables de búsqueda para Usuarios No Registrados
    const libroSearchNoRegistrado = document.getElementById("libro_search_no_registrado");
    const libroResultsNoRegistrado = document.getElementById("libro_results_no_registrado");
    const libroIdInputNoRegistrado = document.getElementById("libro_id_no_registrado");

    // Variables de búsqueda para Libros en Usuarios Registrados
    const libroSearch = document.getElementById("libro_search");
    const libroResults = document.getElementById("libro_results");
    const libroIdInput = document.getElementById("libro_id");

    // Función de búsqueda dinámica
    async function buscar(query, url, resultsDiv, hiddenInput, inputField) {
        if (query.length < 2) {
            resultsDiv.innerHTML = "";
            resultsDiv.style.display = "none";
            return;
        }
        const response = await fetch(url + "?q=" + query);
        const data = await response.json();

        // Renderizar resultados
        resultsDiv.innerHTML = data
            .map((item) => {
                if (item.correo) {
                    // Datos de usuarios
                    return `<div class="dropdown-item" data-id="${item.id}">${item.correo} (${item.nombre} ${item.apellido})</div>`;
                } else {
                    // Datos de libros
                    return `<div class="dropdown-item" data-id="${item.id}">${item.titulo}</div>`;
                }
            })
            .join("");
        resultsDiv.style.display = "block";

        // Manejar selección de resultados
        resultsDiv.querySelectorAll(".dropdown-item").forEach((item) => {
            item.addEventListener("click", () => {
                hiddenInput.value = item.dataset.id;
                resultsDiv.innerHTML = "";
                resultsDiv.style.display = "none";
                inputField.value = item.innerText;
            });
        });
    }

    // Eventos de búsqueda
    if (usuarioSearch) {
        usuarioSearch.addEventListener("input", (e) => {
            buscar(e.target.value, "/buscar-usuarios", usuarioResults, usuarioIdInput, usuarioSearch);
        });
    }

    if (libroSearch) {
        libroSearch.addEventListener("input", (e) => {
            buscar(e.target.value, "/buscar-libros", libroResults, libroIdInput, libroSearch);
        });
    }

    if (libroSearchNoRegistrado) {
        libroSearchNoRegistrado.addEventListener("input", (e) => {
            buscar(e.target.value, "/buscar-libros", libroResultsNoRegistrado, libroIdInputNoRegistrado, libroSearchNoRegistrado);
        });
    }

    // Ocultar resultados al hacer clic fuera del campo de búsqueda
    document.addEventListener("click", function (event) {
        if (usuarioSearch && !usuarioSearch.contains(event.target) && !usuarioResults.contains(event.target)) {
            usuarioResults.style.display = "none";
        }
        if (libroSearch && !libroSearch.contains(event.target) && !libroResults.contains(event.target)) {
            libroResults.style.display = "none";
        }
        if (libroSearchNoRegistrado && !libroSearchNoRegistrado.contains(event.target) && !libroResultsNoRegistrado.contains(event.target)) {
            libroResultsNoRegistrado.style.display = "none";
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Recuperar la pestaña activa del LocalStorage al cargar la página
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        const tabElement = document.querySelector(`[data-bs-target="${activeTab}"], [href="${activeTab}"]`);
        if (tabElement) {
            new bootstrap.Tab(tabElement).show();
        }
    }

    // Escuchar el evento para guardar la pestaña activa cuando se cambia
    const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"], a[data-bs-toggle="tab"]');
    tabButtons.forEach(tabButton => {
        tabButton.addEventListener('shown.bs.tab', function (event) {
            const activeTabId = event.target.getAttribute('data-bs-target') || event.target.getAttribute('href');
            localStorage.setItem('activeTab', activeTabId);
        });
    });
});



function toggleForms() {
    var registradoForm = document.getElementById('prestamoFormRegistrado');
    var noRegistradoForm = document.getElementById('prestamoFormNoRegistrado');
    
    if (document.getElementById("tipoUsuarioRegistrado").checked) {
        registradoForm.style.display = "block"; // Mostrar formulario de usuario registrado
        noRegistradoForm.style.display = "none"; // Ocultar formulario de usuario no registrado
    } else {
        registradoForm.style.display = "none"; // Ocultar formulario de usuario registrado
        noRegistradoForm.style.display = "block"; // Mostrar formulario de usuario no registrado
    }
}

src="https://code.jquery.com/jquery-3.6.0.min.js"
src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"