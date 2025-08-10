<!-- Modal Create-->
<div class="modal fade" id="createS" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Sacerdote</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sacerdotes.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" />
                    </div>
                    <div class="mb-3">
                        <label for="paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" name="paterno" id="paterno" aria-describedby="helpId" placeholder="" />
                    </div>
                    <div class="mb-3">
                        <label for="materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" name="materno" id="materno" aria-describedby="helpId" placeholder="" />
                    </div>
                    <div class="mb-3">
                        <label for="cve_diocesis" class="form-label">Diócesis</label>
                        <select class="form-select" name="cve_diocesis" id="cve_diocesis" aria-describedby="helpId" required>
                            <option value="">Selecciona una diócesis</option>
                            @foreach($diocesis as $diocesi)
                                <option value="{{ $diocesi->cve_diocesis }}">{{ $diocesi->nombre_diocesis }}</option>
                            @endforeach
                        </select>
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