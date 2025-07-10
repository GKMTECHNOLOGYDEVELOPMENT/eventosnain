document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentEventId = 'general';
    
    // Inicializar tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
});
    
    // Cargar métricas de seguimiento cuando cambia el evento
    $('#selectEvento').on('change', function() {
        currentEventId = $(this).val() || 'general';
        loadTrackingMetrics(currentEventId);
    });
    
    // Cargar métricas iniciales
    loadTrackingMetrics(currentEventId);
    
    // Configurar eventos para los modals
    setupModalEvents();
    
    // Función para cargar las métricas de seguimiento
    function loadTrackingMetrics(eventoId) {
        fetch(`/metricas-seguimiento/${eventoId}`)
            .then(response => response.json())
            .then(data => {
                updateTrackingMetricsUI(data);
            })
            .catch(error => {
                console.error('Error cargando métricas:', error);
            });
    }
    
    // Función para actualizar la UI con las métricas
    function updateTrackingMetricsUI(data) {
        // Actualizar contadores
        $('[data-metric="clientes-riesgo"]').text(data.total_clientes_riesgo || 0);
        $('[data-metric="promedio-dias"]').text(data.promedio_dias ? `${data.promedio_dias} días` : 'N/A');
        $('[data-metric="proceso-estancado"]').text(data.total_proceso_estancado || 0);
        $('[data-metric="interaccion-completa"]').text(data.total_interaccion_completa || 0);
    }
    
    // Función para configurar los eventos de los modals
    function setupModalEvents() {
        // Modal de Clientes en Riesgo
        $('#modalClientesRiesgo').on('show.bs.modal', function() {
            loadClientesModal('riesgo', currentEventId, '#tabla-clientes-riesgo');
        });
        
        // Modal de Proceso Estancado
        $('#modalProcesoEstancado').on('show.bs.modal', function() {
            loadClientesModal('estancado', currentEventId, '#tabla-proceso-estancado');
        });
        
        // Modal de Interacción Completa
        $('#modalInteraccionCompleta').on('show.bs.modal', function() {
            loadClientesModal('completa', currentEventId, '#tabla-interaccion-completa');
        });
    }
    
    // Función para cargar clientes en los modals
    function loadClientesModal(tipo, eventoId, targetElement) {
        $(targetElement).html('<div class="text-center my-4"><div class="spinner-border text-primary" role="status"></div></div>');
        
        fetch(`/metricas-seguimiento-detalle/${tipo}/${eventoId}`)
            .then(response => response.json())
            .then(clientes => {
                if (clientes.length === 0) {
                    $(targetElement).html('<p class="text-center text-muted">No hay clientes en esta categoría</p>');
                    return;
                }
                
                // Generar tabla HTML
                let tableHtml = `
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Empresa</th>
                                    <th>Contacto</th>
                                    <th>Registro</th>
                                    ${tipo === 'estancado' ? '<th>Faltantes</th>' : ''}
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                clientes.forEach(cliente => {
                    const fechaRegistro = new Date(cliente.fecharegistro).toLocaleDateString('es-PE');
                    const contacto = cliente.telefono ? `${cliente.telefono}<br>${cliente.email}` : cliente.email;
                    
                    tableHtml += `
                        <tr>
                            <td>${cliente.nombre || 'N/A'}</td>
                            <td>${cliente.empresa || 'N/A'}</td>
                            <td>${contacto || 'N/A'}</td>
                            <td>${fechaRegistro}</td>
                            ${tipo === 'estancado' ? `<td>${getFaltantesInteraccion(cliente)}</td>` : ''}
                        </tr>
                    `;
                });
                
                tableHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
                
                $(targetElement).html(tableHtml);
            })
            .catch(error => {
                console.error(`Error cargando clientes ${tipo}:`, error);
                $(targetElement).html('<p class="text-center text-danger">Error al cargar los clientes</p>');
            });
    }
    
    // Función auxiliar para obtener interacciones faltantes
    function getFaltantesInteraccion(cliente) {
        const faltantes = [];
        if (!cliente.llamada) faltantes.push('Llamada');
        if (!cliente.whatsapp) faltantes.push('WhatsApp');
        if (!cliente.reunion) faltantes.push('Reunión');
        if (!cliente.contrato) faltantes.push('Contrato');
        
        return faltantes.join(', ') || 'Todos completos';
    }
});