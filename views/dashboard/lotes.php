<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Gestión de Lotes - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Lote.php';

$db = getConnection();
$loteModel = new Lote($db);
$lotes = $loteModel->obtenerTodos();

$totalLotes = count($lotes);
$lotesActivos = count(array_filter($lotes, function($l) { return $l['Estado'] == 'Activo'; }));
$totalArea = array_sum(array_column($lotes, 'Area'));
?>

<style>
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
                    Gestión de <span class="text-emerald-300">Lotes</span>
                </h2>
                <p class="text-emerald-50 text-lg md:text-xl font-light">Administración de parcelas y terrenos de cultivo en la finca.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="openModal('modalLote')"
                    class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 hover:scale-105 flex items-center justify-center gap-3 whitespace-nowrap">
                    <i class="fas fa-map-marked-alt text-emerald-600 group-hover:rotate-12 transition-transform text-lg"></i>
                    Nuevo Lote
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Lotes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Lotes</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo $totalLotes; ?></p>
                    <p class="text-xs font-bold text-emerald-500 mt-2"><i class="fas fa-arrow-up mr-1"></i>Sistema actualizado</p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-layer-group text-2xl text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Hectáreas Totales</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo number_format($totalArea, 1); ?></p>
                    <p class="text-xs font-bold text-emerald-500 mt-2"><i class="fas fa-expand mr-1"></i>Superficie total</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-expand text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Lotes Activos</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo $lotesActivos; ?></p>
                    <p class="text-xs font-bold text-emerald-600 mt-2"><i class="fas fa-check-circle mr-1"></i>En uso</p>
                </div>
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-seedling text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-map text-emerald-600"></i> Lista de Lotes
            </h3>
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="search" placeholder="Buscar lote..." class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-white shadow-sm">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Lote</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Ubicación & Área</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($lotes)): ?>
                        <tr>
                            <td colspan="4" class="p-10 text-center text-gray-500 font-medium">
                                <i class="fas fa-info-circle mr-2"></i> No hay lotes registrados todavía.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($lotes as $l): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm font-bold uppercase">
                                            <?php echo substr($l['Nombre'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($l['Nombre']); ?></p>
                                            <p class="text-sm text-gray-500 font-medium">ID: <?php echo $l['IDlote']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-gray-800 font-medium text-sm"><i class="fas fa-map-marker-alt text-gray-400 mr-2"></i> <?php echo htmlspecialchars($l['Ubicacion']); ?></span>
                                        <span class="text-gray-500 font-bold text-sm"><i class="fas fa-expand text-gray-400 mr-2"></i> <?php echo $l['Area']; ?> Hectáreas</span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <?php 
                                    $statusClass = ($l['Estado'] == 'Activo') ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200';
                                    $dotClass = ($l['Estado'] == 'Activo') ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500';
                                    ?>
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider <?php echo $statusClass; ?>">
                                        <span class="w-2 h-2 rounded-full <?php echo $dotClass; ?>"></span> <?php echo $l['Estado']; ?>
                                    </span>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick='editarLote(<?php echo json_encode($l); ?>)' class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button onclick="confirmarEliminarLote(<?php echo $l['IDlote']; ?>)" class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5" title="Eliminar">
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

<!-- Modal Lote (Crear/Editar) -->
<div id="modalLote" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-8 flex justify-between items-center text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 id="modalTitleLote" class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
                <span>Nuevo Lote</span>
            </h3>
            <button onclick="closeModal('modalLote')" class="text-emerald-100 hover:text-white transition-colors relative z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="formLote" action="../../controllers/LoteController.php?accion=crear" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id_lote" id="id_lote">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Lote <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" id="nombre_lote" placeholder="Ej: Lote Norte A" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ubicación <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                        <input type="text" name="ubicacion" id="ubicacion_lote" placeholder="Zona y sector" required class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Área (hectáreas) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-expand"></i></div>
                        <input type="number" step="0.01" name="area" id="area_lote" placeholder="Ej: 10.5" required class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                    </div>
                </div>
            </div>
            
            <div id="containerEstado" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                <div class="relative">
                    <select name="estado" id="estado_lote" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50 appearance-none">
                        <option value="Activo">Activo</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-xs"></i></div>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalLote')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Lote
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalLote' && !document.getElementById('id_lote').value) {
        document.getElementById('modalTitleLote').querySelector('span').innerText = 'Nuevo Lote';
        document.getElementById('formLote').action = '../../controllers/LoteController.php?accion=crear';
        document.getElementById('formLote').reset();
        document.getElementById('containerEstado').classList.add('hidden');
    }
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editarLote(lote) {
    document.getElementById('modalTitleLote').querySelector('span').innerText = 'Editar Lote';
    document.getElementById('formLote').action = '../../controllers/LoteController.php?accion=editar';
    document.getElementById('id_lote').value = lote.IDlote;
    document.getElementById('nombre_lote').value = lote.Nombre;
    document.getElementById('ubicacion_lote').value = lote.Ubicacion;
    document.getElementById('area_lote').value = lote.Area;
    document.getElementById('estado_lote').value = lote.Estado;
    document.getElementById('containerEstado').classList.remove('hidden');
    openModal('modalLote');
}

function confirmarEliminarLote(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el lote permanentemente.",
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
            form.action = '../../controllers/LoteController.php?accion=eliminar';
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
