<!-- Modal Create-->
<div class="modal fade" id="createPlatica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Plática</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('platicas.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="padre" class="form-label">Padre</label>
                        <select class="form-select" name="padre" id="padre" aria-describedby="helpId">
                            <option value="">Selecciona un Padre</option>
                            @foreach ($hombres as $hombre)
                                <option value="{{ $hombre->cve_persona }}">{{ $hombre->nombre }} {{ $hombre->apellido_paterno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="madre" class="form-label">Madre</label>
                        <select class="form-select" name="madre" id="madre" aria-describedby="helpId">
                            <option value="">Selecciona una Madre</option>
                            @foreach ($mujeres as $mujer)
                                <option value="{{ $mujer->cve_persona }}">{{ $mujer->nombre }} {{ $mujer->apellido_paterno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" />
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" placeholder="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>