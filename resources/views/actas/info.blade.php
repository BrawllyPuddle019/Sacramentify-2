<!-- Modal Edit Acta -->
<style>
.is-invalid {
  border-color: #dc3545 !important;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-invalid:focus {
  border-color: #dc3545 !important;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.form-control.is-invalid,
.form-select.is-invalid {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.7.7 1.4-1.4'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right calc(0.375em + 0.1875rem) center;
  background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>

<div class="modal fade" id="editActa{{ $acta->cve_actas }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Acta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('actas.update', $acta->cve_actas) }}" method="post" id="editActaForm{{ $acta->cve_actas }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipo_acta_real" id="tipo_acta_real_{{ $acta->cve_actas }}" value="{{ $acta->tipo_acta }}">
                
                <div class="modal-body">
                    <div class="container-fluid">
                        {{-- ÁREA PARA MOSTRAR ERRORES DE VALIDACIÓN DINÁMICA --}}
                        <div id="validationAlert{{ $acta->cve_actas }}" class="alert alert-warning alert-dismissible fade" role="alert" style="display: none;">
                            <span id="validationMessage{{ $acta->cve_actas }}"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        {{-- TIPO DE ACTA --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="tipo_acta_{{ $acta->cve_actas }}" class="form-label">Tipo de Acta</label>
                                    <select class="form-select" name="tipo_acta" id="tipo_acta_{{ $acta->cve_actas }}" required>
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
                                        <option value="{{ $sacramento->cve_sacramentos }}" data-tipo="{{ $tipoMapeado }}" {{ $acta->tipo_acta == $sacramento->cve_sacramentos ? 'selected' : '' }}>{{ $sacramento->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- CAMPOS ESPECÍFICOS POR TIPO DE ACTA --}}
                        @php
                            // Determinar qué sección mostrar basándose en el tipo de acta actual
                            $tipoActaActual = strtolower($acta->tipoActa->nombre ?? '');
                            $tipoMapeadoActual = match($tipoActaActual) {
                                'matrimonio' => 'matrimonio',
                                'bautismo' => 'bautizo', 
                                'confirmación' => 'confirmacion',
                                default => ''
                            };
                        @endphp
                        <div id="camposMatrimonio{{ $acta->cve_actas }}" style="display:{{ $tipoMapeadoActual === 'matrimonio' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Esposo *</label>
                                        <select class="form-select" name="matrimonio[cve_esposo]" id="cve_persona_matrimonio_{{ $acta->cve_actas }}" data-required-for="matrimonio">
                                            <option value="">Selecciona al Esposo</option>
                                            @foreach ($personas as $persona)
                                            @if ($persona->sexo === '1')
                                                <option value="{{ $persona->cve_persona }}" {{ $acta->cve_persona == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Esposa *</label>
                                        <select class="form-select" name="matrimonio[cve_esposa]" id="cve_persona2_{{ $acta->cve_actas }}" data-required-for="matrimonio">
                                            <option value="">Selecciona a la Esposa</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->cve_persona2 == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- PADRINOS DEL ESPOSO --}}
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Padrino Esposo</label>
                                        <select class="form-select" name="matrimonio[padrino_esposo]">
                                            <option value="">Selecciona Padrino Esposo</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '1')
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padrino1 == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Madrina Esposo</label>
                                        <select class="form-select" name="matrimonio[madrina_esposo]">
                                            <option value="">Selecciona Madrina Esposo</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madrina1 == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- PADRINOS DE LA ESPOSA --}}
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Padrino Esposa</label>
                                        <select class="form-select" name="matrimonio[padrino_esposa]">
                                            <option value="">Selecciona Padrino Esposa</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '1')
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padrino == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Madrina Esposa</label>
                                        <select class="form-select" name="matrimonio[madrina_esposa]">
                                            <option value="">Selecciona Madrina Esposa</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madrina == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
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
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padre == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
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
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madre == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
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
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_padre1 == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
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
                                                    <option value="{{ $persona->cve_persona }}" {{ $acta->Per_cve_madre1 == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="camposBautizo{{ $acta->cve_actas }}" style="display:{{ $tipoMapeadoActual === 'bautizo' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Persona que se bautiza *</label>
                                        <select class="form-select" name="bautizo[cve_persona]" id="cve_persona_bautizo_{{ $acta->cve_actas }}" data-required-for="bautizo">
                                            <option value="">Selecciona una Persona</option>
                                            @foreach ($personas as $persona)
                                                <option value="{{ $persona->cve_persona }}" {{ $acta->cve_persona == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Padrino</label>
                                        <select class="form-select" name="bautizo[padrino]" id="padrino_bautizo_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona Padrino</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '1')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_padrino == $persona->cve_persona || $acta->Per_cve_padrino1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Madrina</label>
                                        <select class="form-select" name="bautizo[madrina]" id="madrina_bautizo_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona Madrina</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_madrina == $persona->cve_persona || $acta->Per_cve_madrina1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
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
                                        <select class="form-select" name="bautizo[padre]" id="padre_bautizo_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona el Padre</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '1')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_padre == $persona->cve_persona || $acta->Per_cve_padre1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Madre</label>
                                        <select class="form-select" name="bautizo[madre]" id="madre_bautizo_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona la Madre</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_madre == $persona->cve_persona || $acta->Per_cve_madre1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="camposConfirmacion{{ $acta->cve_actas }}" style="display:{{ $tipoMapeadoActual === 'confirmacion' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Persona que se confirma *</label>
                                        <select class="form-select" name="confirmacion[cve_persona]" id="cve_persona_confirmacion_{{ $acta->cve_actas }}" data-required-for="confirmacion">
                                            <option value="">Selecciona una Persona</option>
                                            @foreach ($personas as $persona)
                                                <option value="{{ $persona->cve_persona }}" {{ $acta->cve_persona == $persona->cve_persona ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
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
                                        <select class="form-select" name="confirmacion[padre]" id="padre_confirmacion_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona el Padre</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '1')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_padre == $persona->cve_persona || $acta->Per_cve_padre1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Madre del Confirmando</label>
                                        <select class="form-select" name="confirmacion[madre]" id="madre_confirmacion_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona la Madre</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_madre == $persona->cve_persona || $acta->Per_cve_madre1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
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
                                        <select class="form-select" name="confirmacion[padrino]" id="padrino_confirmacion_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona Padrino de Confirmación</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '1')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_padrino == $persona->cve_persona || $acta->Per_cve_padrino1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Madrina de Confirmación</label>
                                        <select class="form-select" name="confirmacion[madrina]" id="madrina_confirmacion_{{ $acta->cve_actas }}">
                                            <option value="">Selecciona Madrina de Confirmación</option>
                                            @foreach ($personas as $persona)
                                                @if ($persona->sexo === '0')
                                                    <option value="{{ $persona->cve_persona }}" {{ ($acta->Per_cve_madrina == $persona->cve_persona || $acta->Per_cve_madrina1 == $persona->cve_persona) ? 'selected' : '' }}>{{ $persona->nombre }} {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CAMPOS GENERALES --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="cve_ermitas_{{ $acta->cve_actas }}" class="form-label">Ermita</label>
                                    <select class="form-select" name="cve_ermitas" id="cve_ermitas_{{ $acta->cve_actas }}">
                                        <option value="">Selecciona la Ermita</option>
                                        @foreach ($ermitas as $ermita)
                                            <option value="{{ $ermita->cve_ermitas }}" {{ $acta->cve_ermitas == $ermita->cve_ermitas ? 'selected' : '' }}>{{ $ermita->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="cve_sacerdotes_celebrante_{{ $acta->cve_actas }}" class="form-label">Sacerdote Celebrante</label>
                                    <select class="form-select" name="cve_sacerdotes_celebrante" id="cve_sacerdotes_celebrante_{{ $acta->cve_actas }}">
                                        <option value="">Selecciona el Sacerdote</option>
                                        @foreach ($sacerdotes as $sacerdote)
                                            <option value="{{ $sacerdote->cve_sacerdotes }}" {{ $acta->cve_sacerdotes_celebrante == $sacerdote->cve_sacerdotes ? 'selected' : '' }}>{{ $sacerdote->nombre_sacerdote }} {{ $sacerdote->apellido_paterno }} {{ $sacerdote->apellido_materno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="cve_sacerdotes_asistente_{{ $acta->cve_actas }}" class="form-label">Sacerdote Asistente</label>
                                    <select class="form-select" name="cve_sacerdotes_asistente" id="cve_sacerdotes_asistente_{{ $acta->cve_actas }}">
                                        <option value="">Selecciona el Sacerdote</option>
                                        @foreach ($sacerdotes as $sacerdote)
                                            <option value="{{ $sacerdote->cve_sacerdotes }}" {{ $acta->cve_sacerdotes_asistente == $sacerdote->cve_sacerdotes ? 'selected' : '' }}>{{ $sacerdote->nombre_sacerdote }} {{ $sacerdote->apellido_paterno }} {{ $sacerdote->apellido_materno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="cve_obispos_celebrante_{{ $acta->cve_actas }}" class="form-label">Obispo Celebrante</label>
                                    <select class="form-select" name="cve_obispos_celebrante" id="cve_obispos_celebrante_{{ $acta->cve_actas }}">
                                        <option value="">Selecciona el Obispo</option>
                                        @foreach ($obispos as $obispo)
                                            <option value="{{ $obispo->cve_obispos }}" {{ $acta->cve_obispos_celebrante == $obispo->cve_obispos ? 'selected' : '' }}>{{ $obispo->nombre_obispo }} {{ $obispo->apellido_paterno }} {{ $obispo->apellido_materno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="fecha_{{ $acta->cve_actas }}" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" name="fecha" id="fecha_{{ $acta->cve_actas }}" value="{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('Y-m-d') : '' }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="Libro_{{ $acta->cve_actas }}" class="form-label">Libro *</label>
                                    <input type="text" class="form-control" name="Libro" id="Libro_{{ $acta->cve_actas }}" value="{{ $acta->Libro }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="Fojas_{{ $acta->cve_actas }}" class="form-label">Fojas *</label>
                                    <input type="number" class="form-control" name="Fojas" id="Fojas_{{ $acta->cve_actas }}" value="{{ $acta->Fojas }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="Folio_{{ $acta->cve_actas }}" class="form-label">Folio *</label>
                                    <input type="number" class="form-control" name="Folio" id="Folio_{{ $acta->cve_actas }}" value="{{ $acta->Folio }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Acta -->
<div class="modal fade" id="deleteActa{{ $acta->cve_actas }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Acta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('actas.destroy', $acta->cve_actas) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    ¿Estás seguro de eliminar el acta de <strong>{{ $acta->persona?->nombre }} {{ $acta->persona?->paterno }} {{ $acta->persona?->materno }}</strong>?
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
// Ejecutar JavaScript cuando se abra este modal específico
document.addEventListener('DOMContentLoaded', function() {
  // Verificar que jQuery esté disponible
  if (typeof $ !== 'undefined') {
    $('#editActa{{ $acta->cve_actas }}').on('shown.bs.modal', function() {
      console.log('🚀 MODAL ABIERTO PARA ACTA:', '{{ $acta->cve_actas }}');
      // Delay para asegurar que el DOM esté completamente listo
      setTimeout(function() {
        inicializarModalEdicion('{{ $acta->cve_actas }}');
      }, 100);
    });
  } else {
    console.warn('jQuery no está disponible para el modal de edición de acta {{ $acta->cve_actas }}');
  }
});

function inicializarModalEdicion(actaId) {
  console.log('🔧 INICIALIZANDO MODAL DE EDICIÓN PARA ACTA:', actaId);
  // ============================================================================
  // INICIALIZACIÓN DE VARIABLES - NO INTERRUMPE ACTUALIZACIÓN
  // ============================================================================
  const tipoActaSelect = document.getElementById('tipo_acta_' + actaId);
  const tipoActaReal = document.getElementById('tipo_acta_real_' + actaId);
  const validationAlert = document.getElementById('validationAlert' + actaId);
  const validationMessage = document.getElementById('validationMessage' + actaId);
  const submitButton = document.querySelector('#editActaForm' + actaId + ' button[type="submit"].btn-primary');
  
  // Debug: verificar que los elementos se encontraron
  console.log('🔍 ELEMENTOS ENCONTRADOS:', {
    actaId,
    validationAlert: !!validationAlert,
    validationMessage: !!validationMessage,
    submitButton: !!submitButton
  });
  
  const secciones = {
    matrimonio: document.getElementById('camposMatrimonio' + actaId),
    bautizo: document.getElementById('camposBautizo' + actaId),
    confirmacion: document.getElementById('camposConfirmacion' + actaId)
  };

  // Debug: verificar que las secciones se encontraron
  console.log('🔍 SECCIONES ENCONTRADAS:', {
    matrimonio: !!secciones.matrimonio,
    bautizo: !!secciones.bautizo,
    confirmacion: !!secciones.confirmacion
  });
  
  // Verificar que todas las secciones existan
  Object.keys(secciones).forEach(key => {
    if (!secciones[key]) {
      console.error('❌ ERROR: No se encontró la sección', key, 'con ID:', key === 'matrimonio' ? 'camposMatrimonio' + actaId : key === 'bautizo' ? 'camposBautizo' + actaId : 'camposConfirmacion' + actaId);
    }
  });

  // ============================================================================
  // FUNCIONES AUXILIARES - NO INTERRUMPEN ACTUALIZACIÓN
  // ============================================================================
  
  // Función para mostrar alertas de validación
  function mostrarAlerta(mensaje, tipo = 'warning') {
    validationMessage.textContent = mensaje;
    validationAlert.className = `alert alert-${tipo} alert-dismissible fade show`;
    validationAlert.style.display = 'block';
    
    // Scroll al top del modal para que se vea la alerta
    document.querySelector('#editActa' + actaId + ' .modal-body').scrollTop = 0;
    
    // Deshabilitar botón de envío si hay error
    if (tipo === 'danger' && submitButton) {
      submitButton.disabled = true;
    }
  }

  // Función para ocultar alertas
  function ocultarAlerta() {
    validationAlert.style.display = 'none';
    if (submitButton) {
      submitButton.disabled = false;
    }
  }

  // ============================================================================
  // FUNCIÓN DE VALIDACIÓN DE DUPLICADOS - PUEDE INTERRUMPIR SI HAY ERROR EN API
  // ============================================================================
  async function validarSacramento(tipoActa, personaId, personaId2 = null, actaIdExcluir = null) {
    console.log('🔍 VALIDANDO SACRAMENTO:', { tipoActa, personaId, personaId2, actaIdExcluir });
    
    if (!tipoActa || !personaId) {
      console.log('✅ VALIDACIÓN OMITIDA - Datos insuficientes');
      return true;
    }

    try {
      console.log('📡 ENVIANDO PETICIÓN AL SERVIDOR...');
      const response = await fetch('{{ route("actas.validar-sacramento") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          tipo_acta: tipoActa,
          persona_id: personaId,
          persona_id2: personaId2,
          acta_id: actaIdExcluir
        })
      });

      if (!response.ok) {
        console.error('❌ ERROR HTTP EN VALIDACIÓN:', response.status);
        console.log('✅ PERMITIENDO CONTINUAR DEBIDO A ERROR HTTP');
        return true; // Permitir continuar si hay error en la respuesta
      }

      const data = await response.json();
      console.log('📋 RESPUESTA DEL SERVIDOR:', data);
      
      if (!data.valido) {
        console.log('🚫 VALIDACIÓN FALLÓ - DUPLICADO ENCONTRADO');
        mostrarAlerta(data.mensaje, 'danger');
        return false;
      } else {
        console.log('✅ VALIDACIÓN EXITOSA - NO HAY DUPLICADOS');
        ocultarAlerta();
        return true;
      }
    } catch (error) {
      console.error('❌ ERROR EN VALIDACIÓN DE SACRAMENTO:', error);
      console.log('✅ PERMITIENDO CONTINUAR DEBIDO A ERROR EN VALIDACIÓN');
      return true; // Permitir continuar si hay error en la validación
    }
  }

  // ============================================================================
  // MOSTRAR SECCIÓN INICIAL - NO INTERRUMPE ACTUALIZACIÓN
  // ============================================================================
  function mostrarSeccionInicial() {
    console.log('🎯 MOSTRANDO SECCIÓN INICIAL');
    console.log('📊 ELEMENTOS DISPONIBLES:', {
      tipoActaSelect: !!tipoActaSelect,
      secciones: Object.keys(secciones).map(key => ({
        [key]: !!secciones[key]
      }))
    });
    
    // Ocultar todas las secciones
    Object.values(secciones).forEach(sec => {
      if (sec) {
        sec.style.display = 'none';
        console.log('🚫 OCULTANDO SECCIÓN:', sec.id);
      }
    });
    
    // Buscar el tipo de acta seleccionada
    let tipoTexto = null;
    if (tipoActaSelect && tipoActaSelect.options && tipoActaSelect.selectedIndex >= 0) {
      const selectedOption = tipoActaSelect.options[tipoActaSelect.selectedIndex];
      tipoTexto = selectedOption.dataset.tipo;
      console.log('📝 TIPO DE ACTA DETECTADO:', tipoTexto);
      console.log('🔍 SELECTED OPTION:', {
        value: selectedOption.value,
        text: selectedOption.text,
        datasetTipo: selectedOption.dataset.tipo
      });

      if (tipoTexto && secciones[tipoTexto]) {
        secciones[tipoTexto].style.display = 'block';
        console.log('✅ SECCIÓN MOSTRADA:', tipoTexto, secciones[tipoTexto].id);
      } else {
        console.log('❌ NO SE ENCONTRÓ SECCIÓN PARA TIPO:', tipoTexto);
        console.log('🔧 SECCIONES DISPONIBLES:', Object.keys(secciones));
      }
    } else {
      console.log('❌ ERROR: tipoActaSelect no disponible o sin opciones');
    }
    
    // ⚠️ ARREGLO CRÍTICO: Manejar atributos required dinámicamente
    manejarCamposRequeridos(tipoTexto);
  }

  // ============================================================================
  // FUNCIÓN PARA MANEJAR CAMPOS REQUIRED - ARREGLA EL PROBLEMA DE FOCUSABLE
  // ============================================================================
  function manejarCamposRequeridos(tipoActivo) {
    console.log('🔧 MANEJANDO CAMPOS REQUIRED PARA TIPO:', tipoActivo);
    
    // Remover required de todos los campos primero
    Object.keys(secciones).forEach(tipo => {
      const seccion = secciones[tipo];
      if (seccion) {
        const camposRequeridos = seccion.querySelectorAll('[required]');
        camposRequeridos.forEach(campo => {
          campo.removeAttribute('required');
          console.log('🚫 REQUIRED REMOVIDO DE:', campo.name);
        });
      }
    });
    
    // Agregar required solo a los campos del tipo activo
    if (tipoActivo && secciones[tipoActivo]) {
      const seccionActiva = secciones[tipoActivo];
      
      if (tipoActivo === 'matrimonio') {
        // Solo esposo y esposa son requeridos en matrimonio
        const esposoField = seccionActiva.querySelector('select[name="matrimonio[cve_esposo]"]');
        const esposaField = seccionActiva.querySelector('select[name="matrimonio[cve_esposa]"]');
        
        if (esposoField) {
          esposoField.setAttribute('required', '');
          console.log('✅ REQUIRED AGREGADO A: matrimonio[cve_esposo]');
        }
        if (esposaField) {
          esposaField.setAttribute('required', '');
          console.log('✅ REQUIRED AGREGADO A: matrimonio[cve_esposa]');
        }
      } else if (tipoActivo === 'bautizo') {
        // Solo la persona a bautizar es requerida
        const personaField = seccionActiva.querySelector('select[name="bautizo[cve_persona]"]');
        if (personaField) {
          personaField.setAttribute('required', '');
          console.log('✅ REQUIRED AGREGADO A: bautizo[cve_persona]');
        }
      } else if (tipoActivo === 'confirmacion') {
        // Solo la persona a confirmar es requerida
        const personaField = seccionActiva.querySelector('select[name="confirmacion[cve_persona]"]');
        if (personaField) {
          personaField.setAttribute('required', '');
          console.log('✅ REQUIRED AGREGADO A: confirmacion[cve_persona]');
        }
      }
    }
  }

  // Mostrar sección inicial
  mostrarSeccionInicial();

  // ============================================================================
  // EVENT LISTENER: CAMBIO DE TIPO DE ACTA - OMITIDO SEGÚN TU SOLICITUD
  // ============================================================================
  tipoActaSelect.addEventListener('change', function() {
    console.log('🔄 TIPO DE ACTA CAMBIÓ A:', this.value);
    // Ocultar todas las secciones
    Object.values(secciones).forEach(sec => sec.style.display = 'none');
    // Guardar valor real para validación
    tipoActaReal.value = this.value;
    ocultarAlerta(); // Limpiar alertas al cambiar tipo

    // Buscar el tipo de acta seleccionada (matrimonio, bautizo, confirmacion)
    const selectedOption = this.options[this.selectedIndex];
    const tipoTexto = selectedOption.dataset.tipo;

    if (tipoTexto && secciones[tipoTexto]) {
      secciones[tipoTexto].style.display = 'block';
    }
    
    // ⚠️ ARREGLO CRÍTICO: Manejar atributos required al cambiar tipo
    manejarCamposRequeridos(tipoTexto);
  });

  // ============================================================================
  // EVENT LISTENERS: VALIDACIÓN EN TIEMPO REAL - PUEDEN INTERRUMPIR SI HAY DUPLICADOS
  // ============================================================================
  
  // MATRIMONIO - Validar cuando se selecciona esposo o esposa
  console.log('⚙️ CONFIGURANDO LISTENERS PARA MATRIMONIO...');
  document.getElementById('cve_persona_matrimonio_' + actaId)?.addEventListener('change', function() {
    console.log('👤 ESPOSO SELECCIONADO:', this.value);
    const esposaSelect = document.getElementById('cve_persona2_' + actaId);
    if (this.value && esposaSelect.value) {
      console.log('💒 VALIDANDO MATRIMONIO (AMBOS SELECCIONADOS)');
      validarSacramento(tipoActaReal.value, this.value, esposaSelect.value, actaId);
    } else if (this.value) {
      console.log('💒 VALIDANDO MATRIMONIO (SOLO ESPOSO)');
      validarSacramento(tipoActaReal.value, this.value, null, actaId);
    }
  });

  document.getElementById('cve_persona2_' + actaId)?.addEventListener('change', function() {
    console.log('👤 ESPOSA SELECCIONADA:', this.value);
    const esposoSelect = document.getElementById('cve_persona_matrimonio_' + actaId);
    if (this.value && esposoSelect.value) {
      console.log('💒 VALIDANDO MATRIMONIO (AMBOS SELECCIONADOS)');
      validarSacramento(tipoActaReal.value, esposoSelect.value, this.value, actaId);
    } else if (this.value) {
      console.log('💒 VALIDANDO MATRIMONIO (SOLO ESPOSA)');
      validarSacramento(tipoActaReal.value, this.value, null, actaId);
    }
  });

  // BAUTIZO - Validar cuando se selecciona la persona
  console.log('⚙️ CONFIGURANDO LISTENER PARA BAUTIZO...');
  document.getElementById('cve_persona_bautizo_' + actaId)?.addEventListener('change', function() {
    console.log('👶 PERSONA PARA BAUTIZO SELECCIONADA:', this.value);
    if (this.value) {
      console.log('✝️ VALIDANDO BAUTIZO');
      validarSacramento(tipoActaReal.value, this.value, null, actaId);
    }
  });

  // CONFIRMACIÓN - Validar cuando se selecciona la persona
  console.log('⚙️ CONFIGURANDO LISTENER PARA CONFIRMACIÓN...');
  document.getElementById('cve_persona_confirmacion_' + actaId)?.addEventListener('change', function() {
    console.log('🙏 PERSONA PARA CONFIRMACIÓN SELECCIONADA:', this.value);
    if (this.value) {
      console.log('⛪ VALIDANDO CONFIRMACIÓN');
      validarSacramento(tipoActaReal.value, this.value, null, actaId);
    }
  });

  // ============================================================================
  // EVENT LISTENER PRINCIPAL: ENVÍO DEL FORMULARIO - PRINCIPAL SOSPECHOSO DE INTERRUPCIONES
  // ============================================================================
  document.getElementById('editActaForm' + actaId).addEventListener('submit', async function(e) {
    console.log('🚀 ==================== ENVÍO DE FORMULARIO INICIADO ====================');
    console.log('📝 Submit event triggered for acta:', actaId);
    
    let isValid = true;
    const form = this;

    // ============================================================================
    // PASO 1: RESETEO DE CLASES DE ERROR - NO INTERRUMPE
    // ============================================================================
    console.log('🧹 LIMPIANDO CLASES DE ERROR PREVIAS...');
    form.querySelectorAll('.is-invalid').forEach(el => {
      el.classList.remove('is-invalid');
    });

    // ============================================================================
    // PASO 2: OCULTAR ALERTAS PREVIAS - NO INTERRUMPE
    // ============================================================================
    console.log('🚫 OCULTANDO ALERTAS PREVIAS...');
    ocultarAlerta();

    // ============================================================================
    // PASO 3: VALIDACIÓN DE CAMPOS GENERALES - PUEDE INTERRUMPIR SI FALTAN CAMPOS
    // ============================================================================
    console.log('📋 VALIDANDO CAMPOS GENERALES...');
    const camposGenerales = ['fecha_' + actaId, 'Libro_' + actaId, 'Fojas_' + actaId, 'Folio_' + actaId, 'tipo_acta_' + actaId];
    camposGenerales.forEach(id => {
      const field = document.getElementById(id);
      if (field && field.hasAttribute('required') && !field.value.trim()) {
        field.classList.add('is-invalid');
        isValid = false;
        console.log('❌ Campo requerido vacío:', id);
      }
    });

    // ============================================================================
    // PASO 4: VALIDACIÓN DE CAMPOS ESPECÍFICOS - PUEDE INTERRUMPIR SI FALTAN CAMPOS
    // ============================================================================
    const tipoActa = tipoActaReal.value;
    console.log('🎯 Tipo de acta actual:', tipoActa);
    
    if (tipoActa) {
      // Buscar el tipo de acta seleccionada (matrimonio, bautizo, confirmacion)
      const selectedOption = tipoActaSelect.options[tipoActaSelect.selectedIndex];
      const tipoTexto = selectedOption.dataset.tipo;
      console.log('📝 Tipo de texto:', tipoTexto);
      
      if (tipoTexto && secciones[tipoTexto]) {
        const camposRequeridos = secciones[tipoTexto].querySelectorAll('[required]');
        console.log('📊 Campos requeridos encontrados:', camposRequeridos.length);
        
        camposRequeridos.forEach(field => {
          if (!field.value) {
            field.classList.add('is-invalid');
            isValid = false;
            console.log('❌ Campo específico vacío:', field.name);
          }
        });
      }
    }

    // ============================================================================
    // PASO 5: VERIFICACIÓN DE VALIDACIÓN - INTERRUMPE SI HAY ERRORES
    // ============================================================================
    if (!isValid) {
      console.log('🚫 ==================== ENVÍO INTERRUMPIDO POR VALIDACIÓN ====================');
      console.log('❌ Validación fallida, previniendo envío');
      e.preventDefault();
      mostrarAlerta('Por favor, completa todos los campos requeridos marcados con * y verifica que no haya errores.', 'danger');
      return;
    }

    // ============================================================================
    // PASO 6: VALIDACIÓN DE DUPLICADOS - PUEDE INTERRUMPIR SI CAMBIÓ TIPO DE ACTA
    // ============================================================================
    console.log('🔍 INICIANDO VALIDACIÓN DE DUPLICADOS...');
    const tipoActaOriginal = '{{ $acta->tipo_acta }}';
    const tipoTexto = tipoActaSelect.options[tipoActaSelect.selectedIndex].dataset.tipo;
    let necesitaValidacionDuplicados = false;
    let personaId = null;
    let personaId2 = null;

    console.log('📊 Tipo original:', tipoActaOriginal, 'Tipo actual:', tipoActa);

    // Solo validar duplicados si cambió el tipo de acta
    if (tipoActa !== tipoActaOriginal) {
      console.log('⚠️ TIPO DE ACTA CAMBIÓ - NECESITA VALIDACIÓN DE DUPLICADOS');
      necesitaValidacionDuplicados = true;
      
      if (tipoTexto === 'matrimonio') {
        personaId = document.getElementById('cve_persona_matrimonio_' + actaId).value;
        personaId2 = document.getElementById('cve_persona2_' + actaId).value;
      } else if (tipoTexto === 'bautizo') {
        personaId = document.getElementById('cve_persona_bautizo_' + actaId).value;
      } else if (tipoTexto === 'confirmacion') {
        personaId = document.getElementById('cve_persona_confirmacion_' + actaId).value;
      }
    } else {
      console.log('✅ TIPO DE ACTA NO CAMBIÓ - ENVÍO DIRECTO SIN VALIDACIÓN');
    }

    // ============================================================================
    // PASO 7: EJECUCIÓN DE VALIDACIÓN DE DUPLICADOS - PRINCIPAL SOSPECHOSO
    // ============================================================================
    if (necesitaValidacionDuplicados && personaId) {
      console.log('🔄 ==================== EJECUTANDO VALIDACIÓN DE DUPLICADOS ====================');
      console.log('⏸️ PREVINIENDO ENVÍO PARA VALIDAR...');
      e.preventDefault(); // ⚠️ ESTE PREVENT DEFAULT PUEDE SER EL PROBLEMA
      
      try {
        const validacionFinal = await validarSacramento(tipoActa, personaId, personaId2, actaId);
        if (!validacionFinal) {
          console.log('🚫 ==================== ENVÍO INTERRUMPIDO POR DUPLICADO ====================');
          console.log('❌ Validación de duplicados falló');
          return; // ⚠️ ESTE RETURN INTERRUMPE EL ENVÍO
        } else {
          console.log('✅ VALIDACIÓN EXITOSA - REMOVIENDO LISTENER Y REENVIANDO...');
          // ⚠️ ESTE BLOQUE INTENTA REENVIAR EL FORMULARIO
          form.removeEventListener('submit', arguments.callee);
          form.submit();
        }
      } catch (error) {
        console.error('❌ ERROR EN VALIDACIÓN:', error);
        mostrarAlerta('Error al validar. Intenta nuevamente.', 'danger');
        // ⚠️ NO HAY RETURN AQUÍ - PUEDE CONTINUAR
      }
    } else {
      console.log('✅ ==================== ENVÍO NORMAL SIN VALIDACIÓN ====================');
      console.log('🚀 No necesita validación de duplicados, enviando formulario normalmente');
    }
    
    console.log('🏁 ==================== FIN DE EVENT LISTENER ====================');
    // ⚠️ SI LLEGA AQUÍ SIN preventDefault(), EL FORMULARIO SE ENVÍA NORMALMENTE
  });
  
  console.log('🎉 ==================== INICIALIZACIÓN COMPLETADA ====================');
} // Fin de la función inicializarModalEdicion

// Inicialización de Select2 para modales de edición
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que jQuery esté disponible
    if (typeof $ !== 'undefined') {
        console.log('🎬 Inicializando Select2 para modales de edición...');
        
        // Función para inicializar Select2 en un modal específico
        function initializeSelect2InModal(modalElement) {
            console.log('🔧 Configurando Select2 en modal:', modalElement.id);
            
            // SELECTOR CORREGIDO: Buscar todos los selects que contengan opciones de personas
            $(modalElement).find('select').each(function() {
                const selectElement = $(this);
                const selectId = selectElement.attr('id') || selectElement.attr('name');
            const selectName = selectElement.attr('name') || '';
            
            // Verificar si es un select de personas (excluyendo tipo_acta, ermitas, sacerdotes, obispos)
            const isPersonSelect = selectName.includes('persona') || 
                                   selectName.includes('padrino') || 
                                   selectName.includes('madrina') || 
                                   selectName.includes('padre') || 
                                   selectName.includes('madre') ||
                                   selectName.includes('esposo') ||
                                   selectName.includes('esposa') ||
                                   selectName.includes('testigo');
            
            // Excluir selects que NO son de personas
            const isNotPersonSelect = selectName.includes('tipo_acta') ||
                                      selectName.includes('ermitas') ||
                                      selectName.includes('sacerdotes') ||
                                      selectName.includes('obispos');
            
            if (isPersonSelect && !isNotPersonSelect) {
                console.log('👤 Configurando select de persona:', selectId, '- Name:', selectName);
                
                // Destruir Select2 existente si ya está inicializado
                if (selectElement.hasClass('select2-hidden-accessible')) {
                    selectElement.select2('destroy');
                    console.log('🗑️ Select2 anterior destruido para:', selectId);
                }
                
                // Determinar si es un select de género específico para iconos
                let genderIcon = '';
                
                if (selectName.includes('padrino') || selectName.includes('padre') || selectName.includes('esposo')) {
                    genderIcon = '👨 ';
                } else if (selectName.includes('madrina') || selectName.includes('madre') || selectName.includes('esposa')) {
                    genderIcon = '👩 ';
                }
                
                // Configurar Select2 con configuración simplificada
                selectElement.select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(modalElement),
                    placeholder: 'Escribe algunas letras del nombre...',
                    allowClear: true,
                    width: '100%',
                    matcher: function(params, data) {
                        // Si no hay término de búsqueda, mostrar todos
                        if ($.trim(params.term) === '') {
                            return data;
                        }
                        
                        // Si no hay texto para buscar, null
                        if (typeof data.text === 'undefined') {
                            return null;
                        }
                        
                        // Búsqueda case-insensitive en cualquier parte del texto
                        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                            return data;
                        }
                        
                        return null;
                    },
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados. Escribe para buscar...";
                        },
                        searching: function() {
                            return "Buscando...";
                        },
                        inputTooShort: function() {
                            return "Escribe al menos 2 caracteres para buscar";
                        }
                    },
                    minimumInputLength: 0, // Permitir búsqueda desde el primer carácter
                    templateSelection: function(option) {
                        if (!option.id) {
                            return option.text;
                        }
                        return genderIcon + option.text;
                    }
                });
                
                // Evento para debugging
                selectElement.on('select2:open', function() {
                    console.log('🔍 Select2 abierto para búsqueda:', selectId);
                    // Enfocar en el campo de búsqueda
                    setTimeout(() => {
                        $('.select2-search__field').focus();
                    }, 100);
                });
                
                selectElement.on('select2:select', function(e) {
                    console.log('✅ Opción seleccionada:', e.params.data.text);
                });
                
                console.log('✅ Select2 configurado para:', selectId);
            } else {
                console.log('🚫 Select ignorado (no es de personas):', selectId, '- Name:', selectName);
            }
        });
        
        console.log('📊 Proceso de configuración de Select2 completado para modal:', modalElement.id);
    }
    
    // Inicializar Select2 cuando se abren los modales de edición
    $('[id^="editActa"]').on('shown.bs.modal', function() {
        console.log('📂 Modal de edición abierto:', this.id);
        setTimeout(() => {
            initializeSelect2InModal(this);
        }, 200); // Delay más largo para asegurar que el modal esté completamente cargado
    });
    
    // También reinicializar cuando cambia el tipo de acta en el modal de edición
    $(document).on('change', 'select[id^="tipo_acta_"]', function() {
        const modal = $(this).closest('.modal')[0];
        if (modal && modal.id.startsWith('editActa')) {
            console.log('🔄 Tipo de acta cambió en modal de edición, reinicializando Select2...');
            setTimeout(() => {
                initializeSelect2InModal(modal);
            }, 300); // Delay más largo para cambios de tipo
        }
    });
    
    // Manejar el cierre del modal para limpiar Select2
    $('[id^="editActa"]').on('hidden.bs.modal', function() {
        console.log('🚪 Modal de edición cerrado:', this.id);
        $(this).find('.select2-hidden-accessible').each(function() {
            $(this).select2('destroy');
        });
    });
    
    console.log('🎉 Configuración de Select2 para modales de edición completada');
    } else {
        console.warn('jQuery no está disponible para la inicialización de Select2');
    }
});
</script>

