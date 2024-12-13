<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamo Aceptado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #28a745;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .details {
            margin-top: 20px;
        }
        .details ul {
            list-style-type: none;
            padding: 0;
        }
        .details li {
            padding: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tu préstamo ha sido aceptado</h1>
        <p>Hola {{ $prestamo->usuario->nombre }},</p>
        <p>Esto es un correo de confirmación para el préstamo del libro <strong>{{ $prestamo->libro->titulo }}</strong>.</p>

        <div class="details">
            <h2>Detalles del Préstamo</h2>
            <ul>
                <li><strong>Fecha de Préstamo:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</li>
                <li><strong>Fecha de Devolución acordada:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}</li>
            </ul>
        </div>

        <p>Gracias por utilizar nuestros servicios. ¡Esperamos que disfrutes de la lectura!</p>

        <div class="footer">
            <p>Saludos,<br>La admnistracion de la Biblioteca Nuevo Horizonte</p>
        </div>
    </div>
</body>
</html>
