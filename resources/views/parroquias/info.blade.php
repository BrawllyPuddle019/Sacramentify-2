<!-- Modal edit-->
<div class="modal fade" id="editParroquia{{$parroquia->cve_parroquia}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Parroquia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('parroquias.update', $parroquia) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cve_diocesis" class="form-label">Diócesis</label>
                        <select class="form-select" name="cve_diocesis" id="cve_diocesis" aria-describedby="helpId">
                            <option value="">Selecciona una Diócesis</option>
                            @foreach ($diocesis as $diocesis)
                                <option value="{{ $diocesis->cve_diocesis }}" {{ $parroquia->cve_diocesis == $diocesis->cve_diocesis ? 'selected' : '' }}>{{ $diocesis->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cve_municipio" class="form-label">Municipio</label>
                        <select class="form-select" name="cve_municipio" id="cve_municipio" aria-describedby="helpId">
                            <option value="">Selecciona un Municipio</option>
                            @foreach ($municipios as $municipio)
                                <option value="{{ $municipio->cve_municipio }}" {{ $parroquia->cve_municipio == $municipio->cve_municipio ? 'selected' : '' }}>{{ $municipio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" value="{{ $parroquia->nombre }}" />
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion" aria-describedby="helpId" placeholder="" value="{{ $parroquia->direccion }}" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal delete-->
<div class="modal fade" id="deleteParroquia{{$parroquia->cve_parroquia}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Parroquia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('parroquias.destroy', $parroquia) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar la parroquia <strong>{{$parroquia->nombre}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>