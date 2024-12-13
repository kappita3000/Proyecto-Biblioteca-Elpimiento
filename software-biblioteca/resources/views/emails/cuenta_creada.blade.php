<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Creada</title>
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
        <h1>¡Hola, {{ $user->nombre }}!</h1>
        <p>Te damos la bienvenida a nuestra plataforma. Tu cuenta ha sido creada con éxito.</p>

        <div class="details">
            <h2>Detalles de tu cuenta</h2>
            <ul>
                <li><strong>Nombre:</strong> {{ $user->nombre }} {{ $user->apellido }}</li>
                <li><strong>Correo:</strong> {{ $user->correo }}</li>
            </ul>
        </div>

        <p>Gracias por registrarte con nosotros.</p>

        <div class="footer">
            <p>Saludos,<br>La administración de la Biblioteca Nuevo Horizonte</p>
        </div>
    </div>
</body>
</html>
