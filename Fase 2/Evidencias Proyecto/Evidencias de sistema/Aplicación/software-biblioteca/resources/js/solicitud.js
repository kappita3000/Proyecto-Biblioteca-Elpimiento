document.addEventListener('DOMContentLoaded', function () {
    const emailField = document.getElementById('correoUsuario');
    const emailError = document.getElementById('emailError');
    const submitBtn = document.getElementById('submitBtn');

    // Validar correo con dominios específicos y extensiones válidas
    function validateEmail() {
        const emailPattern = /^[^\s@]+@(gmail\.com|gmail\.[a-z]{2,3}|hotmail\.com|hotmail\.[a-z]{2,3}|outlook\.com|yahoo\.com|yahoo\.[a-z]{2,3}|icloud\.com|protonmail\.com)$/i;
        if (!emailPattern.test(emailField.value.trim())) {
            emailError.style.display = 'block';
            emailError.textContent =
                'Por favor, ingresa un correo válido. Solo se permiten dominios como gmail, hotmail, outlook, yahoo, icloud y protonmail.';
            return false;
        } else {
            emailError.style.display = 'none';
            return true;
        }
    }

    // Habilitar/deshabilitar el botón de envío
    function toggleSubmitButton() {
        const isEmailValid = validateEmail();
        submitBtn.disabled = !isEmailValid;
    }

    // Evento para validar el correo mientras se escribe
    emailField.addEventListener('input', function () {
        validateEmail();
        toggleSubmitButton();
    });

    // Validar nuevamente al enviar el formulario
    const form = document.getElementById('prestamoForm');
    form.addEventListener('submit', function (e) {
        if (!validateEmail()) {
            e.preventDefault(); // Detiene el envío si hay errores
            emailField.focus();
        }
    });
});