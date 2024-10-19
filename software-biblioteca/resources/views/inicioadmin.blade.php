@extends('layouts.admin')


@section('content')


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
        <div class="user-info">
            @php
                $admin = Auth::guard('admin')->user();
            @endphp

    @endif

    <div class="container">
        <h1>Página Exclusiva para Administradores</h1>
        
        @if ($admin->rol === 'superadmin')
            <h4>Bienvenido, Superadmin {{ $admin->nombre }}</h4>
        @elseif ($admin->rol === 'moderador')
            <h3>Bienvenido, Moderador {{ $admin->nombre }}</h3> 
        @endif
        
    </div>

</body>
</html>





@endsection