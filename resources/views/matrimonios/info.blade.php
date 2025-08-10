<!-- Modal Edit Matrimonio -->
<div class="modal fade" id="editMatrimonio{{$matrimonio->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Matrimonio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('matrimonios.update', $matrimonio->id) }}" method="post" id="editMatrimonioForm{{$matrimonio->id}}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cve_persona" class="form-label">Esposo</label>
                        <select class="form-select" name="cve_persona" id="cve_persona" required>
                            <option value="">Selecciona una Persona</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === 1)
                                    <option value="{{$persona->cve_persona}}" {{$matrimonio->cve_persona == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona al Esposo</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_persona1" class="form-label">Esposa</label>
                        <select class="form-select" name="cve_persona1" id="cve_persona1" required>
                            <option value="">Selecciona una Persona</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === 0)
                                    <option value="{{$persona->cve_persona}}" {{$matrimonio->cve_persona1 == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona a la Esposa</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_padre" class="form-label">Padre del Esposo</label>
                        <select class="form-select" name="Per_cve_padre" id="Per_cve_padre" required>
                            <option value="">Selecciona el Padre</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 1)
                                <option value="{{$persona->cve_persona}}" {{$matrimonio->Per_cve_padre == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona al Padre del Esposo</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_madre" class="form-label">Madre del Esposo</label>
                        <select class="form-select" name="Per_cve_madre" id="Per_cve_madre" required>
                            <option value="">Selecciona la Madre</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 0)
                                <option value="{{$persona->cve_persona}}" {{$matrimonio->Per_cve_madre == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona a la Madre del Esposo</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_padre1" class="form-label">Padre de la Esposa</label>
                        <select class="form-select" name="Per_cve_padre1" id="Per_cve_padre1" required>
                            <option value="">Selecciona el Padre</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 1)
                                <option value="{{$persona->cve_persona}}" {{$matrimonio->Per_cve_padre1 == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif  
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona al Padre de la Esposa</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_madre1" class="form-label">Madre de la Esposa</label>
                        <select class="form-select" name="Per_cve_madre1" id="Per_cve_madre1" required>
                            <option value="">Selecciona la Madre</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 0)
                                <option value="{{$persona->cve_persona}}" {{$matrimonio->Per_cve_madre1 == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif 
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona a la Madre de la Esposa</div>
                    </div>
                    <div class="mb-3">
                        <label for="libro" class="form-label">Libro</label>
                        <input type="text" class="form-control" name="libro" id="libro" value="{{$matrimonio->libro}}" required>
                        <div class="invalid-feedback">Ingresa el Libro</div>
                    </div>
                    <div class="mb-3">
                        <label for="foja" class="form-label">Foja</label>
                        <input type="number" class="form-control" name="foja" id="foja" value="{{$matrimonio->foja}}" required>
                        <div class="invalid-feedback">Ingresa la Foja</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_ermitas" class="form-label">Ermita</label>
                        <select class="form-select" name="cve_ermitas" id="cve_ermitas" required>
                            <option value="">Selecciona una Ermita</option>
                            @foreach ($ermitas as $ermita)
                                <option value="{{$ermita->cve_ermitas}}" {{$matrimonio->cve_ermitas == $ermita->cve_ermitas ? 'selected' : ''}}>{{$ermita->nombre}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona una Ermita</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_padrino" class="form-label">Padrino</label>
                        <select class="form-select" name="Per_cve_padrino" id="Per_cve_padrino" required>
                            <option value="">Selecciona el Padrino</option>
                            @foreach ($personas as $persona)
                                <option value="{{$persona->cve_persona}}" {{$matrimonio->Per_cve_padrino == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona al Padrino</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_madrina" class="form-label">Madrina</label>
                        <select class="form-select" name="Per_cve_madrina" id="Per_cve_madrina" required>
                            <option value="">Selecciona la Madrina</option>
                            @foreach ($personas as $persona)
                                <option value="{{$persona->cve_persona}}" {{$matrimonio->Per_cve_madrina == $persona->cve_persona ? 'selected' : ''}}>{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona a la Madrina</div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha" id="fecha" value="{{$matrimonio->fecha}}" required>
                        <div class="invalid-feedback">Selecciona la Fecha</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_sacerdotes" class="form-label">Sacerdote</label>
                        <select class="form-select" name="cve_sacerdotes" id="cve_sacerdotes" required>
                            <option value="">Selecciona un Sacerdote</option>
                            @foreach ($sacerdotes as $sacerdote)
                                <option value="{{$sacerdote->cve_sacerdotes}}" {{$matrimonio->cve_sacerdotes == $sacerdote->cve_sacerdotes ? 'selected' : ''}}>{{$sacerdote->nombre}} {{$sacerdote->paterno}} {{$sacerdote->materno}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona un Sacerdote</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_obispos" class="form-label">Obispo</label>
                        <select class="form-select" name="cve_obispos" id="cve_obispos" required>
                            <option value="">Selecciona un Obispo</option>
                            @foreach ($obispos as $obispo)
                                <option value="{{$obispo->cve_obispos}}" {{$matrimonio->cve_obispos == $obispo->cve_obispos ? 'selected' : ''}}>{{$obispo->nombre}} {{$obispo->paterno}} {{$obispo->materno}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona un Obispo</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_sacramentos" class="form-label">Tipo de Acta</label>
                        <select class="form-select" name="cve_sacramentos" id="cve_sacramentos" required>
                            @foreach ($sacramentos as $sacramento)
                                @if ($sacramento->nombre === 'Matrimonio')
                                    <option value="{{ $sacramento->cve_sacramentos }}" {{ $matrimonio->cve_sacramentos == $sacramento->cve_sacramentos ? 'selected' : '' }}>{{ $sacramento->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona el Tipo de Acta</div>
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

<!-- Modal Delete Matrimonio -->
<div class="modal fade" id="deleteMatrimonio{{$matrimonio->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Matrimonio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('matrimonios.destroy', $matrimonio->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar el matrimonio de <strong>{{$matrimonio->persona->nombre}} {{$matrimonio->persona->paterno}} {{$matrimonio->persona->materno}}</strong> y <strong>{{$matrimonio->persona1->nombre}} {{$matrimonio->persona1->paterno}} {{$matrimonio->persona1->materno}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validación del formulario de edición antes de enviarlo
document.getElementById('editMatrimonioForm{{$matrimonio->id}}').addEventListener('submit', function(event) {
    event.preventDefault();
    if (validateEditForm(this)) {
        this.submit();
    }
});

// Función para validar los campos del formulario de edición
function validateEditForm(form) {
    var selects = form.querySelectorAll('select');
    var inputs = form.querySelectorAll('input');
    var isValid = true;

    selects.forEach(function(select) {
        if (select.value === '') {
            select.classList.add('is-invalid');
            isValid = false;
        } else {
            select.classList.remove('is-invalid');
        }
    });

    inputs.forEach(function(input) {
        if (input.value === '') {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}
</script>
     
     