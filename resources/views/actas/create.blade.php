<!-- Modal Create Acta -->
<div class="modal fade" id="createActa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white border-0">
        <div class="d-flex align-items-center">
          <i class="fas fa-plus-circle me-2 fs-4"></i>
          <div>
            <h1 class="modal-title fs-5 mb-0" id="exampleModalLabel">Nueva Acta Sacramental</h1>
            <small class="opacity-75">Complete la información del sacramento</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('actas.store') }}" method="post" id="createActaForm">
        @csrf
        <input type="hidden" name="tipo_acta_real" id="tipo_acta_real">
        
        <div class="modal-body bg-light">
          <div class="container-fluid">
            {{-- PASO 1: TIPO DE ACTA --}}
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                  <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <span class="fw-bold">1</span>
                  </div>
                  <div>
                    <h5 class="mb-0 text-primary">Seleccionar Sacramento</h5>
                    <small class="text-muted">Elige el tipo de acta sacramental a registrar</small>
                  </div>
                </div>
              </div>
              <div class="card-body bg-white">
                <div class="row">
                  <div class="col-12">
                    <div class="mb-3">
                      <label for="tipo_acta" class="form-label fw-semibold">
                        <i class="fas fa-church text-primary me-2"></i>Tipo de Sacramento *
                      </label>
                      <select class="form-select form-select-lg border-2" name="tipo_acta" id="tipo_acta" required>
                          <option value="">🔽 Selecciona un sacramento...</option>
                          @foreach ($sacramentos as $sacramento)
                          @php
                            $tipoMapeado = match(strtolower($sacramento->nombre)) {
                              'matrimonio' => 'matrimonio',
                              'bautismo' => 'bautizo',
                              'confirmación' => 'confirmacion',
                              default => strtolower($sacramento->nombre)
                            };
                            $icono = match(strtolower($sacramento->nombre)) {
                              'matrimonio' => '💒',
                              'bautismo' => '👶',
                              'confirmación' => '✝️',
                              default => '📜'
                            };
                          @endphp
                          <option value="{{ $sacramento->cve_sacramentos }}" data-tipo="{{ $tipoMapeado }}">{{ $icono }} {{ $sacramento->nombre }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- PASO 2: CAMPOS ESPECÍFICOS POR TIPO DE ACTA --}}
            <div class="card border-0 shadow-sm mb-4" id="seccionCamposEspecificos" style="display: none;">
              <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <span class="fw-bold">2</span>
                  </div>
                  <div>
                    <h5 class="mb-0 text-success">Información de Personas</h5>
                    <small class="text-muted">Complete los datos de las personas involucradas</small>
                  </div>
                </div>
              </div>
              <div class="card-body bg-white">
                
                <!-- ALERTA DE DUPLICADOS -->
                <div id="alertaDuplicado" class="alert alert-warning alert-dismissible fade" role="alert" style="display: none;">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                    <div>
                      <h6 class="alert-heading mb-1">⚠️ Acta Duplicada Detectada</h6>
                      <p class="mb-0" id="mensajeDuplicado"></p>
                      <small class="text-muted">Esta persona ya tiene un acta registrada para este sacramento.</small>
                    </div>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            <div id="camposMatrimonio" style="display:none;">
              <div class="alert alert-info border-0 mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Matrimonio:</strong> Seleccione al esposo y esposa, junto con sus respectivos padrinos y padres.
              </div>
              <!-- CONTRAYENTES -->
              <div class="row g-4 mb-4">
                <div class="col-lg-6">
                  <div class="card h-100 border border-primary border-2">
                    <div class="card-header bg-primary text-white py-2">
                      <h6 class="mb-0"><i class="fas fa-male me-2"></i>Esposo</h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label fw-semibold">Esposo *</label>
                        <select class="form-select person-select" name="matrimonio[cve_esposo]" id="cve_persona_matrimonio" required>
                          <option value="">👤 Selecciona al Esposo</option>
                            @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}" data-sexo="{{ $persona->sexo }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="card h-100 border border-danger border-2">
                    <div class="card-header bg-danger text-white py-2">
                      <h6 class="mb-0"><i class="fas fa-female me-2"></i>Esposa</h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label fw-semibold">Esposa *</label>
                        <select class="form-select person-select" name="matrimonio[cve_esposa]" id="cve_persona2" required>
                            <option value="">👤 Selecciona a la Esposa</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === '0')
                                    <option value="{{ $persona->cve_persona }}" data-sexo="{{ $persona->sexo }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- PADRINOS Y MADRINAS -->
              <div class="row g-4 mb-4">
                <div class="col-lg-6">
                  <div class="card border border-info border-2">
                    <div class="card-header bg-info text-white py-2">
                      <h6 class="mb-0"><i class="fas fa-hands-helping me-2"></i>Padrinos del Esposo</h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label">👨 Padrino</label>
                        <select class="form-select person-select" name="matrimonio[padrino_esposo]">
                            <option value="">Selecciona Padrino</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === '1')
                                    <option value="{{ $persona->cve_persona }}" data-sexo="{{ $persona->sexo }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">👩 Madrina</label>
                        <select class="form-select person-select" name="matrimonio[madrina_esposo]">
                            <option value="">Selecciona Madrina</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === '0')
                                    <option value="{{ $persona->cve_persona }}" data-sexo="{{ $persona->sexo }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="card border border-warning border-2">
                    <div class="card-header bg-warning text-dark py-2">
                      <h6 class="mb-0"><i class="fas fa-hands-helping me-2"></i>Padrinos de la Esposa</h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label">👨 Padrino</label>
                        <select class="form-select" name="matrimonio[padrino_esposa]">
                            <option value="">Selecciona Padrino</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === '1')
                                    <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">👩 Madrina</label>
                        <select class="form-select" name="matrimonio[madrina_esposa]">
                            <option value="">Selecciona Madrina</option>
                            @foreach ($personas as $persona)
                                @if ($persona->sexo === '0')
                                    <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              {{-- PADRES DEL ESPOSO --}}
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Padre Esposo</label>
                    <select class="form-select" name="matrimonio[padre_esposo]">
                        <option value="">Selecciona Padre Esposo</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Madre Esposo</label>
                    <select class="form-select" name="matrimonio[madre_esposo]">
                        <option value="">Selecciona Madre Esposo</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '0')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              
              {{-- PADRES DE LA ESPOSA --}}
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Padre Esposa</label>
                    <select class="form-select" name="matrimonio[padre_esposa]">
                        <option value="">Selecciona Padre Esposa</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Madre Esposa</label>
                    <select class="form-select" name="matrimonio[madre_esposa]">
                        <option value="">Selecciona Madre Esposa</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '0')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div id="camposBautizo" style="display:none;">
              <div class="row">
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label">Persona que se bautiza *</label>
                    <select class="form-select" name="bautizo[cve_persona]" id="cve_persona_bautizo" required>
                        <option value="">Selecciona una Persona</option>
                        @foreach ($personas as $persona)
                            <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Padrino</label>
                    <select class="form-select" name="bautizo[padrino]">
                        <option value="">Selecciona Padrino</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Madrina</label>
                    <select class="form-select" name="bautizo[madrina]">
                        <option value="">Selecciona Madrina</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '0')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Padre</label>
                    <select class="form-select" name="bautizo[padre]">
                        <option value="">Selecciona el Padre</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Madre</label>
                    <select class="form-select" name="bautizo[madre]">
                        <option value="">Selecciona la Madre</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '0')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div id="camposConfirmacion" style="display:none;">
              <div class="row">
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label">Persona que se confirma *</label>
                    <select class="form-select" name="confirmacion[cve_persona]" id="cve_persona_confirmacion" required>
                        <option value="">Selecciona una Persona</option>
                        @foreach ($personas as $persona)
                            <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              
              {{-- PADRES DEL CONFIRMANDO --}}
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Padre del Confirmando</label>
                    <select class="form-select" name="confirmacion[padre]">
                        <option value="">Selecciona el Padre</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Madre del Confirmando</label>
                    <select class="form-select" name="confirmacion[madre]">
                        <option value="">Selecciona la Madre</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '0')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              
              {{-- PADRINOS DE CONFIRMACIÓN --}}
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Padrino de Confirmación</label>
                    <select class="form-select" name="confirmacion[padrino]">
                        <option value="">Selecciona Padrino de Confirmación</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '1')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="mb-3">
                    <label class="form-label">Madrina de Confirmación</label>
                    <select class="form-select" name="confirmacion[madrina]">
                        <option value="">Selecciona Madrina de Confirmación</option>
                        @foreach ($personas as $persona)
                            @if ($persona->sexo === '0')
                                <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
              </div>
            </div>

            {{-- PASO 3: CAMPOS GENERALES --}}
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                  <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <span class="fw-bold">3</span>
                  </div>
                  <div>
                    <h5 class="mb-0 text-warning">Datos del Sacramento</h5>
                    <small class="text-muted">Complete la información general del sacramento</small>
                  </div>
                </div>
              </div>
              <div class="card-body bg-white">

            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="cve_ermitas" class="form-label fw-semibold">
                    <i class="fas fa-church text-warning me-2"></i>Ermita
                  </label>
                  <select class="form-select" name="cve_ermitas" id="cve_ermitas">
                    <option value="">🏛️ Selecciona la Ermita...</option>
                    @foreach ($ermitas as $ermita)
                      <option value="{{ $ermita->cve_ermitas }}">{{ $ermita->nombre_ermita }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                  <label for="cve_sacerdotes_celebrante" class="form-label fw-semibold">
                    <i class="fas fa-user-tie text-warning me-2"></i>Sacerdote Celebrante
                  </label>
                  <select class="form-select" name="cve_sacerdotes_celebrante" id="cve_sacerdotes_celebrante">
                    <option value="">👨‍💼 Selecciona el Sacerdote...</option>
                    @foreach ($sacerdotes as $sacerdote)
                      <option value="{{ $sacerdote->cve_sacerdotes }}">{{ $sacerdote->nombre_sacerdote }} {{ $sacerdote->apellido_paterno }} {{ $sacerdote->apellido_materno }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                  <label for="cve_sacerdotes_asistente" class="form-label fw-semibold">
                    <i class="fas fa-user-friends text-warning me-2"></i>Sacerdote Asistente
                  </label>
                  <select class="form-select" name="cve_sacerdotes_asistente" id="cve_sacerdotes_asistente">
                    <option value="">👨‍💼 Selecciona el Sacerdote (Opcional)...</option>
                    @foreach ($sacerdotes as $sacerdote)
                      <option value="{{ $sacerdote->cve_sacerdotes }}">{{ $sacerdote->nombre_sacerdote }} {{ $sacerdote->apellido_paterno }} {{ $sacerdote->apellido_materno }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="cve_obispos_celebrante" class="form-label fw-semibold">
                    <i class="fas fa-crown text-warning me-2"></i>Obispo Celebrante
                  </label>
                  <select class="form-select" name="cve_obispos_celebrante" id="cve_obispos_celebrante">
                    <option value="">👑 Selecciona el Obispo (Opcional)...</option>
                    @foreach ($obispos as $obispo)
                      <option value="{{ $obispo->cve_obispos }}">{{ $obispo->nombre_obispo }} {{ $obispo->apellido_paterno }} {{ $obispo->apellido_materno }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                  <label for="fecha" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt text-warning me-2"></i>Fecha del Sacramento *
                  </label>
                  <input type="date" class="form-control form-control-lg" name="fecha" id="fecha" required>
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                  <label for="Libro" class="form-label fw-semibold">
                    <i class="fas fa-book text-warning me-2"></i>Libro *
                  </label>
                  <input type="text" class="form-control form-control-lg" name="Libro" id="Libro" placeholder="Ej: Libro I" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                  <label for="Fojas" class="form-label fw-semibold">
                    <i class="fas fa-file-alt text-warning me-2"></i>Fojas *
                  </label>
                  <input type="number" class="form-control form-control-lg" name="Fojas" id="Fojas" placeholder="Número de fojas" required>
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                  <label for="Folio" class="form-label fw-semibold">
                    <i class="fas fa-hashtag text-warning me-2"></i>Folio *
                  </label>
                  <input type="number" class="form-control form-control-lg" name="Folio" id="Folio" placeholder="Número de folio" required>
                </div>
              </div>
            </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-0 d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save me-2"></i>Guardar Acta
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const tipoActaSelect = document.getElementById('tipo_acta');
  const tipoActaReal = document.getElementById('tipo_acta_real');
  const seccionCamposEspecificos = document.getElementById('seccionCamposEspecificos');
  const secciones = {
    matrimonio: document.getElementById('camposMatrimonio'),
    bautizo: document.getElementById('camposBautizo'),
    confirmacion: document.getElementById('camposConfirmacion')
  };

  // Ocultar todas las secciones al inicio
  Object.values(secciones).forEach(sec => sec.style.display = 'none');
  seccionCamposEspecificos.style.display = 'none';

  tipoActaSelect.addEventListener('change', function() {
    // Ocultar todas las secciones
    Object.values(secciones).forEach(sec => sec.style.display = 'none');
    
    // Remover required de todos los campos específicos
    document.querySelectorAll('#camposBautizo [required], #camposConfirmacion [required], #camposMatrimonio [required]').forEach(field => {
      field.removeAttribute('required');
    });
    
    // Guardar valor real para validación
    tipoActaReal.value = this.value;

    // Buscar el tipo de acta seleccionada (matrimonio, bautizo, confirmacion)
    const selectedOption = this.options[this.selectedIndex];
    const tipoTexto = selectedOption.dataset.tipo; // ejemplo: 'matrimonio'

    if (tipoTexto && secciones[tipoTexto]) {
      // Mostrar la sección principal de campos específicos
      seccionCamposEspecificos.style.display = 'block';
      // Mostrar la sección específica del sacramento
      secciones[tipoTexto].style.display = 'block';
      
      // Agregar required solo a los campos del tipo seleccionado
      if (tipoTexto === 'matrimonio') {
        document.getElementById('cve_persona_matrimonio').setAttribute('required', 'required');
        document.querySelector('select[name="matrimonio[cve_esposa]"]').setAttribute('required', 'required');
      } else if (tipoTexto === 'bautizo') {
        document.getElementById('cve_persona_bautizo').setAttribute('required', 'required');
      } else if (tipoTexto === 'confirmacion') {
        document.getElementById('cve_persona_confirmacion').setAttribute('required', 'required');
      }
    } else {
      // Ocultar la sección principal si no hay tipo seleccionado
      seccionCamposEspecificos.style.display = 'none';
    }
  });

  // Limpiar formulario al cerrar el modal
  document.addEventListener('DOMContentLoaded', function() {
    // Verificar que jQuery esté disponible
    if (typeof $ !== 'undefined') {
      $('#createActa').on('hidden.bs.modal', function() {
        document.getElementById('createActaForm').reset();
        // Ocultar todas las secciones
        Object.values(secciones).forEach(sec => sec.style.display = 'none');
        // Ocultar la sección principal de campos específicos
        seccionCamposEspecificos.style.display = 'none';
        
        // Remover required de todos los campos específicos
        document.querySelectorAll('#camposBautizo [required], #camposConfirmacion [required], #camposMatrimonio [required]').forEach(field => {
          field.removeAttribute('required');
        });
        
        // Limpiar Select2 si existe
        $('#createActa .person-select').each(function() {
          if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
          }
        });
      });

      // Inicializar Select2 cuando se abre el modal
      $('#createActa').on('shown.bs.modal', function() {
        setTimeout(() => {
          if ($('#createActa .person-select').length > 0) {
            initializeSelect2();
          }
        }, 200);
      });

      // Función para inicializar Select2
      function initializeSelect2() {
        $('#createActa .person-select').select2({
          theme: 'bootstrap-5',
          dropdownParent: $('#createActa'),
          placeholder: 'Buscar persona...',
          allowClear: true,
          language: {
            noResults: function() {
              return "No se encontraron resultados";
            },
            searching: function() {
              return "Buscando...";
            }
          },
          minimumInputLength: 2
        });
      }
    } else {
      console.warn('jQuery no está disponible para el modal de creación de actas');
    }
  });

  // Función para verificar duplicados en tiempo real
  function verificarDuplicados() {
    console.log('🔍 Iniciando verificación de duplicados...');
    
    const tipoSacramento = document.querySelector('input[name="tipo_sacramento"]:checked')?.value;
    console.log('📋 Tipo de sacramento:', tipoSacramento);
    
    let personaId = null;
    let personaSelect = null;
    
    // Obtener el campo de persona según el tipo de sacramento
    switch(tipoSacramento) {
      case 'Matrimonio':
        personaSelect = document.querySelector('#cve_persona_matrimonio');
        break;
      case 'Bautismo':
        personaSelect = document.querySelector('#cve_persona_bautizo');
        break;
      case 'Confirmación':
        personaSelect = document.querySelector('#cve_persona_confirmacion');
        break;
    }
    
    if (personaSelect) {
      personaId = personaSelect.value;
      console.log('👤 Persona ID:', personaId);
    } else {
      console.log('❌ No se encontró el campo de persona para:', tipoSacramento);
    }
    
    const fechaSacramento = document.querySelector('#fecha')?.value;
    console.log('📅 Fecha sacramento:', fechaSacramento);
    
    const alertContainer = document.getElementById('duplicate-alert');
    console.log('📢 Alert container encontrado:', alertContainer ? 'Sí' : 'No');

    // Ocultar alerta inicialmente
    if (alertContainer) {
      alertContainer.style.display = 'none';
    }

    if (!tipoSacramento || !personaId || !fechaSacramento) {
      console.log('⚠️ Datos incompletos, no se puede verificar duplicados');
      return;
    }

    console.log('🚀 Enviando petición AJAX...');

    // Mostrar indicador de carga
    if (alertContainer) {
      alertContainer.innerHTML = `
        <div class="alert alert-info">
          <i class="fas fa-spinner fa-spin"></i> Verificando duplicados...
        </div>
      `;
      alertContainer.style.display = 'block';
    }

    // Hacer petición AJAX
    fetch('/actas/verificar-duplicado', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        tipo_sacramento: tipoSacramento,
        persona_id: personaId,
        fecha_sacramento: fechaSacramento
      })
    })
    .then(response => {
      console.log('📡 Respuesta recibida:', response.status);
      return response.json();
    })
    .then(data => {
      console.log('📦 Datos recibidos:', data);
      
      if (data.duplicado) {
        console.log('🚨 DUPLICADO DETECTADO!');
        if (alertContainer) {
          alertContainer.innerHTML = `
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-triangle"></i>
              <strong>¡Acta Duplicada!</strong><br>
              ${data.mensaje}
            </div>
          `;
          alertContainer.style.display = 'block';
        }
        
        // Deshabilitar botón de guardar
        const guardarBtn = document.querySelector('#createActa .btn-success');
        if (guardarBtn) {
          guardarBtn.disabled = true;
          guardarBtn.innerHTML = '<i class="fas fa-ban"></i> No se puede guardar - Duplicado';
        }
      } else {
        console.log('✅ No hay duplicados');
        if (alertContainer) {
          alertContainer.style.display = 'none';
        }
        
        // Habilitar botón de guardar
        const guardarBtn = document.querySelector('#createActa .btn-success');
        if (guardarBtn) {
          guardarBtn.disabled = false;
          guardarBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Acta';
        }
      }
    })
    .catch(error => {
      console.error('❌ Error al verificar duplicados:', error);
      if (alertContainer) {
        alertContainer.innerHTML = `
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-circle"></i>
            Error al verificar duplicados. Contacte al administrador.
          </div>
        `;
        alertContainer.style.display = 'block';
      }
    });
  }

  // Agregar event listeners para verificar duplicados en tiempo real
  document.addEventListener('DOMContentLoaded', function() {
    // Cuando cambie el tipo de sacramento
    document.querySelectorAll('input[name="tipo_sacramento"]').forEach(radio => {
      radio.addEventListener('change', function() {
        setTimeout(verificarDuplicados, 100); // Delay para que se actualice la UI
      });
    });

    // Cuando cambien las personas en cualquier sacramento
    const personaSelects = [
      '#cve_persona_matrimonio',
      '#cve_persona_bautizo', 
      '#cve_persona_confirmacion'
    ];
    
    personaSelects.forEach(selector => {
      const element = document.querySelector(selector);
      if (element) {
        element.addEventListener('change', verificarDuplicados);
      }
    });

    // Cuando cambie la fecha del sacramento
    const fechaInput = document.querySelector('#fecha');
    if (fechaInput) {
      fechaInput.addEventListener('change', verificarDuplicados);
    }

    // También verificar cuando se abre el modal
    $('#createActa').on('shown.bs.modal', function() {
      setTimeout(verificarDuplicados, 500); // Delay para asegurar que todo esté cargado
    });
    
    // Verificar cuando se inicializa Select2 (para capturar cambios dinámicos)
    $(document).on('select2:select', '.person-select', function() {
      setTimeout(verificarDuplicados, 100);
    });
  });
});
</script>
