document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentScope = 'general';
    let currentEventId = null;
    let canalesData = {
        general: null,
        eventos: {}
    };
    
    // Elementos del DOM
    const scopeToggle = document.getElementById('toggle-scope');
    const scopeLabel = document.getElementById('scope-label');
    
    // Inicializar tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
});    
    // Cargar datos iniciales (general)
    loadCanalesData('general');
    
    // Configurar evento change para el select de eventos
    $('#selectEvento').on('change', function() {
        currentEventId = $(this).val() || null;
        if (currentScope === 'evento' && currentEventId) {
            loadCanalesData('evento', currentEventId);
        }
    });
    
    // Configurar toggle entre general/evento
    if (scopeToggle) {
        scopeToggle.addEventListener('change', function() {
            currentScope = this.checked ? 'evento' : 'general';
            updateScopeUI();
            
            if (currentScope === 'evento' && currentEventId) {
                loadCanalesData('evento', currentEventId);
            } else {
                loadCanalesData('general');
            }
        });
    }
    
    // Función para cargar datos de canales
    function loadCanalesData(scope, eventoId = null) {
        const url = `/canales-contacto/${scope}${eventoId ? '/' + eventoId : ''}`;
        const loadingElement = document.getElementById('loading-canales');
        
        // Mostrar estado de carga
        if (loadingElement) loadingElement.classList.remove('d-none');
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Guardar datos en cache
                if (scope === 'general') {
                    canalesData.general = data;
                } else {
                    canalesData.eventos[eventoId] = data;
                }
                
                updateCanalesUI(data);
            })
            .catch(error => {
                console.error(`Error cargando datos de ${scope}:`, error);
                // Mostrar datos en cache si hay error
                const cachedData = scope === 'general' ? 
                    canalesData.general : 
                    canalesData.eventos[eventoId];
                
                if (cachedData) {
                    updateCanalesUI(cachedData);
                } else {
                    showErrorState();
                }
            })
            .finally(() => {
                if (loadingElement) loadingElement.classList.add('d-none');
            });
    }
    
    // Función para actualizar la UI
    function updateCanalesUI(data) {
        // Actualizar porcentajes
        document.querySelector('[data-canal="llamadas"]').textContent = 
            `${data.porcentajes.llamadas}% de interacciones`;
        document.querySelector('[data-canal="whatsapp"]').textContent = 
            `${data.porcentajes.whatsapp}% de interacciones`;
        document.querySelector('[data-canal="correos"]').textContent = 
            `${data.porcentajes.correos}% de interacciones`;
        
        // Actualizar tooltips con conteos absolutos
        $('[data-canal="llamadas"]').attr('data-bs-original-title', 
            `${data.llamadas} llamadas`).tooltip('update');
        $('[data-canal="whatsapp"]').attr('data-bs-original-title', 
            `${data.whatsapp} contactos por WhatsApp`).tooltip('update');
        $('[data-canal="correos"]').attr('data-bs-original-title', 
            `${data.correos} correos electrónicos`).tooltip('update');
        
        // Actualizar estado del scope
        updateScopeUI();
    }
    
    // Función para actualizar la UI del scope
    function updateScopeUI() {
        if (!scopeLabel) return;
        
        if (currentScope === 'general') {
            scopeLabel.textContent = 'Vista General';
            scopeLabel.classList.remove('text-primary');
            scopeLabel.classList.add('text-muted');
        } else {
            scopeLabel.textContent = `Vista por Evento ${currentEventId ? '(Filtrado)' : ''}`;
            scopeLabel.classList.remove('text-muted');
            scopeLabel.classList.add('text-primary');
        }
    }
    
    // Función para mostrar estado de error
    function showErrorState() {
        document.querySelector('[data-canal="llamadas"]').textContent = 'N/A';
        document.querySelector('[data-canal="whatsapp"]').textContent = 'N/A';
        document.querySelector('[data-canal="correos"]').textContent = 'N/A';
        
        // Mostrar mensaje de error
        const errorElement = document.getElementById('error-canales');
        if (errorElement) {
            errorElement.classList.remove('d-none');
            setTimeout(() => errorElement.classList.add('d-none'), 3000);
        }
    }
});