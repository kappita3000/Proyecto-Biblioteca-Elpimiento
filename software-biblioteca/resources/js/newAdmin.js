document.addEventListener('DOMContentLoaded', function() {
    // Alternar visibilidad de la contraseña
    const togglePassword1 = document.getElementById('togglePassword1');
    const togglePassword2 = document.getElementById('togglePassword2');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirmPassword');
    const submitBtn = document.getElementById('submitBtn');
    const passwordHelp = document.getElementById('passwordHelp');
    const registrationForm = document.getElementById('registrationForm');

    if (togglePassword1 && passwordField) {
        togglePassword1.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.textContent = 'Ocultar';
            } else {
                passwordField.type = 'password';
                this.textContent = 'Mostrar';
            }
        });
    }

    if (togglePassword2 && confirmPasswordField) {
        togglePassword2.addEventListener('click', function() {
            if (confirmPasswordField.type === 'password') {
                confirmPasswordField.type = 'text';
                this.textContent = 'Ocultar';
            } else {
                confirmPasswordField.type = 'password';
                this.textContent = 'Mostrar';
            }
        });
    }

    // Validar que las contraseñas coincidan
    if (confirmPasswordField && passwordField) {
        confirmPasswordField.addEventListener('input', validatePasswords);
        passwordField.addEventListener('input', validatePasswords);

        function validatePasswords() {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (password !== confirmPassword || password === '' || confirmPassword === '') {
                if (passwordHelp) {
                    passwordHelp.style.display = 'block';
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
            } else {
                if (passwordHelp) {
                    passwordHelp.style.display = 'none';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            }
        }
    }

    // Evitar que el formulario se envíe si las contraseñas no coinciden
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(event) {
            const password = passwordField ? passwordField.value : '';
            const confirmPassword = confirmPasswordField ? confirmPasswordField.value : '';

            if (password !== confirmPassword) {
                event.preventDefault(); // Evita que se envíe el formulario
                alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
            }
        });
    }
});
