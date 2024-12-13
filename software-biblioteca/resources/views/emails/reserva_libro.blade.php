<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
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
            color: #007bff;
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
        <h1>Confirmación de Reserva</h1>
        <p>Hola {{ $nombreSolicitante }},</p>
        <p>Tu reserva para el libro <strong>{{ $tituloLibro }}</strong> ha sido confirmada.</p>

        <div class="details">
            <h2>Detalles de la Reserva</h2>
            <ul>
                <li><strong>Libro:</strong> {{ $tituloLibro }}</li>
                <li><strong>Fecha de Recojo:</strong> {{ $fechaRecojo }}</li>
            </ul>
        </div>

        <p>Si la solicitud es rechazada antes de esa fecha, te notificaremos.</p>
        <p>Gracias por utilizar nuestros servicios.</p>

        <div class="footer">
            <p>Saludos,<br>La administración de la Biblioteca Nuevo Horizonte</p>
        </div>
    </div>
</body>
</html>
