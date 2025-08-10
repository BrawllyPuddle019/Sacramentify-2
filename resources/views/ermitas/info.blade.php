<!-- Modal edit-->
<div class="modal fade" id="editErmita{{$ermita->cve_ermitas}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Ermita</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ermitas.update', $ermita->cve_ermitas) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cve_parroquia" class="form-label">Parroquia</label>
                        <select class="form-select" name="cve_parroquia" id="cve_parroquia" aria-describedby="helpId">
                            <option value="">Selecciona una Parroquia</option>
                            @foreach ($parroquias as $parroquia)
                                <option value="{{ $parroquia->cve_parroquia }}" {{ $ermita->cve_parroquia == $parroquia->cve_parroquia ? 'selected' : '' }}>{{ $parroquia->nombre_parroquia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cve_municipio" class="form-label">Municipio</label>
                        <select class="form-select" name="cve_municipio" id="cve_municipio" aria-describedby="helpId">
                            <option value="">Selecciona un Municipio</option>
                            @foreach ($municipios as $municipio)
                                <option value="{{ $municipio->cve_municipio }}" {{ $ermita->cve_municipio == $municipio->cve_municipio ? 'selected' : '' }}>{{ $municipio->nombre_municipio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" value="{{ $ermita->nombre_ermita }}" />
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion" aria-describedby="helpId" placeholder="" value="{{ $ermita->direccion }}" />
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
<div class="modal fade" id="deleteErmita{{$ermita->cve_ermitas}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Ermita</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ermitas.destroy', $ermita->cve_ermitas) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar la ermita <strong>{{$ermita->nombre_ermita}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>