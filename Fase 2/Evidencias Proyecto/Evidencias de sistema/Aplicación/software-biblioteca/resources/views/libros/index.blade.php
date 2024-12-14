@extends('layouts.lib')


@section('content')


    <!DOCTYPE html>
    <html lang="es">

    <head>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Biblioteca Nuevo Horizonte</title>

        <link rel="stylesheet" href="{{ asset('css/estilolibros.css') }}"> <!-- Ruta al CSS -->
        <script src="{{ asset('js/script.js') }}" defer></script> <!-- Ruta al JS -->
       
    </head>

    <body>

        <br>
        <!-- Carrousel de los últimos 5 libros agregados -->


<h3 class="libros-titulo">Libros</h3>
        <form action="{{ route('libros.filtro') }}" method="GET" class="mb-4 d-flex align-items-end gap-3">
            <!-- Filtro por autor -->
            <div class="mb-3">
                <label for="author_name" class="form-label fw-bold">Buscar por nombre del autor:</label>
                <input type="text" name="author_name" id="author_name" class="form-control"
                    value="{{ request('author_name') }}" placeholder="Nombre del autor">
            </div>
            <!-- Filtro por género -->
            <div class="mb-3">
                <label for="genre" class="form-label fw-bold">Género:</label>
                <select name="genre" id="genre" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($generos as $genero)
                        <option value="{{ $genero->id }}" {{ request('genre') == $genero->id ? 'selected' : '' }}>
                            {{ $genero->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Filtro por categoría -->
            <div class="mb-3">
                <label for="category" class="form-label fw-bold">Categoría:</label>
                <select name="category" id="category" class="form-select">
                    <option value="">Todas</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}"
                            {{ request('category') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"
                style="background-color: #a2ad62 !important; border-color:#a2ad62;">Filtrar</button>
        </form>



        <!-- Mostrar los libros filtrados -->

       

        <div class="row">
            @forelse($libros as $libro)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 d-flex flex-row">
                        <div class="col-md-4">
                            @if ($libro->caratula && file_exists(public_path($libro->caratula)))
                                <!-- Mostrar la imagen usando la ruta almacenada en la base de datos -->
                                <img src="{{ asset($libro->caratula) }}" alt="Portada de {{ $libro->titulo }}">
                            @else
                                <img src="{{ asset('img/placeholder.png') }}" alt="Portada no disponible">
                            @endif
                        </div>

                        <div class="card-body flex-grow-1">
                            <h5 class="card-title">{{ $libro->titulo }}</h5>
                            <p class="card-text"><strong>Género:</strong> {{ $libro->genero->nombre ?? 'Desconocido' }}</p>
                            <p class="card-text"><strong>Categoria:</strong>
                                {{ $libro->categoria->nombre ?? 'Desconocido' }}</p>
                            <p class="card-text"><strong>Disponibilidad:</strong>
                                @if ($libro->disponible)
                                    <span class="badge bg-success">Disponible</span>
                                @else
                                    <span class="badge bg-danger">No Disponible</span>
                                @endif
                            </p>
                            <a href="{{ route('libros.show', $libro->id) }}" class="btn btn-info"
                                style="background-color: #d1d9a6 !important; border-color: #d1d9a6;">Ver más</a>
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


        </div>
        </div>
    </body>

    </html>





@endsection
