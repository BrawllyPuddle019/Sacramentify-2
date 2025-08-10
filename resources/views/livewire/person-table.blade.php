<div>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de Nacimiento</th>
                <th>Sexo</th>
                <th>Municipio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personas as $persona)
                <tr>
                    <td>{{ $persona->nombre }}</td>
                    <td>{{ $persona->apellido }}</td>
                    <td>{{ $persona->fecha_nacimiento }}</td>
                    <td>{{ $persona->sexo }}</td>
                    <td>{{ $persona->municipio->nombre }}</td>
                    <td>{{ $persona->municipio->estado->nombre }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
