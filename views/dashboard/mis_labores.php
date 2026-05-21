<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

// Verificar que el usuario sea trabajador o mayordomo
if ($_SESSION['usuario']['rol'] !== 'trabajador' && $_SESSION['usuario']['rol'] !== 'mayordomo') {
    header("Location: ../produccion/dashboard.php");
    exit;
}

$titulo = "Mis Labores - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Header de Mis Labores -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mis Labores Asignadas</h2>
            <p class="text-gray-600 mt-1">Tareas pendientes de confirmación y en progreso</p>
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
            <div class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                <i class="fas fa-user mr-2"></i><?= $_SESSION['usuario']['Nombre'] ?>
            </div>
            <button onclick="openModal('modalFiltrosLabores')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                <i class="fas fa-filter mr-2"></i>Filtros
            </button>
        </div>
    </div>
</div>

<!-- Estadísticas Personales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pendientes</p>
                <p class="text-2xl font-bold text-gray-800">3</p>
                <p class="text-xs text-orange-600 mt-1">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Por aceptar
                </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-orange-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Confirmadas</p>
                <p class="text-2xl font-bold text-gray-800">2</p>
                <p class="text-xs text-blue-600 mt-1">
                    <i class="fas fa-check-circle mr-1"></i>Listas para iniciar
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check text-blue-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">En Progreso</p>
                <p class="text-2xl font-bold text-gray-800">1</p>
                <p class="text-xs text-yellow-600 mt-1">
                    <i class="fas fa-spinner mr-1"></i>Ejecutando
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-play text-yellow-600"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Completadas Hoy</p>
                <p class="text-2xl font-bold text-gray-800">4</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-trophy mr-1"></i>Buen rendimiento
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-trophy text-green-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Alerta de Tareas Pendientes -->
<?php if (true): // Simulación de tareas pendientes ?>
<div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-orange-800">Tienes 3 tareas pendientes de aceptación</h3>
            <div class="mt-2 text-sm text-orange-700">
                <p>Por favor revisa y acepta o rechaza las tareas asignadas para hoy.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabs de Labores -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <button onclick="cambiarTab('pendientes')" class="tab-btn px-4 py-3 text-sm font-medium text-orange-600 border-b-2 border-orange-600" data-tab="pendientes">
                Pendientes (3)
            </button>
            <button onclick="cambiarTab('confirmadas')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="confirmadas">
                Confirmadas (2)
            </button>
            <button onclick="cambiarTab('progreso')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="progreso">
                En Progreso (1)
            </button>
            <button onclick="cambiarTab('completadas')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-tab="completadas">
                Completadas (4)
            </button>
        </nav>
    </div>
</div>

