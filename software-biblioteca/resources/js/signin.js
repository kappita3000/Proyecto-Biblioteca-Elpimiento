// validacion correo: copiar de aqui
document.addEventListener('DOMContentLoaded', function () {
    const emailField = document.getElementById('correo');
    const emailError = document.getElementById('emailError');

    // hasta aqui

    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const submitBtn = document.getElementById('submitBtn');
    const togglePassword1 = document.getElementById('togglePassword1');
    const togglePassword2 = document.getElementById('togglePassword2');

    // Alternar visibilidad de contraseñas
    if (togglePassword1 && passwordField) {
        togglePassword1.addEventListener('click', function () {
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
            this.textContent = this.textContent === 'Mostrar' ? 'Ocultar' : 'Mostrar';
        });
    }

    if (togglePassword2 && confirmPasswordField) {
        togglePassword2.addEventListener('click', function () {
            confirmPasswordField.type = confirmPasswordField.type === 'password' ? 'text' : 'password';
            this.textContent = this.textContent === 'Mostrar' ? 'Ocultar' : 'Mostrar';
        });
    }

    // y de aqui
    // Validar correo con dominios específicos y TLDs válidos
    function validateEmail() {
        const emailPattern = /^[^\s@]+@(gmail\.com|gmail\.[a-z]{2,3}|hotmail\.com|hotmail\.[a-z]{2,3}|outlook\.com|yahoo\.com|yahoo\.[a-z]{2,3}|icloud\.com|protonmail\.com)$/i;
        if (!emailPattern.test(emailField.value.trim())) {
            emailError.style.display = 'block';
            emailError.textContent =
                'Por favor, ingresa un correo válido. Solo se permiten dominios como gmail, hotmail, outlook, yahoo, icloud y protonmail';
        } else {
            emailError.style.display = 'none';
        }
    }
    // Hasta aqui


    // Validar contraseña con mensaje actualizado
    function validatePassword() {
        const password = passwordField.value.trim();
        const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&_\-])[A-Za-z\d@$!%*?&_\-]{8,}$/;

        if (!passwordPattern.test(password)) {
            passwordError.style.display = 'block';
            passwordError.textContent =
                'Debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula, un número y un símbolo permitido: @$!%*?&-_.';
        } else {
            passwordError.style.display = 'none';
        }
    }

    // Validar confirmación de contraseña
    function validateConfirmPassword() {
        const password = passwordField.value.trim();
        const confirmPassword = confirmPasswordField.value.trim();

        if (password !== confirmPassword) {
            confirmPasswordError.style.display = 'block';
            confirmPasswordError.textContent = 'Las contraseñas no coinciden.';
        } else {
            confirmPasswordError.style.display = 'none';
        }
    }

    // Habilitar/deshabilitar el botón de envío
    function toggleSubmitButton() {
        const isEmailValid = emailError.style.display === 'none';
        const isPasswordValid = passwordError.style.display === 'none';
        const isConfirmPasswordValid = confirmPasswordError.style.display === 'none';

        submitBtn.disabled = !(isEmailValid && isPasswordValid && isConfirmPasswordValid);
    }

    // y de aqui
    // Eventos para validaciones específicas
    emailField.addEventListener('input', function () {
        validateEmail();
        toggleSubmitButton();
    });
    // Hasta aqui

    passwordField.addEventListener('input', function () {
        validatePassword();
        validateConfirmPassword();
        toggleSubmitButton();
    });

    confirmPasswordField.addEventListener('input', function () {
        validateConfirmPassword();
        toggleSubmitButton();
    });
});
