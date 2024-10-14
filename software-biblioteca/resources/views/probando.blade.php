@extends('app')


@section('contenent')


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Ruta al CSS -->
    <script src="{{ asset('js/script.js') }}" defer></script> <!-- Ruta al JS -->
</head>
<body>
    @if (Auth::guard('admin')->check())
        <div class="user-info" style="
            position: absolute; 
            top: 10px;
            right: 10px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        ">
            @php
                $admin = Auth::guard('admin')->user();
            @endphp

            @if ($admin->rol === 'superadmin')
                Bienvenido, {{ $admin->nombre }} (Superadmin) |
            @elseif ($admin->rol === 'moderador')
                Bienvenido, {{ $admin->nombre }} (Moderador) |
            @endif

            <!-- Botón para cerrar sesión -->
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-link">Cerrar sesión</button>
            </form>
        </div>
    @endif

    <div class="container">
        <h1>Página Exclusiva para Administradores</h1>

        
           

            <!-- Botón 1 y Botón 2 son comunes para ambos roles -->
            <button class="btn btn-primary">Botón 1</button>
            <button class="btn btn-secondary">Botón 2</button>

            @if ($admin->rol === 'superadmin')
                <!-- Solo mostrar Botón 3 para Superadmin -->
                <button class="btn btn-success">Botón 3 (Solo Superadmin)</button>
            @endif
        
    </div>

</body>
</html>





@endsection