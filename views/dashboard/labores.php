<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Asignación de Labores - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Labor.php';
require_once __DIR__ . '/../../models/Lote.php';
require_once __DIR__ . '/../../models/Usuario.php';

$db = getConnection();
$laborModel = new Labor($db);
$loteModel = new Lote($db);
$usuarioModel = new Usuario($db);

$labores = $laborModel->obtenerTodos();
$lotes = $loteModel->obtenerTodos();
$usuarios = $usuarioModel->obtenerTodos();

$totalLabores = count($labores);
$laboresPendientes = count(array_filter($labores, function($l) { return $l['Estado'] == 'Pendiente'; }));
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
                    Asignación de <span class="text-emerald-300">Labores</span>
                </h2>
                <p class="text-emerald-50 text-lg md:text-xl font-light">Gestión de tareas diarias y seguimiento de actividades en campo.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="openModal('modalLabor')"
                    class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 hover:scale-105 flex items-center justify-center gap-3 whitespace-nowrap">
                    <i class="fas fa-tasks text-emerald-600 group-hover:rotate-12 transition-transform text-lg"></i>
                    Nueva Labor
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Labores</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo $totalLabores; ?></p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-clipboard-list text-2xl text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Pendientes</p>
                    <p class="text-3xl font-extrabold text-amber-600"><?php echo $laboresPendientes; ?></p>
                </div>
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-clock text-2xl text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-calendar-check text-emerald-600"></i> Actividades Programadas
            </h3>
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="search" placeholder="Buscar labor..." class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-white shadow-sm">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Labor / Tarea</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Asignación</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Fechas</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($labores)): ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500 font-medium">
                                <i class="fas fa-info-circle mr-2"></i> No hay labores registradas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($labores as $l): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                            <i class="fas fa-tools text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($l['Tarea']); ?></p>
                                            <p class="text-sm text-gray-500 font-medium"><?php echo htmlspecialchars($l['Descripcionlabor']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800">Lote: <?php echo htmlspecialchars($l['LoteNombre'] ?? 'N/A'); ?></span>
                                        <span class="text-xs text-gray-500">Trabajador ID: <?php echo $l['IDtrabajador']; ?></span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col gap-1 text-sm">
                                        <span class="text-emerald-700 font-medium"><i class="fas fa-sign-in-alt mr-2"></i><?php echo $l['Fechainicio']; ?></span>
                                        <span class="text-red-700 font-medium"><i class="fas fa-sign-out-alt mr-2"></i><?php echo $l['Fechafin']; ?></span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <?php 
                                    $est = strtolower($l['Estado']);
                                    $class = "bg-gray-100 text-gray-700";
                                    if ($est == 'pendiente') $class = "bg-amber-50 text-amber-700 ring-1 ring-amber-200";
                                    if ($est == 'completada') $class = "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200";
                                    ?>
                                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider <?php echo $class; ?>">
                                        <?php echo $l['Estado']; ?>
                                    </span>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($l['Estado'] != 'Completada'): ?>
                                            <a href="../../controllers/LaborController.php?accion=completar&id=<?php echo $l['IDasignaciondelabor']; ?>" class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Marcar como completada">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button onclick='editarLabor(<?php echo json_encode($l); ?>)' class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button onclick="confirmarEliminarLabor(<?php echo $l['IDasignaciondelabor']; ?>)" class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
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

<!-- Modal Labor (Crear/Editar) -->
<div id="modalLabor" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-8 flex justify-between items-center text-white relative">
            <h3 id="modalTitleLabor" class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <i class="fas fa-tasks"></i>
                <span>Nueva Labor</span>
            </h3>
            <button onclick="closeModal('modalLabor')" class="text-emerald-100 hover:text-white transition-colors relative z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="formLabor" action="../../controllers/LaborController.php?accion=crear" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id_labor" id="id_labor">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tarea <span class="text-red-500">*</span></label>
                    <input type="text" name="tarea" id="tarea_labor" placeholder="Ej: Riego del sector A" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Trabajador <span class="text-red-500">*</span></label>
                    <select name="id_trabajador" id="id_trabajador_labor" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?php echo $u['IDusuario']; ?>"><?php echo htmlspecialchars($u['Nombre']); ?> (<?php echo $u['Niveldeacceso']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lote <span class="text-red-500">*</span></label>
                    <select name="id_lote" id="id_lote_labor" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                        <?php foreach ($lotes as $l): ?>
                            <option value="<?php echo $l['IDlote']; ?>"><?php echo htmlspecialchars($l['Nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                    <select name="estado" id="estado_labor" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Inicio <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="inicio" id="inicio_labor" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Fin <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="fin" id="fin_labor" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion_labor" rows="3" placeholder="Instrucciones adicionales..." class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50 resize-none"></textarea>
            </div>
            
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalLabor')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Labor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalLabor' && !document.getElementById('id_labor').value) {
        document.getElementById('modalTitleLabor').querySelector('span').innerText = 'Nueva Labor';
        document.getElementById('formLabor').action = '../../controllers/LaborController.php?accion=crear';
        document.getElementById('formLabor').reset();
        // Fecha y hora actual automática
        const ahora = new Date();
        const local = ahora.getFullYear() + '-' +
            String(ahora.getMonth()+1).padStart(2,'0') + '-' +
            String(ahora.getDate()).padStart(2,'0') + 'T' +
            String(ahora.getHours()).padStart(2,'0') + ':' +
            String(ahora.getMinutes()).padStart(2,'0');
        document.getElementById('inicio_labor').value = local;
        document.getElementById('fin_labor').value = local;
    }
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editarLabor(l) {
    document.getElementById('modalTitleLabor').querySelector('span').innerText = 'Editar Labor';
    document.getElementById('formLabor').action = '../../controllers/LaborController.php?accion=editar';
    document.getElementById('id_labor').value = l.IDasignaciondelabor;
    document.getElementById('tarea_labor').value = l.Tarea;
    document.getElementById('id_trabajador_labor').value = l.IDtrabajador;
    document.getElementById('id_lote_labor').value = l.IDlote;
    document.getElementById('estado_labor').value = l.Estado;
    document.getElementById('inicio_labor').value = l.Fechainicio.replace(' ', 'T');
    document.getElementById('fin_labor').value = l.Fechafin.replace(' ', 'T');
    document.getElementById('descripcion_labor').value = l.Descripcionlabor;
    openModal('modalLabor');
}

function confirmarEliminarLabor(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará la asignación de labor permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../controllers/LaborController.php?accion=eliminar';
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'id'; input.value = id;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
