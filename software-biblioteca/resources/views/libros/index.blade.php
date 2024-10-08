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
        </div>
    </div>
</body>
</html>





@endsection