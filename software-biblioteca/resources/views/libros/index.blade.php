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
        a{
        text-decoration: none; 
        outline: none;
        }
        .book-cover {
    width: 100%;
    height: 400px;
    object-fit: cover; /* Recortar la imagen para llenar el contenedor sin distorsionarse */
    border-radius: 8px; /* Bordes redondeados para mejor apariencia */
    margin-bottom: 15px; /* Espacio entre la imagen y el título */
}

.carousel-inner {
    position: relative;
    max-height: 500px;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro para mayor contraste */
    border-radius: 50%; /* Hacer los íconos de navegación redondos */
    padding: 10px;
}

h3.text-primary {
    font-weight: bold;
    margin-bottom: 20px;
}
    </style>
</head>
<body>

<br>
    <!-- Carrousel de los últimos 3 libros agregados -->
    <div id="latestBooksCarousel" class="carousel slide mb-5" data-bs-ride="carousel" style="width: 100%; max-width: 100%; overflow: hidden;">
    <div class="carousel-inner">
        @php
            $ultimosLibros = \App\Models\Libro::latest()->with('autor', 'genero')->take(3)->get();
        @endphp
        @foreach($ultimosLibros as $index => $libro)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <div class="w-100 d-flex justify-content-center align-items-center" style="height: 500px; background-color: #f8f9fa;">
                    <div class="text-center" style="width: 100%; max-width: 300px;">
                        <a href="{{ route('libros.show', $libro->id) }}">
                            <h3 class="text-primary">{{ $libro->titulo }}</h3>
                        </a>
                        @if($libro->caratula && file_exists(public_path($libro->caratula)))
                            <!-- Mostrar la imagen usando la ruta almacenada en la base de datos -->
                            <img class="book-cover img-fluid rounded shadow" src="{{ asset($libro->caratula) }}" alt="Portada de {{ $libro->titulo }}">
                        @else
                            <p>Portada no disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#latestBooksCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#latestBooksCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
    
    <script>
        var myCarousel = document.querySelector('#latestBooksCarousel');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 5000,
            ride: 'carousel'
        });
    </script>
    
    
    <form action="{{ route('libros.filtro') }}" method="GET" class="mb-4 d-flex align-items-end gap-3">
    <!-- Filtro por autor -->
    <div class="mb-3">
        <label for="author_name" class="form-label fw-bold">Buscar por nombre del autor:</label>
        <input type="text" name="author_name" id="author_name" class="form-control" value="{{ request('author_name') }}" placeholder="Nombre del autor">
    </div>
    <!-- Filtro por género -->
    <div class="mb-3">
        <label for="genre" class="form-label fw-bold">Género:</label>
        <select name="genre" id="genre" class="form-select">
            <option value="">Todos</option>
            @foreach($generos as $genero)
                <option value="{{ $genero->id }}" {{ request('genre') == $genero->id ? 'selected' : '' }}>{{ $genero->nombre }}</option>
            @endforeach
        </select>
    </div>
    <!-- Filtro por categoría -->
    <div class="mb-3">
        <label for="category" class="form-label fw-bold">Categoría:</label>
        <select name="category" id="category" class="form-select">
            <option value="">Todas</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ request('category') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Filtrar</button>
</form>



    <!-- Mostrar los libros filtrados -->
  
    <h3>Libros</h3>
    <div class="row">
        @forelse($libros as $libro)
            <div class="col-md-4 mb-4">
                <div class="card h-100 d-flex flex-row">
                    <div class="col-md-4">
                        @if($libro->caratula && file_exists(public_path($libro->caratula)))
                    <!-- Mostrar la imagen usando la ruta almacenada en la base de datos -->
                    <img src="{{ asset($libro->caratula) }}" alt="Portada de {{ $libro->titulo }}" width="100" height="200">
                @else
                    <p>Portada no disponible</p>
                @endif
                    </div>
                    <div class="card-body flex-grow-1">
                        
                        <h5 class="card-title">{{ $libro->titulo }}</h5>
                        <p class="card-text"><strong>Género:</strong> {{ $libro->genero->nombre ?? 'Desconocido' }}</p>
                        <p class="card-text"><strong>Disponibilidad:</strong> 
                            @if($libro->disponible)
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-danger">No Disponible</span>
                            @endif
                        </p>
                        
                        <a href="{{ route('libros.show', $libro->id) }}" class="btn btn-info">Ver más</a>
                    </div>
                    <div class="flex-shrink-0">
                      
                    </div>
                </div>
            </div>
        @empty
            <p>No se encontraron libros.</p>
        @endforelse
    </div>
<div class="d-flex justify-content-center mt-3">
    {{ $libros->links('pagination::bootstrap-4') }}
</div>

    
    
    

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
            @elseif (Auth::guard('web')->check())
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
                        $usuario = Auth::guard('web')->user();
                    @endphp
                    Bienvenido, {{ $usuario->nombre }} |

                    <!-- Botón para cerrar sesión -->
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link">Cerrar sesión</button>
                    </form>
                </div>
            @endif
        
        </div>
    </div>
</body>
</html>





@endsection