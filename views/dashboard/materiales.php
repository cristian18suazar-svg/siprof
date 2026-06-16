<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Gestión de Materiales - SIPROF";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Material.php';

$db = getConnection();
$materialModel = new Material($db);
$materiales = $materialModel->obtenerTodos();

$totalMateriales  = count($materiales);
$valorInventario  = array_sum(array_map(function($m) { return $m['Cantidad'] * $m['Precio']; }, $materiales));
$stockCritico     = count(array_filter($materiales, function($m) { return $m['Cantidad'] <= $m['StockMinimo']; }));
$stockOk          = $totalMateriales - $stockCritico;

// Imagen representativa según tipo de material
function imagenPorTipo(string $tipo): string {
    $tipo = strtolower(trim($tipo));
    $mapa = [
        'abono'         => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
        'fertilizante'  => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
        'pala'          => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
        'machete'       => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
        'guadaña'       => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
        'canasto'       => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
        'herbicida'     => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=400&q=80',
        'fungicida'     => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=400&q=80',
        'insecticida'   => 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?w=400&q=80',
        'semilla'       => 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=400&q=80',
        'herramienta'   => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=400&q=80',
        'equipo'        => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=400&q=80',
        'riego'         => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&q=80',
    ];
    foreach ($mapa as $clave => $url) {
        if (str_contains($tipo, $clave)) return $url;
    }
    return 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=400&q=80';
}

