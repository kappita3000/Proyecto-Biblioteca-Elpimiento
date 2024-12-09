<h3>Préstamos No Devueltos</h3>
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
        @foreach ($noDevueltos as $index => $prestamo)
        <tr>
            <td>{{ ($noDevueltos->currentPage() - 1) * $noDevueltos->perPage() + $loop->iteration }}</td>
            <td>{{ $prestamo->libro->titulo }}</td>
            <td>{{ $prestamo->libro->editorial->nombre }}</td>
            <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
            <td>{{ $prestamo->usuario->correo }}</td>
            <td>{{ $prestamo->usuario->tipo_usuario }}</td>
            <td>{{ $prestamo->fecha_prestamo }}</td>
            <td>{{ $prestamo->fecha_devolucion }}</td>
            <td>{{ $prestamo->devuelto }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $noDevueltos->appends(['tab' => 'noDevueltos'])->links('pagination::bootstrap-4') }}
</div>