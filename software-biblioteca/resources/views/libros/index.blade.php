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
    <div class="container">
        <h1>Libros Disponibles</h1>
        
        <div class="cards">
            @foreach ($libros as $libro)
                <div class="card">
                    <h3>{{ $libro->Titulo }}</h3>
                    <a href="{{ route('libros.show', $libro->ID) }}" class="btn">Ver más</a>
                </div>
            @endforeach

            @if (Auth::check())
            <div class="user-info" .user-info {
                position: absolute; /* o fixed dependiendo de donde lo quieras */
                top: 10px;
                right: 10px;
                background-color: #f8f9fa; /* Color de fondo */
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }>
                Bienvenido, {{ Auth::user()->nombre }} | <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf <!-- Este token es necesario para la protección CSRF -->
                    <button type="submit" class="btn btn-link">Cerrar sesión</button>
                </form>
            </div>
        @endif

        
        </div>
    </div>
</body>
</html>





@endsection