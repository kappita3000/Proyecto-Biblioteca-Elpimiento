<!DOCTYPE html>
<html>
<head>
    <title>Devolución Pendiente</title>
</head>
<body>
    <h1>Devolución Pendiente</h1>
    <p>Estimado(a) {{ $prestamo->usuario->nombre }}:</p>
    <p>Notamos que aún no has devuelto el libro <strong>{{ $prestamo->libro->titulo }}</strong>.</p>
    <ul>
        <li><strong>Fecha de Préstamo:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</li>
        <li><strong>Fecha de Devolución Estimada:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m/Y') }}</li>
    </ul>
    <p>Te pedimos que lo devuelvas lo antes posible. Si tienes alguna duda, contáctanos.</p>
</body>
</html>
