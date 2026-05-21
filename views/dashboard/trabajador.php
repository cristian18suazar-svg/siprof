<?php
require_once __DIR__ . '/../../controllers/TrabajadorController.php';

$titulo = "Mi Panel - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

    <!-- Header personalizado -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <p class="text-emerald-300 text-sm font-semibold uppercase tracking-widest mb-2">Panel del Trabajador</p>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                    Hola, <span class="text-emerald-300"><?= htmlspecialchars(explode(' ', $_SESSION['usuario']['nombre'])[0]) ?></span>
                </h2>
                <p class="text-emerald-100/80 text-lg font-light">Aquí puedes ver tus labores asignadas y el estado de tus pagos.</p>
            </div>
            <div class="flex items-center gap-3 bg-white/10 border border-white/20 rounded-2xl px-5 py-3">
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center font-bold text-xl">
                    <?= strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) ?>
                </div>
                <div>
                    <p class="font-bold text-white text-sm"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
                    <p class="text-emerald-300 text-xs font-medium uppercase tracking-wide">Trabajador</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas personales -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list-check text-emerald-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase">Total</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900"><?= $metricas['totales'] ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Labores asignadas</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg uppercase">Pendientes</span>
            </div>
            <p class="text-3xl font-extrabold <?= $metricas['pendientes'] > 0 ? 'text-amber-600' : 'text-gray-900' ?>"><?= $metricas['pendientes'] ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Por realizar</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-spinner text-blue-600 text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase">En proceso</span>
            </div>
            <p class="text-3xl font-extrabold text-blue-600"><?= $metricas['enProceso'] ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">En ejecución</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-lg"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-200 bg-white/10 px-2 py-1 rounded-lg uppercase">Ganado</span>
            </div>
            <p class="text-2xl font-extrabold">$ <?= number_format($metricas['ganadoTotal'], 0, ',', '.') ?></p>
            <p class="text-xs text-emerald-200 font-medium mt-1">Total recibido</p>
        </div>

    </div>

    <!-- Mis labores -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-list-check text-emerald-600"></i> Mis Labores Asignadas
            </h3>
            <?php if ($metricas['pendientes'] > 0): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-xl text-xs font-bold ring-1 ring-amber-200">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <?= $metricas['pendientes'] ?> pendiente<?= $metricas['pendientes'] > 1 ? 's' : '' ?>
            </span>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tarea</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Lote</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha inicio</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($misLabores)): ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-list-check text-2xl opacity-30"></i>
                                </div>
                                <p class="font-semibold text-gray-500">No tienes labores asignadas</p>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($misLabores as $l):
                            $est = strtolower($l['Estado'] ?? '');
                            $badge = match($est) {
                                'pendiente' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                'proceso'   => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                                'cancelada' => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                                'completada'=> 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                            $dot = match($est) {
                                'pendiente' => 'bg-amber-500 animate-pulse',
                                'proceso'   => 'bg-blue-500 animate-pulse',
                                'cancelada' => 'bg-red-500',
                                'completada'=> 'bg-emerald-500',
                                default     => 'bg-gray-400',
                            };
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($l['Tarea']) ?></p>
                                <?php if (!empty($l['Descripcionlabor'])): ?>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs"><?= htmlspecialchars($l['Descripcionlabor']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-600"><?= htmlspecialchars($l['LoteNombre'] ?? 'N/A') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($l['Fechainicio'] ?? '-') ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase <?= $badge ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $dot ?>"></span>
                                    <?= ucfirst($l['Estado'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($est === 'pendiente'): ?>
                                    <form action="../../controllers/TrabajadorController.php?accion=actualizar_estado" method="POST" class="inline">
                                        <input type="hidden" name="id_labor" value="<?= $l['IDasignaciondelabor'] ?>">
                                        <input type="hidden" name="estado" value="proceso">
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors">
                                            Iniciar
                                        </button>
                                    </form>
                                <?php elseif ($est === 'proceso'): ?>
                                    <form action="../../controllers/TrabajadorController.php?accion=actualizar_estado" method="POST" class="inline">
                                        <input type="hidden" name="id_labor" value="<?= $l['IDasignaciondelabor'] ?>">
                                        <input type="hidden" name="estado" value="completada">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-colors">
                                            Finalizar
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">Sin acciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mis pagos -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-emerald-600"></i> Mis Pagos
            </h3>
            <?php if ($metricas['porCobrar'] > 0): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-xl text-xs font-bold ring-1 ring-amber-200">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <?= $metricas['porCobrar'] ?> por cobrar
            </span>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[500px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Monto</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Fecha</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($misPagos)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-2xl opacity-30"></i>
                                </div>
                                <p class="font-semibold text-gray-500">Sin registros de pago</p>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($misPagos as $p):
                            $ep = strtolower($p['Estado'] ?? '');
                            $badgePago = match($ep) {
                                'pagado'    => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
                                'pendiente' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-700">
                                    <?= htmlspecialchars($p['Tipopago'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">$ <?= number_format($p['Monto'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($p['Fechapago'] ?? '-') ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 rounded-xl text-xs font-bold uppercase <?= $badgePago ?>">
                                    <?= ucfirst($p['Estado'] ?? '-') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
