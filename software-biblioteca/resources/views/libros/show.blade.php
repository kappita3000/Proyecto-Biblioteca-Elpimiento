@extends('layouts.lib')


@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $libro->titulo }}</title>
        <link rel="stylesheet" href="{{ asset('css/estiloshow.css') }}"> <!-- Ruta al CSS -->
        <script src="{{ asset('js/script.js') }}" defer></script> <!-- Ruta al JS -->

    </head>

    <body>
        <a href="{{ route('libros.index') }}" class="btn">
            <img src="{{ asset('img/atras.png') }}" alt="Volver a la lista"
                style="width: 55px; height: auto; border: 2px solid #ccc; padding: 10px; border-radius: 5px; cursor: pointer;">
        </a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="portada">
            <div class="container">
                <div class="space-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $libro->titulo }}</h5>
                        <p class="card-text">{{ $libro->descripcion }}</p>

                        <p><strong>Autor:</strong> {{ $libro->autor ? $libro->autor->nombre : 'Desconocido' }}</p>
                        <p><strong>Editorial:</strong> {{ $libro->editorial ? $libro->editorial->nombre : 'Desconocida' }}
                        </p>
                        <p><strong>Género:</strong> {{ $libro->genero ? $libro->genero->nombre : 'Desconocido' }}</p>
                        <p><strong>Categoría:</strong> {{ $libro->categoria ? $libro->categoria->nombre : 'Desconocida' }}
                        </p>
                        <p><strong>Repisa:</strong> {{ $libro->repisa ? $libro->repisa->numero : 'Desconocida' }}</p>
                        <p><strong>Cantidad:</strong> {{ $libro->cantidad }}</p>
                        <p>
                            <strong>Disponibilidad:</strong>
                            @if ($libro->disponible)
                                <span class="text-success">Disponible</span>
                            @else
                                <span class="text-danger">No Disponible</span>
                            @endif
                        </p>
                    </div>
                    @if ($libro->caratula && file_exists(public_path($libro->caratula)))
                        <!-- Mostrar la imagen usando la ruta almacenada en la base de datos -->
                        <img src="{{ asset($libro->caratula) }}" alt="Portada de {{ $libro->titulo }}" width="100"
                            height="200">
                    @else
                        <img src="{{ asset('img/placeholder.png') }}" alt="Portada no disponible">
                    @endif
                </div>

            </div>

        </div>


        <!-- Botón para abrir el modal -->
        <div class="reserBtn">
            <button id="openModalBtn">Reservar libro</button>

            <!-- Modal -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Reservar Libro</h2>

                    <div class="modal-body">
                        <!-- Verificamos si el usuario está autenticado -->
                        @auth
                            <form action="{{ route('reservar.libro') }}" method="POST">
                                @csrf
                                <!-- ID del libro -->
                                <input type="hidden" name="id_libro" value="{{ $libro->id }}">

                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Nombre del usuario</label>
                                    <!-- Mostrar el nombre del usuario autenticado -->
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario"
                                        value="{{ Auth::user()->nombre }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="correoUsuario" class="form-label">Correo electrónico</label>
                                    <!-- Mostrar el correo del usuario autenticado -->
                                    <input type="email" class="form-control" id="correoUsuario" name="correoUsuario"
                                        value="{{ Auth::user()->correo }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="fechaRecojo" class="form-label">Fecha de recojo</label>
                                    <input type="date" class="form-control" id="fechaRecojo" name="fecha_recoLibro" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Reservar</button>
                            </form>
                        @endauth
                        @guest
                            <!-- Si el usuario no está autenticado, mostramos un formulario para ingresar sus datos -->
                            <form action="{{ route('reservar.libro') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_libro" value="{{ $libro->id }}">
                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario"
                                        placeholder="Ingresa tu nombre" required>
                                </div>

                                <div class="mb-3">
                                    <label for="apellidoUsuario" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellidoUsuario" name="apellidoUsuario"
                                        placeholder="Ingresa tu apellido" required>
                                </div>

                                <div class="mb-3">
                                    <label for="correoUsuario" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="correoUsuario" name="correoUsuario"
                                        placeholder="Ingresa tu correo electrónico" required>
                                </div>
                                <!-- Campo oculto para el tipo de usuario no registrado -->
                                <input type="hidden" name="tipo_usuario" value="No Registrado">
                                <div class="mb-3">
                                    <label for="fechaRecojo" class="form-label">Fecha de recojo</label>
                                    <input type="date" class="form-control" id="fechaRecojo" name="fecha_recoLibro"
                                        required>
                                </div>

                                <button type="submit" class="btn btn-primary">Reservar</button>
                            </form>
                        @endguest

                    </div>
                </div>
            </div>




        </div>


        </div>


    </body>

    </html>
@endsection
