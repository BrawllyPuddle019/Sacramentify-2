// Modal Mejorado - JavaScript Compacto y Funcional
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    let selectedSacramento = null;
    
    // Elementos del DOM
    const modal = document.getElementById('createActaImproved');
    const form = document.getElementById('actaFormImproved');
    const prevBtn = document.getElementById('prevBtnImproved');
    const nextBtn = document.getElementById('nextBtnImproved');
    const submitBtn = document.getElementById('submitBtnImproved');
    
    // Inicializar modal
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            resetModal();
        });
    }

    // Event listeners para navegación
    if (nextBtn) {
        nextBtn.addEventListener('click', nextStep);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevStep);
    }

    // Event listeners para selección de sacramento
    document.addEventListener('click', function(e) {
        if (e.target.closest('.sacramento-card')) {
            selectSacramento(e.target.closest('.sacramento-card'));
        }
    });

    function resetModal() {
        currentStep = 1;
        selectedSacramento = null;
        updateStepDisplay();
        clearSelections();
        form.reset();
    }

    function selectSacramento(card) {
        // Remover selección anterior
        document.querySelectorAll('.sacramento-card').forEach(c => c.classList.remove('selected'));
        
        // Seleccionar nueva opción
        card.classList.add('selected');
        
        selectedSacramento = {
            id: card.dataset.sacramento,
            tipo: card.dataset.tipo,
            nombre: card.querySelector('.card-title').textContent
        };
        
        // Actualizar campo hidden
        document.getElementById('tipo_acta_real_improved').value = selectedSacramento.id;
        
        // Habilitar botón siguiente
        nextBtn.disabled = false;
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepDisplay();
                
                // Cargar contenido específico del paso
                if (currentStep === 2) {
                    loadPersonasContent();
                } else if (currentStep === 4) {
                    loadConfirmationContent();
                }
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    }

    function updateStepDisplay() {
        // Actualizar indicadores de paso
        document.querySelectorAll('.step-item').forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber === currentStep) {
                step.classList.add('active');
            } else if (stepNumber < currentStep) {
                step.classList.add('completed');
            }
        });

        // Mostrar/ocultar contenido de pasos
        document.querySelectorAll('.step-content').forEach((content, index) => {
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
            nextBtn.innerHTML = 'Revisar <i class="fas fa-arrow-right ms-1"></i>';
        } else {
            nextBtn.innerHTML = 'Siguiente <i class="fas fa-arrow-right ms-1"></i>';
        }
    }

    function validateCurrentStep() {
        switch (currentStep) {
            case 1:
                if (!selectedSacramento) {
                    alert('Por favor selecciona un tipo de sacramento.');
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

    function loadPersonasContent() {
        const container = document.getElementById('personas-content');
        if (!selectedSacramento || !window.personasData) return;

        let html = '';

        switch (selectedSacramento.tipo) {
            case 'bautizo':
                html = generateBautizoPersonas();
                break;
            case 'matrimonio':
                html = generateMatrimonioPersonas();
                break;
            case 'confirmación':
                html = generateConfirmacionPersonas();
                break;
            default:
                html = generateDefaultPersonas();
                break;
        }

        container.innerHTML = html;
    }

    function generateBautizoPersonas() {
        const personas = window.personasData || [];
        const hombres = personas.filter(p => p.sexo == 1);
        const mujeres = personas.filter(p => p.sexo == 0);

        return `
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-baby text-primary me-1"></i>Persona a Bautizar *
                    </label>
                    <select class="form-select" name="cve_persona" required>
                        <option value="">Selecciona la persona</option>
                        ${personas.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-male text-primary me-1"></i>Padrino
                    </label>
                    <select class="form-select" name="bautizo[padrino]">
                        <option value="">Selecciona padrino</option>
                        ${hombres.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-female text-primary me-1"></i>Madrina
                    </label>
                    <select class="form-select" name="bautizo[madrina]">
                        <option value="">Selecciona madrina</option>
                        ${mujeres.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
            </div>
        `;
    }

    function generateMatrimonioPersonas() {
        const personas = window.personasData || [];
        const hombres = personas.filter(p => p.sexo == 1);
        const mujeres = personas.filter(p => p.sexo == 0);

        return `
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-male text-primary me-1"></i>Novio *
                    </label>
                    <select class="form-select" name="cve_persona" required>
                        <option value="">Selecciona al novio</option>
                        ${hombres.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-female text-primary me-1"></i>Novia *
                    </label>
                    <select class="form-select" name="cve_persona2" required>
                        <option value="">Selecciona a la novia</option>
                        ${mujeres.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user-friends text-primary me-1"></i>Testigo 1
                    </label>
                    <select class="form-select" name="matrimonio[testigo1]">
                        <option value="">Selecciona testigo</option>
                        ${personas.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user-friends text-primary me-1"></i>Testigo 2
                    </label>
                    <select class="form-select" name="matrimonio[testigo2]">
                        <option value="">Selecciona testigo</option>
                        ${personas.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
            </div>
        `;
    }

    function generateConfirmacionPersonas() {
        const personas = window.personasData || [];

        return `
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-hand-paper text-primary me-1"></i>Persona a Confirmar *
                    </label>
                    <select class="form-select" name="cve_persona" required>
                        <option value="">Selecciona la persona</option>
                        ${personas.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user-tie text-primary me-1"></i>Padrino de Confirmación
                    </label>
                    <select class="form-select" name="confirmacion[padrino]">
                        <option value="">Selecciona padrino</option>
                        ${personas.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user text-primary me-1"></i>Madrina de Confirmación
                    </label>
                    <select class="form-select" name="confirmacion[madrina]">
                        <option value="">Selecciona madrina</option>
                        ${personas.map(p => `
                            <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                        `).join('')}
                    </select>
                </div>
            </div>
        `;
    }

    function generateDefaultPersonas() {
        const personas = window.personasData || [];

        return `
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-user text-primary me-1"></i>Persona Principal *
                </label>
                <select class="form-select" name="cve_persona" required>
                    <option value="">Selecciona la persona</option>
                    ${personas.map(p => `
                        <option value="${p.cve_persona}">${p.nombre} ${p.paterno} ${p.materno}</option>
                    `).join('')}
                </select>
            </div>
        `;
    }

    function validatePersonasStep() {
        const requiredFields = document.querySelectorAll('#step-2 select[required]');
        for (let field of requiredFields) {
            if (!field.value) {
                alert('Por favor completa todos los campos obligatorios de personas.');
                field.focus();
                return false;
            }
        }
        return true;
    }

    function validateDetallesStep() {
        const requiredFields = document.querySelectorAll('#step-3 [required]');
        for (let field of requiredFields) {
            if (!field.value) {
                alert('Por favor completa todos los campos obligatorios de detalles.');
                field.focus();
                return false;
            }
        }
        return true;
    }

    function loadConfirmationContent() {
        const container = document.getElementById('summary-content');
        const formData = new FormData(form);
        
        let summary = `
            <p><strong>Sacramento:</strong> ${selectedSacramento.nombre}</p>
            <p><strong>Fecha:</strong> ${formData.get('fecha') || 'No especificada'}</p>
        `;

        // Agregar información de personas según el tipo
        const personaSelect = document.querySelector('select[name="cve_persona"]');
        if (personaSelect && personaSelect.value) {
            const personaText = personaSelect.options[personaSelect.selectedIndex].text;
            summary += `<p><strong>Persona principal:</strong> ${personaText}</p>`;
        }

        // Agregar información específica del sacramento
        if (selectedSacramento.tipo === 'matrimonio') {
            const persona2Select = document.querySelector('select[name="cve_persona2"]');
            if (persona2Select && persona2Select.value) {
                const persona2Text = persona2Select.options[persona2Select.selectedIndex].text;
                summary += `<p><strong>Segunda persona:</strong> ${persona2Text}</p>`;
            }
        }

        container.innerHTML = summary;
    }

    function clearSelections() {
        document.querySelectorAll('.sacramento-card').forEach(c => c.classList.remove('selected'));
        nextBtn.disabled = true;
    }

    // Inicializar
    updateStepDisplay();
    nextBtn.disabled = true;
});
