<!DOCTYPE html>
<html>
<head>
    <title>Libro Devuelto</title>
</head>
<body>
    <h1>¡Gracias por devolver el libro!</h1>
    <p>Estimado(a) {{ $prestamo->usuario->nombre }}:</p>
    <p>Se ha registrado la devolución del libro <strong>{{ $prestamo->libro->titulo }}</strong>.</p>
    <ul>
        <li><strong>Fecha de Préstamo:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</li>
        <li><strong>Fecha de Devolución:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}</li>
    </ul>
    <p>¡Gracias por usar nuestros servicios!</p>
</body>
</html>
