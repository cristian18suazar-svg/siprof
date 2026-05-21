<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Gestión de Pagos - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Pago.php';
require_once __DIR__ . '/../../models/Usuario.php';

$db = getConnection();
$pagoModel = new Pago($db);
$usuarioModel = new Usuario($db);

$pagos = $pagoModel->obtenerTodos();
$usuarios = $usuarioModel->obtenerTodos();

$totalPagado = array_sum(array_column($pagos, 'Monto'));
$pagosPendientes = count(array_filter($pagos, function($p) { return $p['Estado'] == 'Pendiente'; }));
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
                    Gestión de <span class="text-emerald-300">Pagos</span>
                </h2>
                <p class="text-emerald-50 text-lg md:text-xl font-light">Control de salarios, bonos y pagos a trabajadores de la finca.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="openModal('modalPago')"
                    class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 hover:scale-105 flex items-center justify-center gap-3 whitespace-nowrap">
                    <i class="fas fa-hand-holding-usd text-emerald-600 group-hover:rotate-12 transition-transform text-lg"></i>
                    Nuevo Pago
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Pagado Histórico</p>
                    <p class="text-3xl font-extrabold text-gray-900">$ <?php echo number_format($totalPagado, 2); ?></p>
                </div>
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-dollar-sign text-2xl text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Pagos Pendientes</p>
                    <p class="text-3xl font-extrabold text-amber-600"><?php echo $pagosPendientes; ?></p>
                </div>
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-hourglass-half text-2xl text-amber-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Trabajadores</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?php echo count($usuarios); ?></p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-file-invoice-dollar text-emerald-600"></i> Registro de Pagos
            </h3>
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="search" placeholder="Buscar trabajador..." class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-white shadow-sm">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Trabajador</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Tipo & Monto</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($pagos)): ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500 font-medium">
                                <i class="fas fa-info-circle mr-2"></i> No hay pagos registrados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pagos as $p): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                            <i class="fas fa-user text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($p['TrabajadorNombre'] ?? 'Usuario Desconocido'); ?></p>
                                            <p class="text-sm text-gray-500 font-medium">ID Pago: <?php echo $p['IDpago']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-bold text-lg">$ <?php echo number_format($p['Monto'], 2); ?></span>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider"><?php echo htmlspecialchars($p['Tipopago']); ?></span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <span class="text-gray-700 font-medium"><i class="fas fa-calendar-alt text-gray-400 mr-2"></i><?php echo $p['Fechapago']; ?></span>
                                </td>
                                <td class="p-6">
                                    <?php 
                                    $est = strtolower($p['Estado']);
                                    $class = "bg-gray-100 text-gray-700";
                                    if ($est == 'pendiente') $class = "bg-amber-50 text-amber-700 ring-1 ring-amber-200";
                                    if ($est == 'pagado') $class = "bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200";
                                    ?>
                                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider <?php echo $class; ?>">
                                        <?php echo $p['Estado']; ?>
                                    </span>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($p['Estado'] != 'Pagado'): ?>
                                            <a href="../../controllers/PagoController.php?accion=aprobar&id=<?php echo $p['IDpago']; ?>" class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Aprobar Pago">
                                                <i class="fas fa-check-double"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button onclick='editarPago(<?php echo json_encode($p); ?>)' class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button onclick="confirmarEliminarPago(<?php echo $p['IDpago']; ?>)" class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
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

