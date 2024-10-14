@extends('layouts.lib')


@section('content')


<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Ruta al CSS -->
    <script src="{{ asset('js/script.js') }}" defer></script> <!-- Ruta al JS -->
    <style>
   

    </style>
</head>
<body>

    
    

    <div class="container mt-5">
        <h1 class="text-center mb-4">Libros</h1>
        <div class="row">
            @foreach($libros as $libro)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title">{{ $libro->titulo }}</h4>
                            <a href="{{ route('libros.show', $libro->id) }}" class="btn btn-primary mt-3">Ver más</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $libros->links('pagination::bootstrap-4') }}
    </div>

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