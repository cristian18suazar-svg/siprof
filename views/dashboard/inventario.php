<?php
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: ../usuarios/login.php"); exit; }

$titulo = "Inventario - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Material.php';

$db  = getConnection();
$mat = new Material($db);
$materiales = $mat->obtenerTodos();

// Movimientos recientes
$movimientos = [];
try {
    $stmt = $db->query("SELECT m.*, mt.Nombre AS MaterialNombre, u.Nombre AS UsuarioNombre
                        FROM movimientoinventario m
                        LEFT JOIN materiales mt ON m.IDmateriales = mt.IDmateriales
                        LEFT JOIN usuario u ON m.IDusuario = u.IDusuario
                        ORDER BY m.Fecha DESC LIMIT 50");
    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $movimientos = []; }

// Métricas
$totalItems   = count($materiales);
$stockCritico = count(array_filter($materiales, fn($m) => $m['Cantidad'] <= $m['StockMinimo']));
$valorTotal   = array_sum(array_map(fn($m) => $m['Cantidad'] * $m['Precio'], $materiales));
$totalMovs    = count($movimientos);

// Filtro de búsqueda GET
$busqueda = trim($_GET['q'] ?? '');
$filtroTipo = trim($_GET['tipo'] ?? '');
if ($busqueda || $filtroTipo) {
    $materiales = array_filter($materiales, function($m) use ($busqueda, $filtroTipo) {
        $matchBusq = !$busqueda || stripos($m['Nombre'], $busqueda) !== false
                                || stripos($m['Tipo'], $busqueda) !== false;
        $matchTipo = !$filtroTipo || strtolower($m['Tipo']) === strtolower($filtroTipo);
        return $matchBusq && $matchTipo;
    });
}

// Tipos únicos para el filtro
$tipos = array_unique(array_column($mat->obtenerTodos(), 'Tipo'));
sort($tipos);
?>

<style>
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .tab-btn.active { border-bottom: 3px solid #16a34a; color: #16a34a; font-weight: 700; }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

<!-- HEADER -->
<div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <p class="text-emerald-300 text-sm font-semibold uppercase tracking-widest mb-2">Administración</p>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                Control de <span class="text-emerald-300">Inventario</span>
            </h2>
            <p class="text-emerald-100/80 text-lg font-light">Materiales, movimientos y stock de la finca.</p>
        </div>
        <button onclick="openModal('modalMovimiento')"
            class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-lg transition-all hover:scale-105 flex items-center gap-3 whitespace-nowrap">
            <i class="fas fa-plus-circle text-emerald-600 group-hover:rotate-90 transition-transform duration-300 text-lg"></i>
            Registrar Movimiento
        </button>
    </div>
</div>

<!-- STATS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-boxes-stacked text-emerald-600 text-lg"></i>
            </div>
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase">Total</span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900"><?= $totalItems ?></p>
        <p class="text-xs text-gray-500 font-medium mt-1">Materiales registrados</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-red-500 text-lg"></i>
            </div>
            <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg uppercase">Crítico</span>
        </div>
        <p class="text-3xl font-extrabold <?= $stockCritico > 0 ? 'text-red-600' : 'text-gray-900' ?>"><?= $stockCritico ?></p>
        <p class="text-xs text-gray-500 font-medium mt-1">Stock bajo mínimo</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock-rotate-left text-blue-600 text-lg"></i>
            </div>
            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase">Movs.</span>
        </div>
        <p class="text-3xl font-extrabold text-gray-900"><?= $totalMovs ?></p>
        <p class="text-xs text-gray-500 font-medium mt-1">Movimientos recientes</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-5 shadow-sm text-white">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-wallet text-white text-lg"></i>
            </div>
            <span class="text-[10px] font-bold text-emerald-200 bg-white/10 px-2 py-1 rounded-lg uppercase">Valor</span>
        </div>
        <p class="text-2xl font-extrabold">$ <?= number_format($valorTotal, 0, ',', '.') ?></p>
        <p class="text-xs text-emerald-200 font-medium mt-1">Valor total en stock</p>
    </div>
</div>

<!-- TABS -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="border-b border-gray-100 flex items-center justify-between px-6 pt-4 gap-4 flex-wrap">
        <div class="flex gap-1" id="tabsInventario">
            <button onclick="cambiarTab('materiales')" class="tab-btn active px-4 py-3 text-sm font-medium text-gray-500 hover:text-emerald-600 transition-colors" data-tab="materiales">
                <i class="fas fa-boxes-stacked mr-1"></i> Materiales
            </button>
            <button onclick="cambiarTab('movimientos')" class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-emerald-600 transition-colors" data-tab="movimientos">
                <i class="fas fa-clock-rotate-left mr-1"></i> Movimientos
            </button>
        </div>
        <!-- Buscador + filtro tipo -->
        <form method="GET" class="flex items-center gap-2 pb-3" id="formFiltro">
            <input type="hidden" name="tab" id="tabHidden" value="materiales">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="search" name="q" value="<?= htmlspecialchars($busqueda) ?>"
                       placeholder="Buscar material..."
                       class="pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 w-52">
            </div>
            <select name="tipo" class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                <option value="">Todos los tipos</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>" <?= $filtroTipo === $t ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-colors">
                Filtrar
            </button>
            <?php if ($busqueda || $filtroTipo): ?>
            <a href="inventario.php" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-bold transition-colors">
                Limpiar
            </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- TAB: MATERIALES -->
    <div id="tab-materiales" class="overflow-x-auto">
        <table class="w-full text-left min-w-[900px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Material</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tipo</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Stock</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Precio Unit.</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Valor Total</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($materiales)): ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">
                    <i class="fas fa-boxes-stacked text-3xl mb-3 block opacity-30"></i>
                    <?= $busqueda || $filtroTipo ? 'Sin resultados para los filtros aplicados.' : 'No hay materiales registrados.' ?>
                </td></tr>
                <?php else: ?>
                <?php foreach ($materiales as $m):
                    $critico = $m['Cantidad'] <= $m['StockMinimo'];
                    $pct = $m['StockMinimo'] > 0 ? min(100, round(($m['Cantidad'] / ($m['StockMinimo'] * 2)) * 100)) : 100;
                    $barColor = $critico ? 'bg-red-500' : ($pct < 60 ? 'bg-yellow-400' : 'bg-emerald-500');
                    $valorMat = $m['Cantidad'] * $m['Precio'];
                ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i class="fas fa-box text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900"><?= htmlspecialchars($m['Nombre']) ?></p>
                                <?php if (!empty($m['Descripcion'])): ?>
                                    <p class="text-xs text-gray-400 truncate max-w-xs"><?= htmlspecialchars($m['Descripcion']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-xl text-xs font-bold bg-gray-100 text-gray-700"><?= htmlspecialchars($m['Tipo']) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1 min-w-[120px]">
                            <span class="text-sm font-bold <?= $critico ? 'text-red-600' : 'text-gray-800' ?>">
                                <?= $m['Cantidad'] ?> / <?= $m['StockMinimo'] ?> <?= htmlspecialchars($m['Unidad']) ?>
                            </span>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="<?= $barColor ?> h-1.5 rounded-full" style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">$ <?= number_format($m['Precio'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4 font-bold text-emerald-700">$ <?= number_format($valorMat, 0, ',', '.') ?></td>
                    <td class="px-6 py-4">
                        <?php if ($critico): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-700 ring-1 ring-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Crítico
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Normal
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="registrarMovimiento(<?= $m['IDmateriales'] ?>, '<?= htmlspecialchars($m['Nombre'], ENT_QUOTES) ?>', <?= $m['Cantidad'] ?>)"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white text-xs font-bold transition-all">
                            <i class="fas fa-arrows-up-down text-xs"></i> Movimiento
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- TAB: MOVIMIENTOS -->
    <div id="tab-movimientos" class="overflow-x-auto hidden">
        <table class="w-full text-left min-w-[800px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Material</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tipo</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Cantidad</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Motivo</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Registrado por</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($movimientos)): ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                    <i class="fas fa-clock-rotate-left text-3xl mb-3 block opacity-30"></i>
                    Sin movimientos registrados.
                </td></tr>
                <?php else: ?>
                <?php foreach ($movimientos as $mv):
                    $esEntrada = strtolower($mv['Tipomovimiento']) === 'entrada';
                ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-800"><?= htmlspecialchars($mv['MaterialNombre'] ?? 'N/A') ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold
                            <?= $esEntrada ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
                            <i class="fas <?= $esEntrada ? 'fa-arrow-down' : 'fa-arrow-up' ?> text-xs"></i>
                            <?= htmlspecialchars($mv['Tipomovimiento']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold <?= $esEntrada ? 'text-emerald-700' : 'text-red-600' ?>">
                        <?= $esEntrada ? '+' : '-' ?><?= $mv['Cantidad'] ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($mv['Motivo'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($mv['UsuarioNombre'] ?? 'N/A') ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($mv['Fecha']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- MODAL MOVIMIENTO -->
<div id="modalMovimiento" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-7 flex justify-between items-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 class="text-xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrows-up-down text-lg"></i>
                </div>
                Registrar Movimiento
            </h3>
            <button onclick="closeModal('modalMovimiento')" class="relative z-10 w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/InventarioController.php?accion=movimiento" method="POST" class="p-7 space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Material <span class="text-red-500">*</span></label>
                <select name="id_material" id="sel_material" required
                        class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                    <option value="">Seleccione material...</option>
                    <?php foreach ($mat->obtenerTodos() as $m): ?>
                        <option value="<?= $m['IDmateriales'] ?>"><?= htmlspecialchars($m['Nombre']) ?> (Stock: <?= $m['Cantidad'] ?> <?= $m['Unidad'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" required class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                        <option value="Entrada">Entrada (suma stock)</option>
                        <option value="Salida">Salida (resta stock)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cantidad <span class="text-red-500">*</span></label>
                    <input type="number" name="cantidad" min="1" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Fecha <span class="text-red-500">*</span></label>
                <input type="date" name="fecha" id="fecha_movimiento" required
                       class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Motivo</label>
                <input type="text" name="motivo" placeholder="Ej: Compra, Uso en campo, Ajuste..."
                       class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
            </div>
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalMovimiento')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Tabs
function cambiarTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.tab-btn[data-tab="${tab}"]`).classList.add('active');
    document.getElementById('tab-materiales').classList.toggle('hidden', tab !== 'materiales');
    document.getElementById('tab-movimientos').classList.toggle('hidden', tab !== 'movimientos');
    document.getElementById('tabHidden').value = tab;
}

// Modal
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalMovimiento') {
        document.getElementById('fecha_movimiento').value = new Date().toISOString().split('T')[0];
    }
}
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// Preseleccionar material desde botón de la tabla
function registrarMovimiento(idMaterial, nombre, stock) {
    document.getElementById('sel_material').value = idMaterial;
    openModal('modalMovimiento');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalMovimiento').addEventListener('click', function(e) {
    if (e.target === this) closeModal('modalMovimiento');
});

// Restaurar tab activo si viene de filtro GET
const urlParams = new URLSearchParams(window.location.search);
const tabParam = urlParams.get('tab');
if (tabParam === 'movimientos') cambiarTab('movimientos');
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
