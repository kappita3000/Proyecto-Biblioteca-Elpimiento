@extends('layouts.lib')
<title>Crear cuenta</title>
@section('content')

<div class="container w-20 p-4">
    <form action="{{ route('usuario') }}" method="POST">
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
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="correo" aria-describedby="emailHelp" name="correo" required autofocus>
          <span id="emailError" style="display: none; color: red;"></span>
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
            <span id="passwordError" style="display: none; color: red;"></span>
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Repetir Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirmPassword" name="contraseña_confirmation" required autofocus>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">Mostrar</button>
            </div>
            <span id="confirmPasswordError" style="display: none; color: red;"></span>
        </div>
        
        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Ingresar</button>
    </form>

    <script src="{{ asset('js/signin.js') }}"></script>
</div>
@endsection
