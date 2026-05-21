<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Control de Cultivos - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/ControlCultivo.php';
require_once __DIR__ . '/../../models/Cultivo.php';
require_once __DIR__ . '/../../models/Fase.php';

$db      = getConnection();
$model   = new ControlCultivo($db);
$cultModel = new Cultivo($db);
$faseModel = new Fase($db);

$registros = $model->obtenerTodos();
$cultivos  = $cultModel->obtenerTodos();
$fases     = $faseModel->obtenerTodos();

// Contadores
$total    = count($registros);
$abiertos = count(array_filter($registros, fn($r) => $r['Estado'] === 'abierto'));
$proceso  = count(array_filter($registros, fn($r) => $r['Estado'] === 'proceso'));
$resueltos= count(array_filter($registros, fn($r) => $r['Estado'] === 'resuelto'));

// Colores y etiquetas por estado
function estadoClase(string $estado): string {
    return match($estado) {
        'abierto'  => 'bg-red-50 text-red-700 ring-1 ring-red-200',
        'proceso'  => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'resuelto' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        default    => 'bg-gray-100 text-gray-600',
    };
}
function estadoDot(string $estado): string {
    return match($estado) {
        'abierto'  => 'bg-red-500 animate-pulse',
        'proceso'  => 'bg-amber-500 animate-pulse',
        'resuelto' => 'bg-emerald-500',
        default    => 'bg-gray-400',
    };
}
function estadoIcono(string $estado): string {
    return match($estado) {
        'abierto'  => 'fa-circle-exclamation text-red-500',
        'proceso'  => 'fa-spinner text-amber-500',
        'resuelto' => 'fa-circle-check text-emerald-500',
        default    => 'fa-circle text-gray-400',
    };
}
?>

