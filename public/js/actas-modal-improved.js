// JavaScript para el Modal de Crear Acta Mejorado
document.addEventListener('DOMContentLoaded', function() {
    const createActaModal = document.getElementById('createActaImproved');
    const form = document.getElementById('createActaImprovedForm');
    let currentStep = 1;
    let totalSteps = 4;
    let selectedSacramento = null;

    // Elementos del DOM
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const submitBtn = document.getElementById('submitForm');
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');

    // Inicializar cuando se abre el modal
    createActaModal.addEventListener('shown.bs.modal', function() {
        resetForm();
    });

    // Reset form cuando se cierra el modal
    createActaModal.addEventListener('hidden.bs.modal', function() {
        resetForm();
    });

    // Event listeners para botones de navegación
    nextBtn.addEventListener('click', nextStep);
    prevBtn.addEventListener('click', prevStep);

    // Event listeners para selección de sacramento
    document.querySelectorAll('.sacramento-card').forEach(card => {
        card.addEventListener('click', function() {
            selectSacramento(this);
        });
    });

    // Función para seleccionar sacramento
    function selectSacramento(card) {
        // Remover selección anterior
        document.querySelectorAll('.sacramento-option').forEach(opt => {
            opt.classList.remove('selected');
        });

        // Seleccionar nuevo sacramento
        card.querySelector('.sacramento-option').classList.add('selected');
        selectedSacramento = {
            id: card.dataset.sacramento,
            tipo: card.dataset.tipo,
            nombre: card.querySelector('.card-title').textContent
        };

        // Habilitar botón siguiente
        nextBtn.disabled = false;

        // Actualizar campo hidden
        document.getElementById('tipo_acta_real_improved').value = selectedSacramento.id;
    }

    // Función para ir al siguiente paso
    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();
                
                // Cargar contenido dinámico según el paso
                if (currentStep === 2) {
                    loadPersonasContent();
                } else if (currentStep === 4) {
                    loadConfirmationContent();
                }
            }
        }
    }

    // Función para ir al paso anterior
    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    // Validar paso actual
    function validateCurrentStep() {
        clearValidationErrors();

        switch (currentStep) {
            case 1:
                if (!selectedSacramento) {
                    showValidationError('Por favor selecciona un tipo de sacramento.');
                    return false;
                }
                break;
            case 2:
                return validatePersonasStep();
            case 3:
                return validateDetallesStep();
        }
        return true;
    }

    // Validar paso de personas
    function validatePersonasStep() {
        const requiredFields = document.querySelectorAll('#personas-content [required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            showValidationError('Por favor completa todos los campos requeridos de personas.');
        }

        return isValid;
    }

    // Validar paso de detalles
    function validateDetallesStep() {
        const fecha = document.getElementById('fecha_improved');
        const ermita = document.getElementById('ermita_improved');
        const sacerdote = document.getElementById('sacerdote_improved');
        let isValid = true;

        [fecha, ermita, sacerdote].forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            showValidationError('Por favor completa todos los campos requeridos.');
        }

        return isValid;
    }

    // Actualizar visualización de pasos
    function updateStepDisplay() {
        // Actualizar indicadores de progreso
        steps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('step-active', 'step-completed');
            
            if (stepNumber === currentStep) {
                step.classList.add('step-active');
            } else if (stepNumber < currentStep) {
                step.classList.add('step-completed');
            }
        });

        // Actualizar contenido visible
        stepContents.forEach((content, index) => {
            content.style.display = (index + 1 === currentStep) ? 'block' : 'none';
        });

        // Actualizar botones
        prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
        
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            submitBtn.style.display = 'none';
        }

        // Actualizar texto del botón siguiente
        if (currentStep === totalSteps - 1) {
            nextBtn.innerHTML = 'Revisar<i class="fas fa-arrow-right ms-2"></i>';
        } else {
            nextBtn.innerHTML = 'Siguiente<i class="fas fa-arrow-right ms-2"></i>';
        }
    }

    // Cargar contenido de personas según el sacramento
    function loadPersonasContent() {
        const personasContent = document.getElementById('personas-content');
        
        if (!selectedSacramento) return;

        let html = '';

        switch (selectedSacramento.tipo) {
            case 'bautizo':
                html = `
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-baby text-primary me-2"></i>Persona a Bautizar *
                                </label>
                                <select class="form-select" name="cve_persona" required>
                                    <option value="">Selecciona la persona</option>
                                    @foreach ($personas as $persona)
                                    <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-male text-primary me-2"></i>Padrino
                                </label>
                                <select class="form-select" name="bautizo[padrino]">
                                    <option value="">Selecciona padrino</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 1)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-female text-primary me-2"></i>Madrina
                                </label>
                                <select class="form-select" name="bautizo[madrina]">
                                    <option value="">Selecciona madrina</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 0)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                break;

            case 'matrimonio':
                html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-male text-primary me-2"></i>Esposo *
                                </label>
                                <select class="form-select" name="matrimonio[cve_esposo]" required>
                                    <option value="">Selecciona al esposo</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 1)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-female text-primary me-2"></i>Esposa *
                                </label>
                                <select class="form-select" name="matrimonio[cve_esposa]" required>
                                    <option value="">Selecciona a la esposa</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 0)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user-friends text-primary me-2"></i>Padrino del Matrimonio
                                </label>
                                <select class="form-select" name="matrimonio[padrino_esposo]">
                                    <option value="">Selecciona padrino</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 1)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user-friends text-primary me-2"></i>Madrina del Matrimonio
                                </label>
                                <select class="form-select" name="matrimonio[madrina_esposo]">
                                    <option value="">Selecciona madrina</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 0)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                break;

            case 'confirmación':
                html = `
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user text-primary me-2"></i>Persona a Confirmar *
                                </label>
                                <select class="form-select" name="cve_persona" required>
                                    <option value="">Selecciona la persona</option>
                                    @foreach ($personas as $persona)
                                    <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-male text-primary me-2"></i>Padrino
                                </label>
                                <select class="form-select" name="confirmacion[padrino]">
                                    <option value="">Selecciona padrino</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 1)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-female text-primary me-2"></i>Madrina
                                </label>
                                <select class="form-select" name="confirmacion[madrina]">
                                    <option value="">Selecciona madrina</option>
                                    @foreach ($personas as $persona)
                                        @if ($persona->sexo === 0)
                                        <option value="{{ $persona->cve_persona }}">{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                break;
        }

        personasContent.innerHTML = html;
    }

    // Cargar contenido de confirmación
    function loadConfirmationContent() {
        const summaryContainer = document.querySelector('.confirmation-summary');
        
        // Recopilar toda la información del formulario
        const sacramentoInfo = selectedSacramento;
        const fecha = document.getElementById('fecha_improved').value;
        const ermita = document.getElementById('ermita_improved').selectedOptions[0]?.text || 'No seleccionado';
        const libro = document.getElementById('libro_improved').value || 'No especificado';
        const folio = document.getElementById('folio_improved').value || 'No especificado';
        const sacerdote = document.getElementById('sacerdote_improved').selectedOptions[0]?.text || 'No seleccionado';
        const observaciones = document.getElementById('observaciones_improved').value || 'Ninguna';

        let html = `
            <div class="summary-section mb-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-church me-2"></i>Información del Sacramento
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Tipo de Sacramento:</strong><br>
                        <span class="text-muted">${sacramentoInfo.nombre}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha:</strong><br>
                        <span class="text-muted">${fecha ? new Date(fecha).toLocaleDateString('es-ES') : 'No especificada'}</span>
                    </div>
                </div>
            </div>

            <div class="summary-section mb-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Registro
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Ermita:</strong><br>
                        <span class="text-muted">${ermita}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Sacerdote:</strong><br>
                        <span class="text-muted">${sacerdote}</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Libro:</strong><br>
                        <span class="text-muted">${libro}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Folio:</strong><br>
                        <span class="text-muted">${folio}</span>
                    </div>
                </div>
            </div>

            <div class="summary-section mb-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-users me-2"></i>Personas Involucradas
                </h6>
                <div id="personas-summary">
                    <!-- Se llena dinámicamente -->
                </div>
            </div>

            <div class="summary-section">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-sticky-note me-2"></i>Observaciones
                </h6>
                <p class="text-muted">${observaciones}</p>
            </div>
        `;

        summaryContainer.innerHTML = html;

        // Agregar resumen de personas según el sacramento
        loadPersonasSummary();
    }

    // Cargar resumen de personas
    function loadPersonasSummary() {
        const personasSummary = document.getElementById('personas-summary');
        let html = '';

        // Obtener información de personas según el sacramento
        const personasContent = document.getElementById('personas-content');
        const selects = personasContent.querySelectorAll('select');

        selects.forEach(select => {
            if (select.value && select.selectedOptions[0]) {
                const label = select.previousElementSibling.textContent.replace('*', '').trim();
                const persona = select.selectedOptions[0].text;
                html += `
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>${label}:</strong></div>
                        <div class="col-md-8"><span class="text-muted">${persona}</span></div>
                    </div>
                `;
            }
        });

        if (html === '') {
            html = '<p class="text-muted">No se han seleccionado personas.</p>';
        }

        personasSummary.innerHTML = html;
    }

    // Mostrar errores de validación
    function showValidationError(message) {
        const alertsContainer = document.getElementById('validation-alerts');
        alertsContainer.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    // Limpiar errores de validación
    function clearValidationErrors() {
        document.getElementById('validation-alerts').innerHTML = '';
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }

    // Reset formulario
    function resetForm() {
        currentStep = 1;
        selectedSacramento = null;
        form.reset();
        clearValidationErrors();
        
        // Remover selecciones de sacramento
        document.querySelectorAll('.sacramento-option').forEach(opt => {
            opt.classList.remove('selected');
        });

        // Deshabilitar botón siguiente inicialmente
        nextBtn.disabled = true;

        updateStepDisplay();
    }

    // Prevenir envío del formulario si no está en el último paso
    form.addEventListener('submit', function(e) {
        if (currentStep !== totalSteps) {
            e.preventDefault();
            return false;
        }
    });
});
