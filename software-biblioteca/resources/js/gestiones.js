document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los checkboxes de la pestaña de Categorías
    const selectAllCategorias = document.getElementById('selectAllCategorias');
    const checkboxesCategorias = document.querySelectorAll('input[name="ids[]"]');

    // Función para manejar el "Seleccionar todo"
    selectAllCategorias.addEventListener('change', function(e) {
        checkboxesCategorias.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    // Añadir el mismo comportamiento para las demás pestañas (Autores, Géneros, Repisas, Editoriales)
    
    // Para la pestaña de Autores
    const selectAllAutores = document.getElementById('selectAllAutores');
    const checkboxesAutores = document.querySelectorAll('input[name="ids[]"]');

    selectAllAutores?.addEventListener('change', function(e) {
        checkboxesAutores.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    // Para la pestaña de Géneros
    const selectAllGeneros = document.getElementById('selectAllGeneros');
    const checkboxesGeneros = document.querySelectorAll('input[name="ids[]"]');

    selectAllGeneros?.addEventListener('change', function(e) {
        checkboxesGeneros.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    // Para la pestaña de Repisas
    const selectAllRepisas = document.getElementById('selectAllRepisas');
    const checkboxesRepisas = document.querySelectorAll('input[name="ids[]"]');

    selectAllRepisas?.addEventListener('change', function(e) {
        checkboxesRepisas.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    // Para la pestaña de Editoriales
    const selectAllEditoriales = document.getElementById('selectAllEditoriales');
    const checkboxesEditoriales = document.querySelectorAll('input[name="ids[]"]');

    selectAllEditoriales?.addEventListener('change', function(e) {
        checkboxesEditoriales.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });
});