document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('togglePassword1').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.textContent = 'Ocultar';
        } else {
            passwordField.type = 'password';
            this.textContent = 'Mostrar';
        }
    });
});