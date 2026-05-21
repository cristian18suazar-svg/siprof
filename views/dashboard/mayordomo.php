<?php
require_once __DIR__ . '/../../controllers/MayordomoController.php';

$titulo = "Dashboard Mayordomo - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="relative z-10">
            <p class="text-blue-300 text-sm font-semibold uppercase tracking-widest mb-2">Panel de Mayordomo</p>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                Bienvenido, <span class="text-blue-300"><?= htmlspecialchars(explode(' ', $_SESSION['usuario']['nombre'])[0]) ?></span>
            </h2>
            <p class="text-blue-100/80 text-lg font-light">Gestiona las operaciones diarias de la finca desde aquí.</p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg uppercase">Pendientes</span>
            </div>
            <p class="text-3xl font-extrabold <?= $metricas['pendientes'] > 0 ? 'text-amber-600' : 'text-gray-900' ?>"><?= $metricas['pendientes'] ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Labores sin asignar</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-spinner text-blue-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase">En proceso</span>
            </div>
            <p class="text-3xl font-extrabold text-blue-600"><?= $metricas['enProceso'] ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Labores activas</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-<?= $metricas['stockCritico'] > 0 ? 'red' : 'emerald' ?>-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes-stacked text-<?= $metricas['stockCritico'] > 0 ? 'red' : 'emerald' ?>-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-<?= $metricas['stockCritico'] > 0 ? 'red' : 'emerald' ?>-600 bg-<?= $metricas['stockCritico'] > 0 ? 'red' : 'emerald' ?>-50 px-2 py-1 rounded-lg uppercase">Stock</span>
            </div>
            <p class="text-3xl font-extrabold <?= $metricas['stockCritico'] > 0 ? 'text-red-600' : 'text-gray-900' ?>"><?= $metricas['stockCritico'] ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Materiales críticos</p>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-200 bg-white/10 px-2 py-1 rounded-lg uppercase">Personal</span>
            </div>
            <p class="text-3xl font-extrabold"><?= $metricas['trabajadores'] ?></p>
            <p class="text-xs text-blue-200 font-medium mt-1">Trabajadores activos</p>
        </div>

    </div>

    <!-- Herramientas Administrativas -->
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Asignación Rápida -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-plus-circle text-blue-600"></i> Asignación Rápida de Labor
            </h3>
            <form action="../../controllers/MayordomoController.php?accion=asignar_labor" method="POST" class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tarea / Actividad</label>
                        <input type="text" name="tarea" required placeholder="Ej: Riego de surcos" 
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Trabajador Responsable</label>
                        <select name="id_trabajador" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($trabajadoresList as $t): ?>
                                <option value="<?= $t['IDusuario'] ?>"><?= htmlspecialchars($t['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lote</label>
                        <select name="id_lote" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <?php foreach ($cultivos as $c): ?>
                                <option value="<?= $c['IDlote'] ?>"><?= htmlspecialchars($c['Nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end h-full">
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                            Asignar Tarea Ahora
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Ajuste de Stock -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-boxes-stacked text-orange-600"></i> Ajuste de Inventario
            </h3>
            <form action="../../controllers/MayordomoController.php?accion=ajustar_stock" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Material</label>
                    <select name="id_material" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                        <?php foreach ($materiales as $m): ?>
                            <option value="<?= $m['IDmateriales'] ?>"><?= htmlspecialchars($m['Nombre']) ?> (<?= $m['Cantidad'] ?> <?= $m['Unidad'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cantidad</label>
                        <input type="number" name="cantidad" required min="1" placeholder="0" 
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Acción</label>
                        <select name="tipo" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            <option value="restar">Salida (-)</option>
                            <option value="sumar">Entrada (+)</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/30">
                    Registrar Movimiento
                </button>
            </form>
        </div>
    </div>

    <!-- Últimas labores -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-list-check text-blue-600"></i> Últimas Labores
            </h3>
            <a href="labores.php" class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                Ver todas <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tarea</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Lote</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Inicio</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($labores)): ?>
                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400 font-medium">
                            Sin labores registradas.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($labores, 0, 6) as $l):
                            $est = strtolower($l['Estado'] ?? '');
                            $badge = match($est) {
                                'pendiente' => 'bg-amber-50 text-amber-700',
                                'proceso'   => 'bg-blue-50 text-blue-700',
                                'cancelada' => 'bg-red-50 text-red-700',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($l['Tarea']) ?></p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs"><?= htmlspecialchars($l['Descripcionlabor'] ?? '') ?></p>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-600"><?= htmlspecialchars($l['LoteNombre'] ?? 'N/A') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($l['Fechainicio'] ?? '-') ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase <?= $badge ?>">
                                    <?= ucfirst($l['Estado'] ?? '-') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Materiales críticos -->
    <?php if ($metricas['stockCritico'] > 0): ?>
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-red-100 bg-red-50/50 flex items-center gap-3">
            <i class="fas fa-triangle-exclamation text-red-500 text-xl"></i>
            <h3 class="text-lg font-bold text-red-800">Materiales con Stock Crítico</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach (array_filter($materiales, fn($m) => $m['Cantidad'] <= $m['StockMinimo']) as $m): ?>
            <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box text-red-500 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-gray-800 text-sm truncate"><?= htmlspecialchars($m['Nombre']) ?></p>
                    <p class="text-xs text-red-600 font-medium"><?= $m['Cantidad'] ?> / <?= $m['StockMinimo'] ?> <?= htmlspecialchars($m['Unidad']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
