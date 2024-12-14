@extends('layouts.lib')
<title>Iniciar sesion</title>
@section('content')

    
<div class="container w-50 p-4">
    <!-- Mensajes de éxito -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" aria-describedby="emailHelp" name="correo" value="{{ old('correo') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="contraseña" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword1">Mostrar</button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>



</div>
<!-- Script para mostrar y ocultar la contraseña -->
<script src="{{ asset('js/login.js') }}"></script>
@endsection