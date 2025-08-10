<!-- Modal Create Confirmacion -->
<div class="modal fade" id="createConfirmacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nueva Confirmación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('confirmaciones.store') }}" method="post" id="createConfirmacionForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cve_persona" class="form-label">Persona</label>
                        <select class="form-select" name="cve_persona" id="cve_persona" required>
                            <option value="">Selecciona una Persona</option>
                            @foreach ($personas as $persona)
                            <option value="{{$persona->cve_persona}}">{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona una Persona</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_padre" class="form-label">Padre</label>
                        <select class="form-select" name="Per_cve_padre" id="Per_cve_padre" required>
                            <option value="">Selecciona el Padre</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 1)
                            <option value="{{$persona->cve_persona}}">{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona el Padre</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_madre" class="form-label">Madre</label>
                        <select class="form-select" name="Per_cve_madre" id="Per_cve_madre" required>
                            <option value="">Selecciona la Madre</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 0)
                            <option value="{{$persona->cve_persona}}">{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona la Madre</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_padrino" class="form-label">Padrino</label>
                        <select class="form-select" name="Per_cve_padrino" id="Per_cve_padrino" required>
                            <option value="">Selecciona el Padrino</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 1)
                            <option value="{{$persona->cve_persona}}">{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona el Padrino</div>
                    </div>
                    <div class="mb-3">
                        <label for="Per_cve_madrina" class="form-label">Madrina</label>
                        <select class="form-select" name="Per_cve_madrina" id="Per_cve_madrina" required>
                            <option value="">Selecciona la Madrina</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === 0)
                            <option value="{{$persona->cve_persona}}">{{$persona->nombre}} {{$persona->paterno}} {{$persona->materno}}</option>
                            @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona la Madrina</div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha" id="fecha" required>
                        <div class="invalid-feedback">Selecciona la Fecha</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_sacerdotes" class="form-label">Sacerdote</label>
                        <select class="form-select" name="cve_sacerdotes" id="cve_sacerdotes" required>
                            <option value="">Selecciona un Sacerdote</option>
                            @foreach ($sacerdotes as $sacerdote)
                            <option value="{{$sacerdote->cve_sacerdotes}}">{{$sacerdote->nombre}} {{$sacerdote->paterno}} {{$sacerdote->materno}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona un Sacerdote</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_obispos" class="form-label">Obispo</label>
                        <select class="form-select" name="cve_obispos" id="cve_obispos" required>
                            <option value="">Selecciona un Obispo</option>
                            @foreach ($obispos as $obispo)
                                <option value="{{ $obispo->cve_obispos }}">{{ $obispo->nombre }} {{ $obispo->paterno }} {{ $obispo->materno }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona un Obispo</div>
                    </div>
                    <div class="mb-3">
                        <label for="cve_sacramentos" class="form-label">Tipo de Acta</label>
                        <select class="form-select" name="cve_sacramentos" id="cve_sacramentos" required>
                            @foreach ($sacramentos as $sacramento)
                                @if ($sacramento->nombre === 'Confirmacion')
                                    <option value="{{ $sacramento->cve_sacramentos }}" selected>{{ $sacramento->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Selecciona el Tipo de Acta</div>
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
// Validación del formulario de creación antes de enviarlo
document.getElementById('createConfirmacionForm').addEventListener('submit', function(event) {
    event.preventDefault();
    if (validateCreateForm(this)) {
        this.submit();
    }
});

// Función para validar los campos del formulario de creación
function validateCreateForm(form) {
    var inputs = form.querySelectorAll('input, select');
    var isValid = true;

    inputs.forEach(function(input) {
        if (input.value.trim() === '') {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}
</script>