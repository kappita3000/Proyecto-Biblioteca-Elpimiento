document.addEventListener("DOMContentLoaded", function () {
    // Función para manejar búsquedas dinámicas
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

    // Función para registrar los eventos de búsqueda
    function registrarBusqueda(inputId, url, resultsId, hiddenInputId) {
        const inputField = document.getElementById(inputId);
        const resultsDiv = document.getElementById(resultsId);
        const hiddenInput = document.getElementById(hiddenInputId);

        if (inputField && resultsDiv && hiddenInput) {
            inputField.addEventListener("input", (e) => {
                buscar(e.target.value, url, resultsDiv, hiddenInput, inputField);
            });
        }
    }

    // Registrar búsquedas
    registrarBusqueda("usuario_search", "/buscar-usuarios", "usuario_results", "usuario_id");
    registrarBusqueda("libro_search", "/buscar-libros", "libro_results", "libro_id");
    registrarBusqueda("libro_search_no_registrado", "/buscar-libros", "libro_results_no_registrado", "libro_id_no_registrado");

    // Ocultar resultados al hacer clic fuera del campo de búsqueda
    document.addEventListener("click", function (event) {
        const elementosBusqueda = [
            { search: "usuario_search", results: "usuario_results" },
            { search: "libro_search", results: "libro_results" },
            { search: "libro_search_no_registrado", results: "libro_results_no_registrado" },
        ];

        elementosBusqueda.forEach(({ search, results }) => {
            const searchElement = document.getElementById(search);
            const resultsElement = document.getElementById(results);
            if (searchElement && resultsElement && !searchElement.contains(event.target) && !resultsElement.contains(event.target)) {
                resultsElement.style.display = "none";
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const registradoTab = document.querySelector('[href="#usuarioRegistrado"]');
        const noRegistradoTab = document.querySelector('[href="#usuarioNoRegistrado"]');
        const prestamoFormRegistrado = document.getElementById('prestamoFormRegistrado');
        const prestamoFormNoRegistrado = document.getElementById('prestamoFormNoRegistrado');
    
        if (registradoTab && noRegistradoTab && prestamoFormRegistrado && prestamoFormNoRegistrado) {
            registradoTab.addEventListener('click', function () {
                prestamoFormRegistrado.style.display = 'block';
                prestamoFormNoRegistrado.style.display = 'none';
            });
    
            noRegistradoTab.addEventListener('click', function () {
                prestamoFormRegistrado.style.display = 'none';
                prestamoFormNoRegistrado.style.display = 'block';
            });
        }
    });


    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'solicitudes'; // Si no hay pestaña activa en la URL, usa 'solicitudes'
        const tabElement = document.querySelector(`[href="#${activeTab}"]`);
    
        if (tabElement) {
            // Mostrar la pestaña activa
            new bootstrap.Tab(tabElement).show();
        } else {
            // Si no se encuentra una pestaña activa, activa la primera pestaña
            const firstTab = document.querySelector('#prestamosTabs .nav-link');
            if (firstTab) {
                new bootstrap.Tab(firstTab).show();
            }
        }
    
        // Guardar la pestaña activa al cambiar
        document.querySelectorAll('#prestamosTabs .nav-link').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                const activeTabId = event.target.getAttribute('href').substring(1); // Captura el ID de la pestaña activa
                urlParams.set('tab', activeTabId);
                const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
                history.replaceState(null, '', newUrl); // Actualiza la URL sin recargar la página
            });
        });
    });

});
