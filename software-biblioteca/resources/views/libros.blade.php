{{-- 
@extends('app')


@section('contenent')
    
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Libro</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Ruta al CSS -->
    <script src="{{ asset('js/script.js') }}" defer></script> <!-- Ruta al JS -->
</head>
<body>
    <div class="container">
        <!-- Sección de la imagen y descripción del libro -->
        <div class="book-section">
            <img src="{{ asset('images/libro.jpg') }}" alt="Libro" class="book-image">
            <div class="book-description">
                <!-- Mostrar título y descripción desde la base de datos -->
                <h2>{{ $libro->titulo }}</h2>
                <p><strong>Autor:</strong> {{ $libro->autor->Nombre }} {{ $libro->autor->Apellido }}</p>
                    <p><strong>Género:</strong> {{ $libro->genero->Nombre }}</p>
                    <p><strong>Categoría:</strong> {{ $libro->categoria->Nombre }}</p>
                    <p><strong>Repisa:</strong> {{ $libro->repisa->Numero }} - {{ $libro->repisa->Ubicacion }}</p>
                    <p>{{ $libro->descripcion }}</p>
                    <p><strong>Disponible:</strong> {{ $libro->Disponible ? 'Sí' : 'No' }}</p>
               
                
            </div>
        </div>

        <!-- Botón para abrir el modal -->
        <button id="openModalBtn">Reservar libro</button>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Reservar Libro</h2>
                <form id="reservationForm" method="POST" action="{{ route('reservar.libro') }}">
                    @csrf <!-- Token CSRF de Laravel -->
                    
                    <!-- Campo de Nombre -->
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                    
                    <!-- Campo de Apellido -->
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                    
                    <!-- Campo de Correo -->
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required>
                    
                    <!-- Campo de Fecha de Recogida -->
                    <label for="fecha_prestamo">Fecha de Recogida:</label>
                    <input type="date" id="fecha_prestamo" name="fecha_prestamo" required>
                    
                    <!-- Botón de Envío -->
                    <button type="submit">Reservar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


@endsection
--}}