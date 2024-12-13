<h3>Préstamos Rechazados</h3>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre del Libro</th>
            <th>Editorial</th>
            <th>Solicitante</th>
            <th>Correo del Solicitante</th>
            <th>Tipo de Usuario</th>
            <th>Fecha Solicitud</th>
            <th>Fecha Rechazo</th>
            <th>Motivo del Rechazo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rechazados as $index => $prestamo)
            <tr>
                <td>{{ ($rechazados->currentPage() - 1) * $rechazados->perPage() + $loop->iteration }}</td>
                <td>{{ $prestamo->titulo_libro }}</td>
                <td>{{ $prestamo->editorial_libro }}</td>
                <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                <td>{{ $prestamo->usuario->correo }}</td>
                <td>{{ $prestamo->usuario->tipo_usuario }}</td>
                <td>{{ \Carbon\Carbon::parse($prestamo->fecha_solicitud)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($prestamo->fecha_rechazo)->format('d-m-Y') }}</td>
                <td>{{ $prestamo->motivo_rechazo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $rechazados->appends(['tab' => 'rechazados'])->links('pagination::bootstrap-4') }}
</div>