 <!-- Modal Create-->
 <div class="modal fade" id="createM" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Municipio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('municipios.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre_municipio" id="nombre" aria-describedby="helpId" placeholder="" />
                    </div>
  
                    <div class="mb-3">
                        <label for="cve_estado" class="form-label">Estado</label>
                        <select class="form-select" name="cve_estado" id="cve_estado" aria-describedby="helpId">
                          <option value="">Selecciona un Estado</option>
                          
                          @foreach($estados as $estado)
                           <option value="{{ $estado->cve_estado }}">{{ $estado->nombre }}</option>
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