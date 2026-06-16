<?php
ob_start();
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    ob_end_clean();
    exit;
}

$titulo = "Gestión de Producción - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Produccion.php';
require_once __DIR__ . '/../../models/Cultivo.php';

$db = getConnection();
$produccionModel = new Produccion($db);
$cultivoModel = new Cultivo($db);

$producciones = $produccionModel->obtenerTodas();
$cultivos = $cultivoModel->obtenerTodos();

$totalProduccionMes = array_sum(array_column($producciones, 'Cantidad'));
$totalRegistros = count($producciones);
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                    Gestión de <span class="text-emerald-300">Producción</span>
                </h2>
                <p class="text-emerald-50 text-lg md:text-xl font-light">Registro y control de cosechas y rendimientos agrícolas.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="openModal('modalProduccion')"
                    class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 hover:scale-105 flex items-center justify-center gap-3 whitespace-nowrap">
                    <i class="fas fa-plus-circle text-emerald-600 group-hover:rotate-12 transition-transform text-lg"></i>
                    Nuevo Registro
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Registros</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo $totalRegistros; ?></p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-list-ol text-2xl text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Volumen Total Producido</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo number_format($totalProduccionMes, 2); ?> Unidades</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-chart-bar text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-box-open text-emerald-600"></i> Historial de Producción
            </h3>
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="search" placeholder="Buscar producción..." class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-white shadow-sm">
            </div>
        </div>
         <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Cultivo</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Cantidad</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Costo</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Tipo</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($producciones)): ?>
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-500 font-medium">
                                <i class="fas fa-info-circle mr-2"></i> No hay registros de producción.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($producciones as $p): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900"><?php echo htmlspecialchars($p['CultivoNombre'] ?? 'Cultivo ID: '.$p['IDcultivo']); ?></p>
                                            <p class="text-xs text-gray-400"># Reg <?php echo $p['IDproduccion']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <span class="text-gray-900 font-bold text-lg"><?php echo number_format($p['Cantidad'], 2); ?></span>
                                </td>
                                <td class="p-6">
                                    <span class="text-emerald-700 font-bold">$ <?php echo number_format($p['Costo'], 2); ?></span>
                                </td>
                                <td class="p-6">
                                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase bg-emerald-50 text-emerald-700">
                                        <?php echo htmlspecialchars($p['Tipo'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td class="p-6">
                                    <span class="text-gray-600 font-medium"><i class="fas fa-calendar-day text-gray-400 mr-2"></i><?php echo htmlspecialchars($p['Fecha'] ?? '-'); ?></span>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick='editarProduccion(<?php echo json_encode($p); ?>)' class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button onclick="confirmarEliminarProduccion(<?php echo $p['IDproduccion']; ?>)" class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Producción (Crear/Editar) -->
<div id="modalProduccion" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-8 flex justify-between items-center text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 id="modalTitleProduccion" class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <span>Nuevo Registro de Producción</span>
            </h3>
            <button onclick="closeModal('modalProduccion')" class="text-emerald-100 hover:text-white transition-colors relative z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="formProduccion" action="../../controllers/ProduccionController.php?accion=crear" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id_produccion" id="id_produccion">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cultivo <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="id_cultivo" id="id_cultivo_produccion" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50 appearance-none">
                            <option value="">Seleccione un cultivo...</option>
                            <?php foreach ($cultivos as $c): ?>
                                <option value="<?php echo $c['IDcultivo']; ?>"><?php echo htmlspecialchars($c['Nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-xs"></i></div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" id="fecha_produccion" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
    Cantidad (Arrobas) <span class="text-red-500">*</span>
</label>
<input 
    type="number" 
    step="0.01" 
    min="0" 
    name="cantidad" 
    id="cantidad_produccion" 
    required 
    placeholder="Ej: 25 arrobas"
    class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Costo (COP) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="costo" id="costo_produccion" required placeholder="0.00" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" id="tipo_produccion" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                        <option value="Cosecha">Cosecha</option>
                        <option value="Poscosecha">Poscosecha</option>
                        <option value="Secado">Secado</option>
                        <option value="Trilla">Trilla</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalProduccion')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Registro
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalProduccion' && !document.getElementById('id_produccion').value) {
        document.getElementById('modalTitleProduccion').querySelector('span').innerText = 'Nuevo Registro de Producción';
        document.getElementById('formProduccion').action = '../../controllers/ProduccionController.php?accion=crear';
        document.getElementById('formProduccion').reset();
        // Fecha actual automática
        document.getElementById('fecha_produccion').value = new Date().toISOString().split('T')[0];
    }
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editarProduccion(p) {
    document.getElementById('modalTitleProduccion').querySelector('span').innerText = 'Editar Registro';
    document.getElementById('formProduccion').action = '../../controllers/ProduccionController.php?accion=editar';
    document.getElementById('id_produccion').value    = p.IDproduccion;
    document.getElementById('id_cultivo_produccion').value = p.IDcultivo;
    document.getElementById('fecha_produccion').value     = p.Fecha;
    document.getElementById('cantidad_produccion').value  = p.Cantidad;
    document.getElementById('costo_produccion').value     = p.Costo;
    document.getElementById('tipo_produccion').value      = p.Tipo;
    openModal('modalProduccion');
}

function confirmarEliminarProduccion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el registro de producción permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../controllers/ProduccionController.php?accion=eliminar';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
