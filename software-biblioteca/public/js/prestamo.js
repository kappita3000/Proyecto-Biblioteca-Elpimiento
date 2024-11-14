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