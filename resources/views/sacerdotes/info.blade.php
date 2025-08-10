<!-- Modal edit-->
<div class="modal fade" id="editS{{$sacerdote->cve_sacerdotes}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Sacerdote</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sacerdotes.update', $sacerdote->cve_sacerdotes) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" value="{{$sacerdote->nombre_sacerdote}}" />
                    </div>
                    <div class="mb-3">
                        <label for="paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" name="paterno" id="paterno" aria-describedby="helpId" placeholder="" value="{{$sacerdote->apellido_paterno}}" />
                    </div>
                    <div class="mb-3">
                        <label for="materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" name="materno" id="materno" aria-describedby="helpId" placeholder="" value="{{$sacerdote->apellido_materno}}" />
                    </div>
                    <div class="mb-3">
                        <label for="cve_diocesis" class="form-label">Diócesis</label>
                        <select class="form-select" name="cve_diocesis" id="cve_diocesis" aria-describedby="helpId" required>
                            <option value="">Selecciona una diócesis</option>
                            @foreach($diocesis as $diocesi)
                                <option value="{{ $diocesi->cve_diocesis }}" {{ $sacerdote->cve_diocesis == $diocesi->cve_diocesis ? 'selected' : '' }}>
                                    {{ $diocesi->nombre_diocesis }}
                                </option>
                            @endforeach
                        </select>
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
<div class="modal fade" id="deleteS{{$sacerdote->cve_sacerdotes}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Sacerdote</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sacerdotes.destroy', $sacerdote->cve_sacerdotes) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar al sacerdote <strong>{{$sacerdote->nombre_sacerdote}} {{$sacerdote->apellido_paterno}} {{$sacerdote->apellido_materno}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>