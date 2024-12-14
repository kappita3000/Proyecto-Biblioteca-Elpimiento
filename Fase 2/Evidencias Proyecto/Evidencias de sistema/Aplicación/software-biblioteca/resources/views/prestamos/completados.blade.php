<h3>Préstamos Completados</h3>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre del Libro</th>
            <th>Editorial</th>
            <th>Solicitante</th>
            <th>Correo del Solicitante</th>
            <th>Tipo de Usuario</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Devuelto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($completados as $index => $prestamo)
            <tr>
                <td>{{ ($completados->currentPage() - 1) * $completados->perPage() + $loop->iteration }}</td>
                <td>{{ $prestamo->titulo_libro }}</td>
                <td>{{ $prestamo->editorial_libro }}</td>
                <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                <td>{{ $prestamo->usuario->correo }}</td>
                <td>{{ $prestamo->usuario->tipo_usuario }}</td>
                <td>{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d-m-Y') }}</td>
                <td>{{ $prestamo->devuelto }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $completados->appends(['tab' => 'completados'])->links('pagination::bootstrap-4') }}
</div>