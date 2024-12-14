<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Rechazo de Préstamo</title>
</head>
<body>
    <h2>Hola {{ $usuario->nombre }} {{ $usuario->apellido }}</h2>
    <p>
        Lamentamos informarte que tu solicitud de préstamo
        @if ($prestamo->titulo_libro && $prestamo->editorial_libro)
            para el libro <strong>"{{ $prestamo->titulo_libro }}"</strong> ({{ $prestamo->editorial_libro }})
        @else
            para un libro cuya información no está disponible
        @endif
        ha sido rechazada.
    </p>

    <p><strong>Motivo del rechazo:</strong></p>
    <blockquote style="color: #d9534f; font-style: italic;">
        {{ $prestamo->motivo_rechazo }}
    </blockquote>

    <p>Gracias por utilizar nuestros servicios.</p>
</body>
</html>
