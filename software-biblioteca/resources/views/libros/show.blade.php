@extends('layouts.lib')


@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $libro->titulo }}</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Ruta al CSS -->
        <script src="{{ asset('js/script.js') }}" defer></script> <!-- Ruta al JS -->
        <style>
            .space-card {
                display: grid;
                grid-template-columns: 2fr 1fr;
                /* Dos columnas: 2 partes para contenido, 1 parte para imagen */
                gap: 20px;
                /* Espacio entre columnas */
                background-color: #f0f8ff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                max-width: 900px;
                /* Ajustar ancho máximo */
                margin: auto;
                /* Centrar el contenedor */
            }

            .card-text {
                font-family: 'Arial', sans-serif;
                font-size: 14px;
                line-height: 1.5;
                color: #333;
                margin: 10px 0;
                padding: 10px;
                background-color: #f9f9f9;
                border-left: 4px solid #007BFF;
                border-radius: 5px;
                max-width: 500px;
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
                overflow-wrap: break-word;
                display: flex;
                justify-content: center;
                align-content: center;

            }

            body {


                height: 100vh;
                /* Asegura que ocupe toda la altura de la ventana */
                margin: 0;
                /* Elimina márgenes por defecto */
            }

            #openModalBtn {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .container {}

            .reserBtn {
                display: flex;
                justify-content: center;
                align-content: center;
                padding: 15px;
                margin: 0;

            }

            #openModalBtn {
                width: 40%;
                padding: 10px;
                margin-top: 10px;
                background-color: #007BFF;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }

            #openModalBtn:hover {
                background-color: #0056b3;
            }

            .portada img {
                width: 255px;
                /* Ajusta el ancho de la imagen */
                height: auto;
                /* Mantiene la proporción de la imagen */
                border-radius: 8px;
                /* Bordes redondeados */
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                /* Sombra opcional */
            }
        </style>


    </head>

    <body>
        <a href="{{ route('libros.index') }}" class="btn">
            <img src="{{ asset('img/atras.png') }}" alt="Volver a la lista"
                style="width: 55px; height: auto; border: 2px solid #ccc; padding: 10px; border-radius: 5px; cursor: pointer;">
        </a>
        <div class="portada">

            <div class="container">
                <div class="space-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $libro->titulo }}</h5>
                        <p class="card-text">{{ $libro->descripcion }}</p>
                        <p><strong>Autor:</strong> {{ $libro->autor?->Nombre ?? 'Desconocido' }}</p>
                        <p><strong>Editorial:</strong> {{ $libro->editorial?->nombre ?? 'Desconocida' }}</p>
                        <p><strong>Género:</strong> {{ $libro->genero?->nombre ?? 'Desconocido' }} {{$libro->categoria?->nombre ?? 'Desconocido'}}</p>
                        <p><strong>Repisa:</strong> {{ $libro->repisa?->nombre ?? 'Desconocida' }}</p>
                        <p><strong>Cantidad:</strong> {{ $libro->cantidad }}</p>
                        <p><strong>Disponible:</strong> {{ $libro->disponible ? 'Sí' : 'No' }}</p>
                        
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
                    <!-- Asegúrate de que el formulario esté aquí -->
                    
                        @csrf
                        <!-- Campos del formulario -->
                        <input type="hidden" name="libro_id" value="{{ $libro->ID }}">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>

                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required>

                        <label for="correo">Correo:</label>
                        <input type="email" id="correo" name="correo" required>

                        <label for="fecha">Fecha de Recogida:</label>
                        <input type="date" id="fecha" name="fecha" required>

                        <button type="submit" class="btn-reservar">Reservar</button>
                    </form>
                </div>
            </div>




        </div>
    </body>

    </html>
@endsection
