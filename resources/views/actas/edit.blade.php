{{-- Modal Edit Actas --}}
@foreach($actas as $acta)
<!-- Modal Edit Acta {{ $acta->cve_actas }} -->
<div class="modal fade" id="editActa{{ $acta->cve_actas }}" tabindex="-1" aria-labelledby="editModalLabel{{ $acta->cve_actas }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel{{ $acta->cve_actas }}">Editar Acta #{{ $acta->numero_consecutivo ?? $acta->cve_actas }}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('actas.update', $acta->cve_actas) }}" method="post" id="editActaForm{{ $acta->cve_actas }}">
        @csrf
        @method('PUT')
        
        <div class="modal-body">
          <div class="container-fluid">
            {{-- TIPO DE ACTA --}}
            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="tipo_acta_edit{{ $acta->cve_actas }}" class="form-label">Tipo de Acta</label>
                  <select class="form-select" name="tipo_acta" id="tipo_acta_edit{{ $acta->cve_actas }}" required>
                      <option value="">Selecciona un sacramento</option>
                      @foreach ($sacramentos as $sacramento)
                      @php
                        $tipoMapeado = match(strtolower($sacramento->nombre)) {
                          'matrimonio' => 'matrimonio',
                          'bautismo' => 'bautizo',
                          'confirmación' => 'confirmacion',
                          default => strtolower($sacramento->nombre)
                        };
                      @endphp
                      <option value="{{ $sacramento->cve_sacramentos }}" 
                              data-tipo="{{ $tipoMapeado }}"
                              {{ $acta->tipo_acta == $sacramento->cve_sacramentos ? 'selected' : '' }}>
                          {{ $sacramento->nombre }}
                      </option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>

            {{-- DATOS GENERALES --}}
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="fecha_edit{{ $acta->cve_actas }}" class="form-label">Fecha *</label>
                  <input type="date" class="form-control" name="fecha" id="fecha_edit{{ $acta->cve_actas }}" value="{{ $acta->fecha }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="cve_ermitas_edit{{ $acta->cve_actas }}" class="form-label">Ermita *</label>
                  <select class="form-select" name="cve_ermitas" id="cve_ermitas_edit{{ $acta->cve_actas }}" required>
                    <option value="">Selecciona una ermita</option>
                    @foreach ($ermitas as $ermita)
                    <option value="{{ $ermita->cve_ermitas }}" {{ $acta->cve_ermitas == $ermita->cve_ermitas ? 'selected' : '' }}>
                      {{ $ermita->nombre }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="Libro_edit{{ $acta->cve_actas }}" class="form-label">Libro *</label>
                  <input type="text" class="form-control" name="Libro" id="Libro_edit{{ $acta->cve_actas }}" value="{{ $acta->Libro }}" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="Fojas_edit{{ $acta->cve_actas }}" class="form-label">Fojas *</label>
                  <input type="number" class="form-control" name="Fojas" id="Fojas_edit{{ $acta->cve_actas }}" value="{{ $acta->Fojas }}" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label for="Folio_edit{{ $acta->cve_actas }}" class="form-label">Folio *</label>
                  <input type="number" class="form-control" name="Folio" id="Folio_edit{{ $acta->cve_actas }}" value="{{ $acta->Folio }}" required>
                </div>
              </div>
            </div>

            {{-- SACERDOTES Y OBISPOS --}}
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="cve_sacerdotes_celebrante_edit{{ $acta->cve_actas }}" class="form-label">Sacerdote Celebrante</label>
                  <select class="form-select" name="cve_sacerdotes_celebrante" id="cve_sacerdotes_celebrante_edit{{ $acta->cve_actas }}">
                    <option value="">Selecciona un sacerdote</option>
                    @foreach ($sacerdotes as $sacerdote)
                    <option value="{{ $sacerdote->cve_sacerdotes }}" {{ $acta->cve_sacerdotes_celebrante == $sacerdote->cve_sacerdotes ? 'selected' : '' }}>
                      {{ $sacerdote->nombre }} {{ $sacerdote->apellido_paterno }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="cve_sacerdotes_asistente_edit{{ $acta->cve_actas }}" class="form-label">Sacerdote Asistente</label>
                  <select class="form-select" name="cve_sacerdotes_asistente" id="cve_sacerdotes_asistente_edit{{ $acta->cve_actas }}">
                    <option value="">Selecciona un sacerdote</option>
                    @foreach ($sacerdotes as $sacerdote)
                    <option value="{{ $sacerdote->cve_sacerdotes }}" {{ $acta->cve_sacerdotes_asistente == $sacerdote->cve_sacerdotes ? 'selected' : '' }}>
                      {{ $sacerdote->nombre }} {{ $sacerdote->apellido_paterno }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="cve_obispos_celebrante_edit{{ $acta->cve_actas }}" class="form-label">Obispo Celebrante</label>
                  <select class="form-select" name="cve_obispos_celebrante" id="cve_obispos_celebrante_edit{{ $acta->cve_actas }}">
                    <option value="">Selecciona un obispo</option>
                    @foreach ($obispos as $obispo)
                    <option value="{{ $obispo->cve_obispos }}" {{ $acta->cve_obispos_celebrante == $obispo->cve_obispos ? 'selected' : '' }}>
                      {{ $obispo->nombre }} {{ $obispo->apellido_paterno }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            {{-- CAMPOS ESPECÍFICOS POR TIPO DE ACTA --}}
            
            {{-- MATRIMONIO --}}
            @php
                $isMatrimonio = $acta->tipoActa && strtolower($acta->tipoActa->nombre) === 'matrimonio';
                $isBautismo = $acta->tipoActa && strtolower($acta->tipoActa->nombre) === 'bautismo';
                $isConfirmacion = $acta->tipoActa && strtolower($acta->tipoActa->nombre) === 'confirmación';
            @endphp

            <div id="camposMatrimonio_edit{{ $acta->cve_actas }}" style="display:{{ $isMatrimonio ? 'block' : 'none' }};">
              <!-- Contenido específico de matrimonio -->
              <h5 class="mt-4 mb-3">Datos del Matrimonio</h5>
              
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Esposo *</label>
                    <select class="form-select person-select-edit" name="matrimonio[cve_esposo]" id="cve_persona_matrimonio_edit{{ $acta->cve_actas }}">
                      <option value="">Selecciona al Esposo</option>
                        @foreach ($personas as $persona)
                        @if ($persona->sexo === '1')
                            <option value="{{ $persona->cve_persona }}" 
                                    data-sexo="{{ $persona->sexo }}"
                                    {{ $acta->cve_persona == $persona->cve_persona ? 'selected' : '' }}>
                                {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                            </option>
                        @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Esposa *</label>
                    <select class="form-select person-select-edit" name="matrimonio[cve_esposa]" id="cve_persona2_matrimonio_edit{{ $acta->cve_actas }}">
                      <option value="">Selecciona a la Esposa</option>
                        @foreach ($personas as $persona)
                        @if ($persona->sexo === '2')
                            <option value="{{ $persona->cve_persona }}" 
                                    data-sexo="{{ $persona->sexo }}"
                                    {{ $acta->cve_persona2 == $persona->cve_persona ? 'selected' : '' }}>
                                {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                            </option>
                        @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padres del Esposo -->
              <h6 class="mt-3">Padres del Esposo</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padre del Esposo</label>
                    <select class="form-select" name="matrimonio[padre_esposo]">
                      <option value="">Selecciona al padre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padre == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madre del Esposo</label>
                    <select class="form-select" name="matrimonio[madre_esposo]">
                      <option value="">Selecciona a la madre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madre == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padrinos del Esposo -->
              <h6 class="mt-3">Padrinos del Esposo</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padrino del Esposo</label>
                    <select class="form-select" name="matrimonio[padrino_esposo]">
                      <option value="">Selecciona al padrino</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padrino1 == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madrina del Esposo</label>
                    <select class="form-select" name="matrimonio[madrina_esposo]">
                      <option value="">Selecciona a la madrina</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madrina1 == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padres de la Esposa -->
              <h6 class="mt-3">Padres de la Esposa</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padre de la Esposa</label>
                    <select class="form-select" name="matrimonio[padre_esposa]">
                      <option value="">Selecciona al padre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padre1 == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madre de la Esposa</label>
                    <select class="form-select" name="matrimonio[madre_esposa]">
                      <option value="">Selecciona a la madre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madre1 == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padrinos de la Esposa -->
              <h6 class="mt-3">Padrinos de la Esposa</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padrino de la Esposa</label>
                    <select class="form-select" name="matrimonio[padrino_esposa]">
                      <option value="">Selecciona al padrino</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padrino == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madrina de la Esposa</label>
                    <select class="form-select" name="matrimonio[madrina_esposa]">
                      <option value="">Selecciona a la madrina</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madrina == $persona->cve_persona ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- BAUTISMO --}}
            <div id="camposBautizo_edit{{ $acta->cve_actas }}" style="display:{{ $isBautismo ? 'block' : 'none' }};">
              <h5 class="mt-4 mb-3">Datos del Bautismo</h5>
              
              <div class="row">
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label">Persona que se bautiza *</label>
                    <select class="form-select person-select-edit" name="bautizo[cve_persona]" id="cve_persona_bautizo_edit{{ $acta->cve_actas }}">
                      <option value="">Selecciona a la persona</option>
                      @foreach ($personas as $persona)
                        <option value="{{ $persona->cve_persona }}" 
                                data-sexo="{{ $persona->sexo }}"
                                {{ $acta->cve_persona == $persona->cve_persona ? 'selected' : '' }}>
                            {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padres -->
              <h6 class="mt-3">Padres</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padre</label>
                    <select class="form-select" name="bautizo[padre]">
                      <option value="">Selecciona al padre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        @php
                          $padreSelected = ($acta->Per_cve_padre == $persona->cve_persona) || ($acta->Per_cve_padre1 == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $padreSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madre</label>
                    <select class="form-select" name="bautizo[madre]">
                      <option value="">Selecciona a la madre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        @php
                          $madreSelected = ($acta->Per_cve_madre == $persona->cve_persona) || ($acta->Per_cve_madre1 == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $madreSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padrinos -->
              <h6 class="mt-3">Padrinos</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padrino</label>
                    <select class="form-select" name="bautizo[padrino]">
                      <option value="">Selecciona al padrino</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        @php
                          $padrinoSelected = ($acta->Per_cve_padrino1 == $persona->cve_persona) || ($acta->Per_cve_padrino == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $padrinoSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madrina</label>
                    <select class="form-select" name="bautizo[madrina]">
                      <option value="">Selecciona a la madrina</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        @php
                          $madrinaSelected = ($acta->Per_cve_madrina1 == $persona->cve_persona) || ($acta->Per_cve_madrina == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $madrinaSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- CONFIRMACIÓN --}}
            <div id="camposConfirmacion_edit{{ $acta->cve_actas }}" style="display:{{ $isConfirmacion ? 'block' : 'none' }};">
              <h5 class="mt-4 mb-3">Datos de la Confirmación</h5>
              
              <div class="row">
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label">Persona que se confirma *</label>
                    <select class="form-select person-select-edit" name="confirmacion[cve_persona]" id="cve_persona_confirmacion_edit{{ $acta->cve_actas }}">
                      <option value="">Selecciona a la persona</option>
                      @foreach ($personas as $persona)
                        <option value="{{ $persona->cve_persona }}" 
                                data-sexo="{{ $persona->sexo }}"
                                {{ $acta->cve_persona == $persona->cve_persona ? 'selected' : '' }}>
                            {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padres -->
              <h6 class="mt-3">Padres</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padre</label>
                    <select class="form-select" name="confirmacion[padre]">
                      <option value="">Selecciona al padre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        @php
                          $padreSelected = ($acta->Per_cve_padre == $persona->cve_persona) || ($acta->Per_cve_padre1 == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $padreSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madre</label>
                    <select class="form-select" name="confirmacion[madre]">
                      <option value="">Selecciona a la madre</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        @php
                          $madreSelected = ($acta->Per_cve_madre == $persona->cve_persona) || ($acta->Per_cve_madre1 == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $madreSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <!-- Padrinos -->
              <h6 class="mt-3">Padrinos</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Padrino</label>
                    <select class="form-select" name="confirmacion[padrino]">
                      <option value="">Selecciona al padrino</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '1')
                        @php
                          $padrinoSelected = ($acta->Per_cve_padrino1 == $persona->cve_persona) || ($acta->Per_cve_padrino == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $padrinoSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Madrina</label>
                    <select class="form-select" name="confirmacion[madrina]">
                      <option value="">Selecciona a la madrina</option>
                      @foreach ($personas as $persona)
                      @if ($persona->sexo === '2')
                        @php
                          $madrinaSelected = ($acta->Per_cve_madrina1 == $persona->cve_persona) || ($acta->Per_cve_madrina == $persona->cve_persona);
                        @endphp
                        <option value="{{ $persona->cve_persona }}" {{ $madrinaSelected ? 'selected' : '' }}>
                          {{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                        </option>
                      @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar Acta</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script>
// JavaScript para manejar cambios de tipo en modales de edición
document.addEventListener('DOMContentLoaded', function() {
    // Para cada acta, configurar el comportamiento del modal de edición
    @foreach($actas as $acta)
    (function() {
        const actaId = {{ $acta->cve_actas }};
        const tipoSelect = document.getElementById('tipo_acta_edit' + actaId);
        
        if (tipoSelect) {
            tipoSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const tipo = selectedOption.getAttribute('data-tipo');
                
                // Ocultar todos los campos específicos
                document.getElementById('camposMatrimonio_edit' + actaId).style.display = 'none';
                document.getElementById('camposBautizo_edit' + actaId).style.display = 'none';
                document.getElementById('camposConfirmacion_edit' + actaId).style.display = 'none';
                
                // Mostrar campos específicos según el tipo
                if (tipo === 'matrimonio') {
                    document.getElementById('camposMatrimonio_edit' + actaId).style.display = 'block';
                } else if (tipo === 'bautizo') {
                    document.getElementById('camposBautizo_edit' + actaId).style.display = 'block';
                } else if (tipo === 'confirmacion') {
                    document.getElementById('camposConfirmacion_edit' + actaId).style.display = 'block';
                }
            });
        }
    })();
    @endforeach
});
</script>
