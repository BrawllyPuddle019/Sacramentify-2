<!-- Modal edit-->
<div class="modal fade" id="edit{{$persona->cve_persona}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('personas.update', $persona->cve_persona) }}" method="post" id="editPersonaForm{{$persona->cve_persona}}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" value="{{$persona->nombre}}" required />
                        <div class="invalid-feedback">Ingresa el nombre</div>
                    </div>
                    <div class="mb-3">
                        <label for="paterno" class="form-label">Paterno</label>
                        <input type="text" class="form-control" name="paterno" id="paterno" aria-describedby="helpId" placeholder="" value="{{$persona->apellido_paterno}}" required />
                        <div class="invalid-feedback">Ingresa el apellido paterno</div>
                    </div>
                    <div class="mb-3">
                        <label for="materno" class="form-label">Materno</label>
                        <input type="text" class="form-control" name="materno" id="materno" aria-describedby="helpId" placeholder="" value="{{$persona->apellido_materno}}" required />
                        <div class="invalid-feedback">Ingresa el apellido materno</div>
                    </div>
                    <div class="mb-3">
                        <label for="sexo" class="form-label">Sexo</label>
                        <select class="form-select" name="sexo" id="sexo" aria-describedby="helpId" required>
                            <option value="1" {{ $persona->sexo == '1' || $persona->sexo == 1 ? 'selected' : '' }}>Masculino</option>
                            <option value="0" {{ $persona->sexo == '0' || $persona->sexo == 0 ? 'selected' : '' }}>Femenino</option>
                        </select>
                        <div class="invalid-feedback">Selecciona el sexo</div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" aria-describedby="helpId" placeholder="" value="{{$persona->fecha_nacimiento}}" required />
                        <div class="invalid-feedback">Selecciona la fecha de nacimiento</div>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion" aria-describedby="helpId" placeholder="" value="{{$persona->direccion}}" required />
                        <div class="invalid-feedback">Ingresa la dirección</div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" id="telefono" aria-describedby="helpId" placeholder="Ingrese el número de teléfono" pattern="[0-9]{10}" maxlength="10" required value="{{$persona->telefono}}">
                        <div class="invalid-feedback">Ingresa un número de teléfono válido (10 dígitos)</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_municipio" class="form-label">Municipio</label>
                        <select class="form-select" name="cve_municipio" id="cve_municipio" aria-describedby="helpId" required>
                            <option value="">Selecciona un municipio</option>
                            <?php foreach($municipios as $municipio): ?>
                                <?php if ($municipio->cve_municipio == $persona->cve_municipio): ?>
                                    <option value="<?php echo $municipio->cve_municipio; ?>" selected><?php echo $municipio->nombre_municipio; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $municipio->cve_municipio; ?>"><?php echo $municipio->nombre_municipio; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Selecciona un municipio</div>
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
<div class="modal fade" id="delete{{$persona->cve_persona}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('personas.destroy', $persona->cve_persona) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar a <strong>{{$persona->nombre}} {{$persona->apellido_paterno}} {{$persona->apellido_materno}}?</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validación del formulario de edición antes de enviarlo
document.getElementById('editPersonaForm{{$persona->cve_persona}}').addEventListener('submit', function(event) {
    event.preventDefault();
    if (validateEditForm(this)) {
        this.submit();
    }
});

// Función para validar los campos del formulario de edición
function validateEditForm(form) {
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


  