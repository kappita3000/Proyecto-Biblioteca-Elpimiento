<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Rechazo de Préstamo</title>
</head>
<body>
    <h2>Hola {{ $usuario->nombre }} {{ $usuario->apellido }},</h2>

    <p>Lamentamos informarte que tu solicitud de préstamo para el libro <strong>"{{ $libro->titulo }}"</strong> ha sido rechazada.</p>

    <p><strong>Motivo del rechazo:</strong></p>
    <blockquote style="color: #d9534f; font-style: italic;">
        {{ $motivo }}
    </blockquote>

    <p>Si tienes alguna pregunta o deseas más información, no dudes en ponerte en contacto con nosotros.</p>

    <p>Gracias por tu comprensión.</p>

    <p>Atentamente,</p>
    <p><strong>Equipo de la Biblioteca</strong></p>
</body>
</html>
