<!-- Modal Create-->
<div  class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Persona</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('personas.store') }}" method="post" id="createPersonaForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" required />
                        <div class="invalid-feedback">Ingresa el nombre</div>
                    </div>
                    <div class="mb-3">
                        <label for="paterno" class="form-label">Paterno</label>
                        <input type="text" class="form-control" name="paterno" id="paterno" aria-describedby="helpId" placeholder="" required />
                        <div class="invalid-feedback">Ingresa el apellido paterno</div>
                    </div>
                    <div class="mb-3">
                        <label for="materno" class="form-label">Materno</label>
                        <input type="text" class="form-control" name="materno" id="materno" aria-describedby="helpId" placeholder="" required />
                        <div class="invalid-feedback">Ingresa el apellido materno</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Género</label>
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="sexo" id="masculino" value="1" required>
                          <label class="form-check-label" for="masculino">Masculino</label>
                      </div>
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="sexo" id="femenino" value="0" required>
                          <label class="form-check-label" for="femenino">Femenino</label>
                      </div>
                      <div class="invalid-feedback">Selecciona el género</div>
                  </div>
                  
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" aria-describedby="helpId" placeholder="" required />
                        <div class="invalid-feedback">Selecciona la fecha de nacimiento</div>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion" aria-describedby="helpId" placeholder="" required />
                        <div class="invalid-feedback">Ingresa la dirección</div>
                    </div>
                    <div class="mb-3">
                      <label for="telefono" class="form-label">Teléfono</label>
                      <input type="tel" class="form-control" name="telefono" id="telefono" aria-describedby="helpId" placeholder="Ingrese el número de teléfono" pattern="[0-9]{10}" maxlength="10" required>
                      <div class="invalid-feedback">Ingresa un número de teléfono válido (10 dígitos)</div>
                  </div>
  
                    <div class="mb-3">
                        <label for="cve_municipio" class="form-label">Municipio</label>
                        <select class="form-select" name="cve_municipio" id="cve_municipio" aria-describedby="helpId" required>
                          <option value="">Selecciona un municipio</option>
                          
                          <?php foreach($municipios as $municipio): ?>
                           <option value="<?php echo $municipio->cve_municipio; ?>"><?php echo $municipio->nombre_municipio; ?></option>
                          <?php endforeach; ?>

                          
                      </select>
                      <div class="invalid-feedback">Selecciona un municipio</div>
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
  
  <script>
  // Validación del formulario antes de enviarlo
  document.getElementById('createPersonaForm').addEventListener('submit', function(event) {
      event.preventDefault();
      if (validateForm()) {
          this.submit();
      }
  });
  
  // Función para validar los campos del formulario
  function validateForm() {
      var form = document.getElementById('createPersonaForm');
      var inputs = form.querySelectorAll('input');
      var selects = form.querySelectorAll('select');
      var isValid = true;
  
      inputs.forEach(function(input) {
          if (input.value === '') {
              input.classList.add('is-invalid');
              isValid = false;
          } else {
              input.classList.remove('is-invalid');
          }
      });
  
      selects.forEach(function(select) {
          if (select.value === '') {
              select.classList.add('is-invalid');
              isValid = false;
          } else {
              select.classList.remove('is-invalid');
          }
      });
  
      return isValid;
  }
  </script>