<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Creada</title>
</head>
<body>
    <h1>¡Hola, {{ $user->nombre }}!</h1>
    <p>Te damos la bienvenida a nuestra plataforma. Tu cuenta ha sido creada con éxito.</p>
    <p>Detalles de tu cuenta:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $user->nombre }} {{ $user->apellido }}</li>
        <li><strong>Correo:</strong> {{ $user->correo }}</li>
    </ul>
    <p>Gracias por registrarte con nosotros.</p>
    <p>Saludos,<br>El equipo de {{ config('app.name') }}</p>
</body>
</html>