<!-- Tabla de Labores del Trabajador -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Mis Tareas</h3>
        <div class="flex items-center gap-2">
            <input type="search" placeholder="Buscar tarea..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Todos los tipos</option>
                <option value="riego">Riego</option>
                <option value="siembra">Siembra</option>
                <option value="fertilizacion">Fertilización</option>
                <option value="cosecha">Cosecha</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tarea</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Lote</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="p-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 bg-orange-50">
                    <td class="p-3 text-sm font-medium text-gray-900">Riego de maíz - Sector A</td>
                    <td class="p-3 text-sm text-gray-600">LOT-001</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Riego</span>
                    </td>
                    <td class="p-3 text-sm text-gray-600">Hoy, 06:00</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Alta</span>
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full animate-pulse">Pendiente</span>
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesTarea('TAR-001')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="aceptarTarea('TAR-001')" class="text-green-600 hover:text-green-800 mr-2" title="Aceptar tarea">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="rechazarTarea('TAR-001')" class="text-red-600 hover:text-red-800" title="Rechazar tarea">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 bg-orange-50">
                    <td class="p-3 text-sm font-medium text-gray-900">Fertilización de tomates</td>
                    <td class="p-3 text-sm text-gray-600">LOT-002</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">Fertilización</span>
                    </td>
                    <td class="p-3 text-sm text-gray-600">Hoy, 08:00</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">Media</span>
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full animate-pulse">Pendiente</span>
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesTarea('TAR-002')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="aceptarTarea('TAR-002')" class="text-green-600 hover:text-green-800 mr-2" title="Aceptar tarea">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="rechazarTarea('TAR-002')" class="text-red-600 hover:text-red-800" title="Rechazar tarea">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 bg-blue-50">
                    <td class="p-3 text-sm font-medium text-gray-900">Siembra de fríjol</td>
                    <td class="p-3 text-sm text-gray-600">LOT-003</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Siembra</span>
                    </td>
                    <td class="p-3 text-sm text-gray-600">Hoy, 10:00</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">Media</span>
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Confirmada</span>
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesTarea('TAR-003')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="iniciarTarea('TAR-003')" class="text-green-600 hover:text-green-800 mr-2" title="Iniciar tarea">
                            <i class="fas fa-play"></i>
                        </button>
                        <button onclick="reportarProgreso('TAR-003')" class="text-purple-600 hover:text-purple-800" title="Reportar progreso">
                            <i class="fas fa-chart-line"></i>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 bg-yellow-50">
                    <td class="p-3 text-sm font-medium text-gray-900">Mantenimiento de cercas</td>
                    <td class="p-3 text-sm text-gray-600">LOT-004</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Mantenimiento</span>
                    </td>
                    <td class="p-3 text-sm text-gray-600">Hoy, 07:00</td>
                    <td class="p-3 text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Alta</span>
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">En Progreso</span>
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="verDetallesTarea('TAR-004')" class="text-blue-600 hover:text-blue-800 mr-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="pausarTarea('TAR-004')" class="text-orange-600 hover:text-orange-800 mr-2" title="Pausar tarea">
                            <i class="fas fa-pause"></i>
                        </button>
                        <button onclick="completarTarea('TAR-004')" class="text-green-600 hover:text-green-800" title="Completar tarea">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detalles de Tarea -->
<div id="modalDetallesTarea" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Detalles de la Tarea</h3>
            <button onclick="closeModal('modalDetallesTarea')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600">Tarea</p>
                    <p class="font-semibold text-gray-800">Riego de maíz - Sector A</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Lote</p>
                    <p class="font-semibold text-gray-800">LOT-001</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tipo</p>
                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Riego</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Prioridad</p>
                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Alta</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha Programada</p>
                    <p class="font-semibold text-gray-800">Hoy, 06:00 - 10:00</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Asignado por</p>
                    <p class="font-semibold text-gray-800">Carlos Rodríguez (Mayordomo)</p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Descripción</p>
                <p class="text-gray-800 bg-gray-50 p-3 rounded-lg">Realizar riego por goteo en el sector A del lote 001. Cubrir aproximadamente 5 hectáreas. Verificar que todos los emisores funcionen correctamente. Revisar presión del sistema antes de iniciar.</p>
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Materiales Requeridos</p>
                <ul class="space-y-1">
                    <li class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Sistema de riego conectado
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Herramientas de mantenimiento básicas
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Equipo de protección personal
                    </li>
                </ul>
            </div>
            
            <div class="flex justify-end gap-3">
                <button onclick="closeModal('modalDetallesTarea')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    Cerrar
                </button>
                <button onclick="aceptarTarea('TAR-001')" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg">
                    <i class="fas fa-check mr-2"></i>Aceptar Tarea
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div id="modalFiltrosLabores" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Filtros de Tareas</h3>
            <button onclick="closeModal('modalFiltrosLabores')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Tarea</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos los tipos</option>
                    <option value="riego">Riego</option>
                    <option value="siembra">Siembra</option>
                    <option value="fertilizacion">Fertilización</option>
                    <option value="cosecha">Cosecha</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="en_progreso">En Progreso</option>
                    <option value="completada">Completada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todas las prioridades</option>
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button onclick="limpiarFiltros()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    Limpiar
                </button>
                <button onclick="aplicarFiltros()" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg">
                    Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cambiarTab(tab) {
    // Actualizar estilos de tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-orange-600', 'text-blue-600', 'text-yellow-600', 'text-green-600', 'border-orange-600', 'border-blue-600', 'border-yellow-600', 'border-green-600');
        btn.classList.add('text-gray-500', 'border-transparent');
    });
    
    const activeTab = document.querySelector(`[data-tab="${tab}"]`);
    const colorMap = {
        'pendientes': 'text-orange-600 border-orange-600',
        'confirmadas': 'text-blue-600 border-blue-600',
        'progreso': 'text-yellow-600 border-yellow-600',
        'completadas': 'text-green-600 border-green-600'
    };
    
    activeTab.classList.remove('text-gray-500', 'border-transparent');
    activeTab.classList.add(...colorMap[tab].split(' '));
    
    console.log('Cambiando a tab:', tab);
}

