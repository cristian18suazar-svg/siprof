<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

// Solo admin puede ver reportes
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? $_SESSION['usuario']['Niveldeacceso'] ?? ''));
if (!in_array($rol, ['administrador', 'admin'])) {
    header("Location: admin.php");
    exit;
}

$titulo = "Reportes - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Produccion.php';
require_once __DIR__ . '/../../models/Pago.php';
require_once __DIR__ . '/../../models/Labor.php';
require_once __DIR__ . '/../../models/Material.php';
require_once __DIR__ . '/../../models/Lote.php';
require_once __DIR__ . '/../../models/Usuario.php';

$db = getConnection();

$produccionModel = new Produccion($db);
$pagoModel       = new Pago($db);
$laborModel      = new Labor($db);
$materialModel   = new Material($db);
$loteModel       = new Lote($db);
$usuarioModel    = new Usuario($db);

$producciones = $produccionModel->obtenerTodos();
$pagos        = $pagoModel->obtenerTodos();
$labores      = $laborModel->obtenerTodos();
$materiales   = $materialModel->obtenerTodos();
$lotes        = $loteModel->obtenerTodos();
$usuarios     = $usuarioModel->obtenerTodos();

// ── Cálculos de resumen ──────────────────────────────────────
$totalProduccion   = array_sum(array_column($producciones, 'Cantidad'));
$totalPagos        = array_sum(array_column($pagos, 'Monto'));
$totalLabores      = count($labores);
$laboresCompletadas = count(array_filter($labores, fn($l) => strtolower($l['Estado'] ?? '') === 'completada'));
$pctLabores        = $totalLabores > 0 ? round(($laboresCompletadas / $totalLabores) * 100) : 0;
$valorInventario   = array_sum(array_map(fn($m) => $m['Cantidad'] * $m['Precio'], $materiales));
$stockCritico      = count(array_filter($materiales, fn($m) => $m['Cantidad'] <= $m['StockMinimo']));
$totalLotes        = count($lotes);
$lotesActivos      = count(array_filter($lotes, fn($l) => $l['Estado'] === 'Activo'));
$totalUsuarios     = count($usuarios);

// Pagos por tipo
$pagosPorTipo = [];
foreach ($pagos as $p) {
    $tipo = $p['Tipopago'] ?? 'Otro';
    $pagosPorTipo[$tipo] = ($pagosPorTipo[$tipo] ?? 0) + $p['Monto'];
}
arsort($pagosPorTipo);

// Producción por cultivo
$prodPorCultivo = [];
foreach ($producciones as $p) {
    $nombre = $p['CultivoNombre'] ?? 'Sin cultivo';
    $prodPorCultivo[$nombre] = ($prodPorCultivo[$nombre] ?? 0) + $p['Cantidad'];
}
arsort($prodPorCultivo);
?>