<!-- Modal Pago (Crear/Editar) -->
<div id="modalPago" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-8 flex justify-between items-center text-white relative">
            <h3 id="modalTitlePago" class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <i class="fas fa-money-bill-wave"></i>
                <span>Registrar Pago</span>
            </h3>
            <button onclick="closeModal('modalPago')" class="text-emerald-100 hover:text-white transition-colors relative z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="formPago" action="../../controllers/PagoController.php?accion=crear" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id_pago" id="id_pago">

            <!-- Trabajador + Fecha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Trabajador <span class="text-red-500">*</span></label>
                    <select name="id_trabajador" id="id_trabajador_pago" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                        <option value="">Seleccione trabajador...</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?php echo $u['IDusuario']; ?>"><?php echo htmlspecialchars($u['Nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" id="fecha_pago" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>

            <!-- Tipo de pago -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Pago</label>
                <input type="hidden" name="tipo" id="tipo_pago" value="Jornal">
                <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-sun text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-800 text-sm">Jornal</p>
                        <p class="text-xs text-emerald-600">Pago por días trabajados</p>
                    </div>
                </div>
            </div>

            <!-- Calculadora jornal -->
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-4">
                <p class="text-sm font-bold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-calculator text-emerald-600"></i> Calculadora de Jornal
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Salario por día (COP)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-bold text-sm">$</span>
                            <input type="number" id="salario_dia" placeholder="0" min="0"
                                   class="w-full pl-7 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-medium bg-white"
                                   oninput="calcularJornal()">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Días trabajados</label>
                        <input type="number" id="dias_trabajados" placeholder="0" min="1"
                               class="w-full px-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-medium bg-white"
                               oninput="calcularJornal()">
                    </div>
                    <div class="bg-white rounded-xl border border-emerald-300 p-3 text-center">
                        <p class="text-xs text-gray-500 font-medium">Total calculado</p>
                        <p id="resultado_jornal" class="text-xl font-extrabold text-emerald-700">$ 0</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i>
                    El monto se calcula automáticamente. Puedes ajustarlo manualmente si es necesario.
                </p>
            </div>

            <!-- Monto total (se llena automáticamente con la calculadora) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Monto Total (COP) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">$</span>
                    <input type="number" step="0.01" min="0"
                           name="monto" id="monto_pago" required
                           placeholder="Se calcula automáticamente"
                           class="w-full pl-8 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-lg bg-gray-50/50">
                </div>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                <select name="estado" id="estado_pago" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalPago')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Calculadora de jornal ────────────────────────────────────
function calcularJornal() {
    const salarioDia = parseFloat(document.getElementById('salario_dia').value) || 0;
    const dias       = parseFloat(document.getElementById('dias_trabajados').value) || 0;
    const total      = salarioDia * dias;
    document.getElementById('resultado_jornal').textContent = '$ ' + total.toLocaleString('es-CO');
    document.getElementById('monto_pago').value = total > 0 ? total.toFixed(2) : '';
}

// ── Modal ────────────────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalPago' && !document.getElementById('id_pago').value) {
        document.getElementById('modalTitlePago').querySelector('span').innerText = 'Registrar Pago';
        document.getElementById('formPago').action = '../../controllers/PagoController.php?accion=crear';
        document.getElementById('formPago').reset();
        document.getElementById('fecha_pago').value = new Date().toISOString().split('T')[0];
        document.getElementById('tipo_pago').value  = 'Jornal';
        document.getElementById('resultado_jornal').textContent = '$ 0';
    }
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editarPago(p) {
    document.getElementById('modalTitlePago').querySelector('span').innerText = 'Editar Pago';
    document.getElementById('formPago').action = '../../controllers/PagoController.php?accion=editar';
    document.getElementById('id_pago').value            = p.IDpago;
    document.getElementById('id_trabajador_pago').value = p.IDtrabajador;
    document.getElementById('fecha_pago').value         = p.Fechapago;
    document.getElementById('monto_pago').value         = p.Monto;
    document.getElementById('tipo_pago').value          = 'Jornal';
    document.getElementById('estado_pago').value        = p.Estado;
    document.getElementById('resultado_jornal').textContent = '$ ' + parseFloat(p.Monto).toLocaleString('es-CO');
    openModal('modalPago');
}

function confirmarEliminarPago(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará el registro de pago permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../controllers/PagoController.php?accion=eliminar';
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
