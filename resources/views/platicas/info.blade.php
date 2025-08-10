<!-- Modal edit-->
<div class="modal fade" id="editPlatica{{$platica->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Plática</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('platicas.update', $platica->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="padre" class="form-label">Padre</label>
                        <select class="form-select" name="padre" id="padre" aria-describedby="helpId">
                            <option value="">Selecciona un Padre</option>
                            @foreach ($hombres as $hombre)
                                <option value="{{ $hombre->cve_persona }}" {{ $platica->padre == $hombre->cve_persona ? 'selected' : '' }}>{{ $hombre->nombre }} {{ $hombre->apellido_paterno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="madre" class="form-label">Madre</label>
                        <select class="form-select" name="madre" id="madre" aria-describedby="helpId">
                            <option value="">Selecciona una Madre</option>
                            @foreach ($mujeres as $mujer)
                                <option value="{{ $mujer->cve_persona }}" {{ $platica->madre == $mujer->cve_persona ? 'selected' : '' }}>{{ $mujer->nombre }} {{ $mujer->apellido_paterno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" value="{{ $platica->nombre }}" />
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" placeholder="" value="{{ $platica->fecha }}" />
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
<div class="modal fade" id="deletePlatica{{$platica->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Plática</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('platicas.destroy', $platica->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar la plática <strong>{{$platica->nombre}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>