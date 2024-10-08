<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por su reserva</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Redirección automática después de 10 segundos -->
    <meta http-equiv="refresh" content="5; url={{ route('libros') }}">
</head>
<body>
    <div class="container">
        <h1>¡Gracias por su reserva!</h1>
        <p>Por favor, vaya el día acordado a recoger su libro.</p>
    </div>
</body>
</html>