function verDetallesTarea(codigo) {
    openModal('modalDetallesTarea');
}

function aceptarTarea(codigo) {
    Swal.fire({
        title: 'Aceptar Tarea',
        text: '¿Estás seguro de aceptar esta tarea? Una vez aceptada, deberás completarla según lo programado.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '¡Tarea Aceptada!',
                text: 'Has aceptado la tarea. Ahora aparece como "Confirmada" y puedes iniciarla cuando estés listo.',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
}

function rechazarTarea(codigo) {
    Swal.fire({
        title: 'Rechazar Tarea',
        input: 'textarea',
        inputLabel: 'Motivo del rechazo',
        inputPlaceholder: 'Por favor explica por qué no puedes realizar esta tarea...',
        inputAttributes: {
            'aria-label': 'Motivo del rechazo',
            'rows': 4
        },
        showCancelButton: true,
        confirmButtonText: 'Rechazar tarea',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes proporcionar un motivo para rechazar la tarea'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Tarea Rechazada',
                html: `
                    <div class="text-left">
                        <p>La tarea ha sido rechazada y será notificada al mayordomo.</p>
                        <p class="mt-2"><strong>Motivo registrado:</strong></p>
                        <p class="text-sm text-gray-600 bg-gray-100 p-2 rounded">"${result.value}"</p>
                    </div>
                `,
                icon: 'warning'
            });
        }
    });
}

function iniciarTarea(codigo) {
    Swal.fire({
        title: 'Iniciar Tarea',
        text: '¿Estás listo para comenzar esta tarea ahora?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Comenzar',
        cancelButtonText: 'Después'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Tarea Iniciada',
                text: 'Has comenzado la tarea. ¡Buen trabajo!',
                icon: 'success',
                timer: 2000
            });
        }
    });
}

function pausarTarea(codigo) {
    Swal.fire({
        title: 'Pausar Tarea',
        text: '¿Deseas pausar esta tarea temporalmente?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Pausar',
        cancelButtonText: 'Continuar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Tarea Pausada', 'Puedes reanudarla más tarde', 'info');
        }
    });
}

function completarTarea(codigo) {
    Swal.fire({
        title: 'Completar Tarea',
        input: 'textarea',
        inputLabel: 'Reporte de completion',
        inputPlaceholder: 'Describe cómo completaste la tarea y cualquier observación importante...',
        showCancelButton: true,
        confirmButtonText: 'Completar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes proporcionar un reporte de completion'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '¡Tarea Completada!',
                text: 'Excelente trabajo. La tarea ha sido marcada como completada.',
                icon: 'success',
                timer: 3000
            });
        }
    });
}

function reportarProgreso(codigo) {
    Swal.fire({
        title: 'Reportar Progreso',
        input: 'textarea',
        inputLabel: 'Actualización de progreso',
        inputPlaceholder: 'Describe el progreso actual de la tarea...',
        showCancelButton: true,
        confirmButtonText: 'Enviar Reporte',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Reporte Enviado', 'Tu progreso ha sido registrado', 'success');
        }
    });
}

function limpiarFiltros() {
    document.querySelectorAll('#modalFiltrosLabores select, #modalFiltrosLabores input').forEach(element => {
        element.value = '';
    });
}

function aplicarFiltros() {
    closeModal('modalFiltrosLabores');
    Swal.fire('Filtros aplicados', '', 'success');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
