@extends('layouts.admin')
<title>Crear Moderador</title>
@section('content')

    
<div class="container w-20 p-4">
    <form action="{{ route('newAdmin') }}" method="POST">
        @csrf

        @if (session('success'))
        <h6 class="alert alert-success">{{ session('success') }}</h6>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
          <label for="correo" class="form-label">Correo electronico</label>
          <input type="email" class="form-control" id="correo" aria-describedby="emailHelp" name="correo" required autofocus>
        </div>
        <div class="row">
            <div class="col">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" id="nombre" class="form-control" aria-label="First name" name="nombre" required autofocus>
            </div>
            <div class="col">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" id="apellido" class="form-control" aria-label="Last name" name="apellido" required autofocus>
            </div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="contraseña" required autofocus>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword1">Mostrar</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Repetir Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirmPassword" name="contraseña_confirmation" required autofocus>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">Mostrar</button>
            </div>
            <div id="passwordHelp" class="form-text text-danger" style="display:none;">Las contraseñas no coinciden.</div>
        </div>
        
        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Crear Moderador</button>
      </form>



      <script>
        // mover esto a un lugar seguro
        // mover esto a un lugar seguro
        // mover esto a un lugar seguro
        // mover esto a un lugar seguro

           // Función para alternar la visibilidad de la contraseña
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

    document.getElementById('togglePassword2').addEventListener('click', function() {
        const confirmPasswordField = document.getElementById('confirmPassword');
        if (confirmPasswordField.type === 'password') {
            confirmPasswordField.type = 'text';
            this.textContent = 'Ocultar';
        } else {
            confirmPasswordField.type = 'password';
            this.textContent = 'Mostrar';
        }
    });

    // Función para validar que las contraseñas coincidan
    document.getElementById('confirmPassword').addEventListener('input', validatePasswords);
    document.getElementById('password').addEventListener('input', validatePasswords);

    function validatePasswords() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const submitBtn = document.getElementById('submitBtn');
        const passwordHelp = document.getElementById('passwordHelp');

        if (password !== confirmPassword || password === '' || confirmPassword === '') {
            passwordHelp.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            passwordHelp.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    // Evitar que el formulario se envíe si las contraseñas no coinciden
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (password !== confirmPassword) {
            event.preventDefault(); // Evita que se envíe el formulario
            alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
        }
    });

        // mover esto a un lugar seguro
        // mover esto a un lugar seguro
        // mover esto a un lugar seguro
        // mover esto a un lugar seguro
    </script>
</div>
@endsection