// Color de badge según tipo
function colorTipo(string $tipo): string {
    $tipo = strtolower($tipo);
    if (str_contains($tipo, 'fertilizante') || str_contains($tipo, 'abono')) return 'bg-green-100 text-green-700';
    if (str_contains($tipo, 'herbicida'))   return 'bg-yellow-100 text-yellow-700';
    if (str_contains($tipo, 'fungicida'))   return 'bg-purple-100 text-purple-700';
    if (str_contains($tipo, 'insecticida')) return 'bg-orange-100 text-orange-700';
    if (str_contains($tipo, 'semilla'))     return 'bg-lime-100 text-lime-700';
    if (str_contains($tipo, 'herramienta') || str_contains($tipo, 'equipo')) return 'bg-blue-100 text-blue-700';
    return 'bg-gray-100 text-gray-600';
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .mat-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .mat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.10); }

    .stock-bar-track { background: #f1f5f9; border-radius: 99px; height: 6px; overflow: hidden; }
    .stock-bar-fill  { height: 100%; border-radius: 99px; transition: width 0.6s ease; }

    /* Búsqueda: ocultar tarjetas que no coincidan */
    .mat-card.hidden-search { display: none; }
</style>

<div class="font-outfit space-y-8 animate-fade-in p-2 md:p-4">

    <!-- ── HEADER ─────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-[2rem] shadow-xl p-8 md:p-10 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-black/10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <p class="text-emerald-300 text-sm font-semibold uppercase tracking-widest mb-2">Inventario</p>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3">
                    Gestión de <span class="text-emerald-300">Materiales</span>
                </h2>
                <p class="text-emerald-100/80 text-lg font-light">Control de insumos, herramientas y stock de la finca.</p>
            </div>
            <button onclick="openModal('modalMaterial')"
                class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.15)] transition-all duration-300 hover:scale-105 flex items-center gap-3 whitespace-nowrap">
                <i class="fas fa-plus-circle text-emerald-600 group-hover:rotate-90 transition-transform duration-300 text-lg"></i>
                Nuevo Material
            </button>
        </div>
    </div>

    <!-- ── ESTADÍSTICAS ───────────────────────────────────── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes-stacked text-emerald-600 text-lg"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900"><?= $totalMateriales ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Materiales registrados</p>
        </div>

        <!-- Stock OK -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-circle-check text-green-500 text-lg"></i>
                </div>
                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg">OK</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900"><?= $stockOk ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Stock suficiente</p>
        </div>

        <!-- Stock crítico -->
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-triangle-exclamation text-red-500 text-lg"></i>
                </div>
                <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Alerta</span>
            </div>
            <p class="text-3xl font-extrabold <?= $stockCritico > 0 ? 'text-red-600' : 'text-gray-900' ?>"><?= $stockCritico ?></p>
            <p class="text-xs text-gray-500 font-medium mt-1">Stock crítico</p>
        </div>

        <!-- Valor inventario -->
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
                <span class="text-xs font-bold text-emerald-200 bg-white/10 px-2 py-1 rounded-lg">Valor</span>
            </div>
            <p class="text-2xl font-extrabold">$ <?= number_format($valorInventario, 0, ',', '.') ?></p>
            <p class="text-xs text-emerald-200 font-medium mt-1">Valor total inventario</p>
        </div>
    </div>

    <!-- ── BARRA DE BÚSQUEDA + TÍTULO ─────────────────────── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-layer-group text-emerald-600"></i>
            Catálogo de Materiales
            <span class="text-sm font-semibold text-gray-400 bg-gray-100 px-2.5 py-0.5 rounded-full"><?= $totalMateriales ?></span>
        </h3>
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input id="buscadorMaterial" type="search" placeholder="Buscar material..."
                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-medium bg-white shadow-sm">
        </div>
    </div>

    <!-- ── GRID DE TARJETAS ───────────────────────────────── -->
    <?php if (empty($materiales)): ?>
    <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-16 text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-boxes-stacked text-3xl text-gray-300"></i>
        </div>
        <p class="text-gray-500 font-semibold text-lg mb-1">Sin materiales registrados</p>
        <p class="text-gray-400 text-sm mb-6">Agrega el primer material al inventario</p>
        <button onclick="openModal('modalMaterial')"
            class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all hover:scale-105 shadow-lg shadow-emerald-500/30">
            <i class="fas fa-plus"></i> Agregar Material
        </button>
    </div>
    <?php else: ?>
    <div id="gridMateriales" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach ($materiales as $m):
            $critico     = $m['Cantidad'] <= $m['StockMinimo'];
            $pct         = $m['StockMinimo'] > 0 ? min(100, round(($m['Cantidad'] / ($m['StockMinimo'] * 2)) * 100)) : 100;
            $barColor    = $critico ? 'bg-red-500' : ($pct < 60 ? 'bg-yellow-400' : 'bg-emerald-500');
            $imgUrl      = imagenPorTipo($m['Tipo']);
            $badgeColor  = colorTipo($m['Tipo']);
            $valorTotal  = $m['Cantidad'] * $m['Precio'];
        ?>
        <div class="mat-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
             data-nombre="<?= strtolower(htmlspecialchars($m['Nombre'])) ?>"
             data-tipo="<?= strtolower(htmlspecialchars($m['Tipo'])) ?>">

            <!-- Imagen -->
            <div class="relative h-40 overflow-hidden">
                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($m['Nombre']) ?>"
                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                     onerror="this.src='https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=400&q=80'">
                <!-- Overlay degradado -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                <!-- Badge tipo -->
                <span class="absolute top-3 left-3 text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full <?= $badgeColor ?> shadow-sm">
                    <?= htmlspecialchars($m['Tipo']) ?>
                </span>

                <!-- Badge stock crítico -->
                <?php if ($critico): ?>
                <span class="absolute top-3 right-3 flex items-center gap-1 text-[11px] font-bold bg-red-500 text-white px-2.5 py-1 rounded-full shadow-sm">
                    <i class="fas fa-triangle-exclamation text-[10px]"></i> Crítico
                </span>
                <?php endif; ?>

                <!-- Nombre sobre imagen -->
                <div class="absolute bottom-3 left-3 right-3">
                    <p class="text-white font-bold text-base leading-tight drop-shadow-md">
                        <?= htmlspecialchars($m['Nombre']) ?>
                    </p>
                </div>
            </div>

            <!-- Cuerpo -->
            <div class="p-4 space-y-3">

                <!-- Stock con barra -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Stock</span>
                        <span class="text-xs font-bold <?= $critico ? 'text-red-600' : 'text-gray-700' ?>">
                            <?= $m['Cantidad'] ?> / <?= $m['StockMinimo'] ?> <?= htmlspecialchars($m['Unidad']) ?>
                        </span>
                    </div>
                    <div class="stock-bar-track">
                        <div class="stock-bar-fill <?= $barColor ?>" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>

                <!-- Precio y valor -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-gray-50 rounded-xl p-2.5 text-center">
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Precio unit.</p>
                        <p class="text-sm font-extrabold text-gray-800">$ <?= number_format($m['Precio'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-2.5 text-center">
                        <p class="text-[10px] text-emerald-600 font-semibold uppercase tracking-wide">Valor total</p>
                        <p class="text-sm font-extrabold text-emerald-700">$ <?= number_format($valorTotal, 0, ',', '.') ?></p>
                    </div>
                </div>

                <!-- Descripción -->
                <?php if (!empty($m['Descripcion'])): ?>
                <p class="text-xs text-gray-400 leading-relaxed line-clamp-2"><?= htmlspecialchars($m['Descripcion']) ?></p>
                <?php endif; ?>

                <!-- Acciones -->
                <div class="flex gap-2 pt-1">
                    <button onclick='editarMaterial(<?= json_encode($m) ?>)'
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold text-sm transition-all">
                        <i class="fas fa-pen text-xs"></i> Editar
                    </button>
                    <button onclick="confirmarEliminarMaterial(<?= $m['IDmateriales'] ?>)"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white font-semibold text-sm transition-all">
                        <i class="fas fa-trash text-xs"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<!-- ── MODAL MATERIAL ─────────────────────────────────────── -->
<div id="modalMaterial" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-8 flex justify-between items-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 id="modalTitleMaterial" class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes-stacked text-xl"></i>
                </div>
                <span>Nuevo Material</span>
            </h3>
            <button onclick="closeModal('modalMaterial')" class="relative z-10 w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="formMaterial" action="../../controllers/MaterialController.php?accion=crear" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="id_material" id="id_material">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Material <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" id="nombre_material" placeholder="Ej: Fertilizante NPK" required
                       class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo <span class="text-red-500">*</span></label>
                    <input type="text" name="tipo" id="tipo_material" placeholder="Ej: Fertilizante, Herramienta" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Unidad <span class="text-red-500">*</span></label>
                    <input type="text" name="unidad" id="unidad_material" placeholder="Ej: Kg, Litros, Unid" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Cantidad Actual <span class="text-red-500">*</span></label>
                    <input type="number" name="cantidad" id="cantidad_material" step="0.01" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Stock Mínimo <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_minimo" id="stock_minimo_material" step="0.01" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Precio Unitario <span class="text-red-500">*</span></label>
                    <input type="number" name="precio" id="precio_material" step="0.01" required
                           class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion_material" rows="3" placeholder="Detalles del material..."
                          class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50 resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modalMaterial')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Material
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modalMaterial' && !document.getElementById('id_material').value) {
        document.getElementById('modalTitleMaterial').querySelector('span').innerText = 'Nuevo Material';
        document.getElementById('formMaterial').action = '../../controllers/MaterialController.php?accion=crear';
        document.getElementById('formMaterial').reset();
    }
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function editarMaterial(m) {
    document.getElementById('modalTitleMaterial').querySelector('span').innerText = 'Editar Material';
    document.getElementById('formMaterial').action = '../../controllers/MaterialController.php?accion=editar';
    document.getElementById('id_material').value          = m.IDmateriales;
    document.getElementById('nombre_material').value      = m.Nombre;
    document.getElementById('tipo_material').value        = m.Tipo;
    document.getElementById('unidad_material').value      = m.Unidad;
    document.getElementById('cantidad_material').value    = m.Cantidad;
    document.getElementById('stock_minimo_material').value= m.StockMinimo;
    document.getElementById('precio_material').value      = m.Precio;
    document.getElementById('descripcion_material').value = m.Descripcion;
    openModal('modalMaterial');
}
function confirmarEliminarMaterial(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará el material permanentemente.',
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
            f.action = '../../controllers/MaterialController.php?accion=eliminar';
            const i = document.createElement('input');
            i.type = 'hidden'; i.name = 'id'; i.value = id;
            f.appendChild(i); document.body.appendChild(f); f.submit();
        }
    });
}

// Búsqueda en tiempo real
document.getElementById('buscadorMaterial')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.mat-card').forEach(card => {
        const nombre = card.dataset.nombre || '';
        const tipo   = card.dataset.tipo   || '';
        card.classList.toggle('hidden-search', q !== '' && !nombre.includes(q) && !tipo.includes(q));
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