<style>
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .progress-bar { transition: width 0.8s ease; }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

    <!-- ── HEADER ─────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <p class="text-emerald-300 text-sm font-semibold uppercase tracking-widest mb-2">Administración</p>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                    Reportes del <span class="text-emerald-300">Sistema</span>
                </h2>
                <p class="text-emerald-100/80 text-lg font-light">Resumen general de producción, pagos, labores e inventario.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-sm font-medium">
                    <i class="fas fa-calendar-day text-emerald-300"></i>
                    <?= date('d/m/Y') ?>
                </span>
                <button onclick="window.print()"
                    class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-6 py-3 rounded-2xl shadow-lg transition-all hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-print text-emerald-600 text-sm"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <!-- ── TARJETAS DE RESUMEN ────────────────────────────── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-seedling text-emerald-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-wide">Producción</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900"><?= number_format($totalProduccion, 0) ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Unidades producidas</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wide">Pagos</span>
            </div>
            <p class="text-2xl font-extrabold text-gray-900">$ <?= number_format($totalPagos, 0, ',', '.') ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Total pagado a trabajadores</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list-check text-orange-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg uppercase tracking-wide">Labores</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900"><?= $pctLabores ?>%</p>
            <p class="text-xs text-gray-500 font-medium mt-1"><?= $laboresCompletadas ?> / <?= $totalLabores ?> completadas</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-warehouse text-white text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-200 bg-white/10 px-2 py-1 rounded-lg uppercase tracking-wide">Inventario</span>
            </div>
            <p class="text-2xl font-extrabold">$ <?= number_format($valorInventario, 0, ',', '.') ?></p>
            <p class="text-xs text-emerald-200 font-medium mt-1">Valor total en stock</p>
        </div>

    </div>

    <!-- ── FILA 2: PRODUCCIÓN + PAGOS ────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Producción por cultivo -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-emerald-600"></i> Producción por Cultivo
                </h3>
                <span class="text-xs text-gray-400 font-medium"><?= count($producciones) ?> registros</span>
            </div>
            <?php if (empty($prodPorCultivo)): ?>
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <i class="fas fa-seedling text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Sin registros de producción</p>
                </div>
            <?php else: ?>
                <?php
                $maxProd = max($prodPorCultivo);
                $i = 0;
                $colores = ['bg-emerald-500','bg-blue-500','bg-orange-500','bg-purple-500','bg-yellow-500'];
                foreach ($prodPorCultivo as $cultivo => $cantidad):
                    $pct = $maxProd > 0 ? round(($cantidad / $maxProd) * 100) : 0;
                    $color = $colores[$i % count($colores)];
                    $i++;
                ?>
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-semibold text-gray-700 truncate max-w-[60%]"><?= htmlspecialchars($cultivo) ?></span>
                        <span class="text-sm font-bold text-gray-900"><?= number_format($cantidad, 0) ?> uds.</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="progress-bar <?= $color ?> h-2.5 rounded-full" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagos por tipo -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-wallet text-blue-600"></i> Pagos por Tipo
                </h3>
                <span class="text-xs text-gray-400 font-medium"><?= count($pagos) ?> pagos</span>
            </div>
            <?php if (empty($pagosPorTipo)): ?>
                <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                    <i class="fas fa-dollar-sign text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Sin registros de pagos</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php
                    $maxPago = max($pagosPorTipo);
                    $coloresPago = ['bg-blue-500','bg-emerald-500','bg-purple-500','bg-orange-500'];
                    $j = 0;
                    foreach ($pagosPorTipo as $tipo => $monto):
                        $pct = $maxPago > 0 ? round(($monto / $maxPago) * 100) : 0;
                        $color = $coloresPago[$j % count($coloresPago)];
                        $j++;
                    ?>
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full <?= $color ?> flex-shrink-0"></div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($tipo) ?></span>
                                <span class="text-sm font-bold text-gray-900">$ <?= number_format($monto, 0, ',', '.') ?></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="progress-bar <?= $color ?> h-2 rounded-full" style="width: <?= $pct ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- ── FILA 3: INVENTARIO + LOTES + USUARIOS ─────────── -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Inventario crítico -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-boxes-stacked text-emerald-600"></i> Inventario
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-sm text-gray-600 font-medium">Total materiales</span>
                    <span class="font-bold text-gray-900"><?= count($materiales) ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                    <span class="text-sm text-green-700 font-medium">Stock suficiente</span>
                    <span class="font-bold text-green-700"><?= count($materiales) - $stockCritico ?></span>
                </div>
                <div class="flex justify-between items-center p-3 <?= $stockCritico > 0 ? 'bg-red-50' : 'bg-gray-50' ?> rounded-xl">
                    <span class="text-sm <?= $stockCritico > 0 ? 'text-red-600' : 'text-gray-600' ?> font-medium flex items-center gap-1">
                        <?php if ($stockCritico > 0): ?><i class="fas fa-triangle-exclamation text-xs"></i><?php endif; ?>
                        Stock crítico
                    </span>
                    <span class="font-bold <?= $stockCritico > 0 ? 'text-red-600' : 'text-gray-900' ?>"><?= $stockCritico ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-xl">
                    <span class="text-sm text-emerald-700 font-medium">Valor total</span>
                    <span class="font-bold text-emerald-700 text-sm">$ <?= number_format($valorInventario, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Lotes -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-map text-blue-600"></i> Lotes
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-sm text-gray-600 font-medium">Total lotes</span>
                    <span class="font-bold text-gray-900"><?= $totalLotes ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                    <span class="text-sm text-green-700 font-medium">Activos</span>
                    <span class="font-bold text-green-700"><?= $lotesActivos ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-sm text-gray-600 font-medium">Inactivos</span>
                    <span class="font-bold text-gray-900"><?= $totalLotes - $lotesActivos ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
                    <span class="text-sm text-blue-700 font-medium">Área total</span>
                    <span class="font-bold text-blue-700"><?= number_format(array_sum(array_column($lotes, 'Area')), 1) ?> ha</span>
                </div>
            </div>
        </div>

        <!-- Usuarios y labores -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-users text-purple-600"></i> Personal
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-sm text-gray-600 font-medium">Total usuarios</span>
                    <span class="font-bold text-gray-900"><?= $totalUsuarios ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl">
                    <span class="text-sm text-purple-700 font-medium">Labores asignadas</span>
                    <span class="font-bold text-purple-700"><?= $totalLabores ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                    <span class="text-sm text-green-700 font-medium">Completadas</span>
                    <span class="font-bold text-green-700"><?= $laboresCompletadas ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-xl">
                    <span class="text-sm text-orange-700 font-medium">Pendientes</span>
                    <span class="font-bold text-orange-700"><?= $totalLabores - $laboresCompletadas ?></span>
                </div>
            </div>
        </div>

    </div>

    <!-- ── TABLA ÚLTIMAS PRODUCCIONES ────────────────────── -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-emerald-600"></i> Últimos Registros de Producción
            </h3>
            <a href="produccion.php" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-1">
                Ver todos <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Cultivo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Cantidad</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Unidad</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Calidad</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($producciones)): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">
                            <i class="fas fa-info-circle mr-2"></i> Sin registros de producción.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($producciones, 0, 8) as $p): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <i class="fas fa-leaf text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-gray-800"><?= htmlspecialchars($p['CultivoNombre'] ?? 'ID: '.$p['IDcultivo']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?= number_format($p['Cantidad'], 2) ?></td>
                            <td class="px-6 py-4 text-gray-600 font-medium"><?= htmlspecialchars($p['Unidad'] ?? '-') ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-gray-100 text-gray-700">
                                    <?= htmlspecialchars($p['Calidad'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium"><?= htmlspecialchars($p['Fecha'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── TABLA ÚLTIMOS PAGOS ────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-blue-600"></i> Últimos Pagos Registrados
            </h3>
            <a href="pagos.php" class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                Ver todos <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Trabajador</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Monto</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($pagos)): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">
                            <i class="fas fa-info-circle mr-2"></i> Sin registros de pagos.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($pagos, 0, 8) as $p): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                        <?= strtoupper(substr($p['TrabajadorNombre'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <span class="font-semibold text-gray-800"><?= htmlspecialchars($p['TrabajadorNombre'] ?? 'ID: '.$p['IDtrabajador']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">$ <?= number_format($p['Monto'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-700">
                                    <?= htmlspecialchars($p['Tipopago'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php $ep = strtolower($p['Estado'] ?? ''); ?>
                                <span class="px-3 py-1 rounded-xl text-xs font-bold
                                    <?= $ep === 'pagado' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>">
                                    <?= htmlspecialchars($p['Estado'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium"><?= htmlspecialchars($p['Fechapago'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