<style>
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

    <!-- ── HEADER ─────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-red-800 to-red-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <p class="text-red-300 text-sm font-semibold uppercase tracking-widest mb-2">Monitoreo</p>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                    Control de <span class="text-red-300">Problemas</span>
                </h2>
                <p class="text-red-100/80 text-lg font-light">Registro y seguimiento de incidencias en cultivos de la finca.</p>
            </div>
            <button onclick="openModal('modalControl')"
                class="group bg-white hover:bg-red-50 text-red-900 font-bold px-8 py-4 rounded-2xl shadow-lg transition-all hover:scale-105 flex items-center gap-3 whitespace-nowrap">
                <i class="fas fa-plus-circle text-red-600 group-hover:rotate-90 transition-transform duration-300 text-lg"></i>
                Nuevo Problema
            </button>
        </div>
    </div>

    <!-- ── ESTADÍSTICAS ───────────────────────────────────── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-gray-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-gray-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-lg uppercase">Total</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900"><?= $total ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Registros totales</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-circle-exclamation text-red-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">Abiertos</span>
            </div>
            <p class="text-3xl font-extrabold <?= $abiertos > 0 ? 'text-red-600' : 'text-gray-900' ?>"><?= $abiertos ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Sin resolver</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-spinner text-amber-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg uppercase">En proceso</span>
            </div>
            <p class="text-3xl font-extrabold text-amber-600"><?= $proceso ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">En atención</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-circle-check text-white text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-200 bg-white/10 px-2 py-1 rounded-lg uppercase">Resueltos</span>
            </div>
            <p class="text-3xl font-extrabold"><?= $resueltos ?></p>
            <p class="text-xs text-emerald-200 font-medium mt-1">Solucionados</p>
        </div>

    </div>

    <!-- ── TABLA ──────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-bug text-red-600"></i> Registro de Problemas
            </h3>
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input id="buscador" type="search" placeholder="Buscar problema..."
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 text-sm font-medium bg-white shadow-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[1000px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Problema</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Cultivo / Fase</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Reportado por</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Fechas</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="tablaBody">
                    <?php if (empty($registros)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-bug text-3xl opacity-30"></i>
                                    </div>
                                    <p class="font-semibold text-gray-500">Sin problemas registrados</p>
                                    <p class="text-sm">Registra el primer problema detectado en un cultivo</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registros as $r): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors fila-tabla"
                            data-buscar="<?= strtolower(htmlspecialchars($r['Tipocontrol'].' '.$r['CultivoNombre'].' '.$r['UsuarioNombre'])) ?>">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                                        <i class="fas <?= estadoIcono($r['Estado']) ?> text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900"><?= htmlspecialchars($r['Tipocontrol']) ?></p>
                                        <?php if (!empty($r['Valorregistrado'])): ?>
                                            <p class="text-xs text-gray-500 font-medium">Valor: <?= htmlspecialchars($r['Valorregistrado']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($r['Descripcion'])): ?>
                                            <p class="text-xs text-gray-400 mt-0.5 max-w-xs truncate"><?= htmlspecialchars($r['Descripcion']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($r['CultivoNombre'] ?? 'N/A') ?></p>
                                <p class="text-xs text-gray-500 mt-0.5"><i class="fas fa-layer-group mr-1"></i><?= htmlspecialchars($r['FaseNombre'] ?? 'N/A') ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-sm">
                                        <?= strtoupper(substr($r['UsuarioNombre'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($r['UsuarioNombre'] ?? 'N/A') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1 text-xs font-medium">
                                    <span class="text-gray-600"><i class="fas fa-flag text-red-400 mr-1"></i><?= htmlspecialchars($r['Fechareporte'] ?? '-') ?></span>
                                    <?php if (!empty($r['Fechasolucion'])): ?>
                                        <span class="text-emerald-600"><i class="fas fa-check mr-1"></i><?= htmlspecialchars($r['Fechasolucion']) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">Sin fecha solución</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase <?= estadoClase($r['Estado']) ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= estadoDot($r['Estado']) ?>"></span>
                                    <?= ucfirst($r['Estado']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='editarControl(<?= json_encode($r) ?>)'
                                        class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <button onclick="eliminarControl(<?= $r['IDcontroldecultivo'] ?>)"
                                        class="w-9 h-9 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Eliminar">
                                        <i class="fas fa-trash text-xs"></i>
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

<!-- ── MODAL CREAR / EDITAR ───────────────────────────────── -->
<div id="modalControl" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        <div class="bg-gradient-to-r from-red-600 to-red-800 p-7 flex justify-between items-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 id="modalTitle" class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bug text-xl"></i>
                </div>
                <span>Nuevo Problema</span>
            </h3>
            <button onclick="closeModal('modalControl')" class="relative z-10 w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="formControl" action="../../controllers/ControlCultivoController.php?accion=crear" method="POST" class="p-7 space-y-5">
            <input type="hidden" name="id_control" id="id_control">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Problema <span class="text-red-500">*</span></label>
                    <input type="text" name="tipo" id="tipo_control"
                           placeholder="Ej: Plaga, Enfermedad, Sequía" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Valor Registrado</label>
                    <input type="text" name="valor" id="valor_control"
                           placeholder="Ej: 35°C, 80% afectado"
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cultivo <span class="text-red-500">*</span></label>
                    <select name="idcultivo" id="idcultivo_control" required
                            class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                        <option value="">Seleccione cultivo...</option>
                        <?php foreach ($cultivos as $c): ?>
                            <option value="<?= $c['IDcultivo'] ?>"><?= htmlspecialchars($c['Nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fase <span class="text-red-500">*</span></label>
                    <select name="idfase" id="idfase_control" required
                            class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                        <option value="">Seleccione fase...</option>
                        <?php foreach ($fases as $f): ?>
                            <option value="<?= $f['IDfase'] ?>"><?= htmlspecialchars($f['Nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                    <select name="estado" id="estado_control"
                            class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                        <option value="abierto">Abierto</option>
                        <option value="proceso">En Proceso</option>
                        <option value="resuelto">Resuelto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Reporte <span class="text-red-500">*</span></label>
                    <input type="date" name="fechareporte" id="fechareporte_control" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Solución</label>
                    <input type="date" name="fechasolucion" id="fechasolucion_control"
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion_control" rows="3"
                          placeholder="Describe el problema detectado, síntomas y área afectada..."
                          class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 font-medium bg-gray-50/50 resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalControl')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-red-600 hover:bg-red-700 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-1">
                    Guardar Problema
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalControl' && !document.getElementById('id_control').value) {
        document.getElementById('modalTitle').querySelector('span').innerText = 'Nuevo Problema';
        document.getElementById('formControl').action = '../../controllers/ControlCultivoController.php?accion=crear';
        document.getElementById('formControl').reset();
        // Fecha actual automática
        document.getElementById('fechareporte_control').value = new Date().toISOString().split('T')[0];
    }
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById('id_control').value = '';
}
function editarControl(r) {
    document.getElementById('modalTitle').querySelector('span').innerText = 'Editar Problema';
    document.getElementById('formControl').action = '../../controllers/ControlCultivoController.php?accion=editar';
    document.getElementById('id_control').value          = r.IDcontroldecultivo;
    document.getElementById('tipo_control').value        = r.Tipocontrol;
    document.getElementById('valor_control').value       = r.Valorregistrado ?? '';
    document.getElementById('descripcion_control').value = r.Descripcion ?? '';
    document.getElementById('estado_control').value      = r.Estado;
    document.getElementById('fechareporte_control').value  = r.Fechareporte ?? '';
    document.getElementById('fechasolucion_control').value = r.Fechasolucion ?? '';
    document.getElementById('idcultivo_control').value   = r.IDcultivo;
    document.getElementById('idfase_control').value      = r.IDfase;
    openModal('modalControl');
}
function eliminarControl(id) {
    Swal.fire({
        title: '¿Eliminar este problema?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST';
            f.action = '../../controllers/ControlCultivoController.php?accion=eliminar';
            const i = document.createElement('input');
            i.type = 'hidden'; i.name = 'id'; i.value = id;
            f.appendChild(i); document.body.appendChild(f); f.submit();
        }
    });
}
// Búsqueda en tiempo real
document.getElementById('buscador').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.fila-tabla').forEach(fila => {
        fila.style.display = (!q || fila.dataset.buscar.includes(q)) ? '' : 'none';
    });
});
// Cerrar modal al hacer clic fuera
document.getElementById('modalControl').addEventListener('click', function(e) {
    if (e.target === this) closeModal('modalControl');
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
