<?php
$paginaActual = basename($_SERVER['PHP_SELF']);

// Compatibilidad PHP 7+ — evitar operadores encadenados ?? en versiones antiguas
$usuarioRol = '';
if (isset($_SESSION['usuario']['rol'])) {
    $usuarioRol = $_SESSION['usuario']['rol'];
} elseif (isset($_SESSION['usuario']['Niveldeacceso'])) {
    $usuarioRol = $_SESSION['usuario']['Niveldeacceso'];
}
$rolActual    = strtolower(trim($usuarioRol));
$esAdmin      = in_array($rolActual, ['administrador', 'admin']);
$esMayordomo  = ($rolActual === 'mayordomo');
$esTrabajador = ($rolActual === 'trabajador');

// Menú según rol
if ($esAdmin) {
    $menuPrincipal = array(
        array('href' => 'admin.php',      'icon' => 'fa-house',        'label' => 'Dashboard'),
        array('href' => 'produccion.php', 'icon' => 'fa-chart-line',   'label' => 'Producción'),
        array('href' => 'lotes.php',      'icon' => 'fa-map',          'label' => 'Lotes'),
        array('href' => 'cultivos.php',   'icon' => 'fa-seedling',     'label' => 'Cultivos'),
        array('href' => 'fases.php',      'icon' => 'fa-layer-group',  'label' => 'Fases'),
        array('href' => 'labores.php',    'icon' => 'fa-list-check',   'label' => 'Labores'),
        array('href' => 'pagos.php',      'icon' => 'fa-dollar-sign',  'label' => 'Pagos'),
        array('href' => 'materiales.php', 'icon' => 'fa-boxes-stacked','label' => 'Materiales'),
    );
    $menuAdmin = array(
        array('href' => 'control_cultivos.php', 'icon' => 'fa-bug',      'label' => 'Control Cultivos'),
        array('href' => 'inventario.php',       'icon' => 'fa-warehouse', 'label' => 'Inventario'),
        array('href' => 'reportes.php',         'icon' => 'fa-chart-bar', 'label' => 'Reportes'),
    );
} elseif ($esMayordomo) {
    $menuPrincipal = array(
        array('href' => 'mayordomo.php',  'icon' => 'fa-house',        'label' => 'Dashboard'),
        array('href' => 'labores.php',    'icon' => 'fa-list-check',   'label' => 'Labores'),
        array('href' => 'produccion.php', 'icon' => 'fa-chart-line',   'label' => 'Producción'),
        array('href' => 'lotes.php',      'icon' => 'fa-map',          'label' => 'Lotes'),
        array('href' => 'cultivos.php',   'icon' => 'fa-seedling',     'label' => 'Cultivos'),
        array('href' => 'materiales.php', 'icon' => 'fa-boxes-stacked','label' => 'Materiales'),
        array('href' => 'pagos.php',      'icon' => 'fa-dollar-sign',  'label' => 'Pagos'),
    );
    $menuAdmin = array();
} elseif ($esTrabajador) {
    $menuPrincipal = array(
        array('href' => 'trabajador.php', 'icon' => 'fa-house', 'label' => 'Mi Panel'),
    );
    $menuAdmin = array();
} else {
    $menuPrincipal = array(array('href' => 'admin.php', 'icon' => 'fa-house', 'label' => 'Dashboard'));
    $menuAdmin = array();
}
?>

<style>
    #sidebar {
        background: linear-gradient(160deg, #0f3d1f 0%, #14532d 35%, #166534 70%, #1a7a3c 100%);
        box-shadow: 4px 0 24px rgba(0,0,0,0.25);
    }

    /* Patrón de puntos sutil en el fondo */
    #sidebar::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 20px 20px;
        pointer-events: none;
        z-index: 0;
    }

    .nav-item {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 14px;
        transition: all 0.22s cubic-bezier(.4,0,.2,1);
        color: rgba(255,255,255,0.6);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        overflow: hidden;
    }
    .nav-item:hover {
        background: rgba(255,255,255,0.08);
        color: #fff;
        transform: translateX(3px);
    }
    .nav-item.active {
        background: rgba(255,255,255,0.13);
        color: #fff;
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12), 0 4px 16px rgba(0,0,0,0.15);
    }
    .nav-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 20%; bottom: 20%;
        width: 3px;
        background: linear-gradient(180deg, #86efac, #22c55e);
        border-radius: 0 4px 4px 0;
    }

    .nav-icon {
        width: 34px; height: 34px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 0.85rem;
        transition: all 0.22s ease;
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.7);
    }
    .nav-item:hover .nav-icon,
    .nav-item.active .nav-icon {
        background: rgba(34,197,94,0.25);
        color: #86efac;
        box-shadow: 0 0 12px rgba(34,197,94,0.2);
    }

    /* Scrollbar del nav */
    #sidebar-nav::-webkit-scrollbar { width: 4px; }
    #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

    /* Animación de entrada */
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .nav-item { animation: slideInLeft 0.3s ease both; }
    <?php
    $i = 1;
    foreach (array_merge($menuPrincipal, $menuAdmin) as $_) {
        echo ".nav-item:nth-child({$i}) { animation-delay: " . ($i * 0.04) . "s; }\n";
        $i++;
    }
    ?>
</style>

<!-- ═══════════════════════════════════════════ SIDEBAR ═══ -->
<aside id="sidebar" class="sidebar-transition w-64 flex-shrink-0 min-h-screen flex flex-col relative">

    <!-- ── LOGO ─────────────────────────────────────────── -->
    <div class="relative z-10 px-5 pt-6 pb-5">
        <div class="flex items-center gap-3">
            <!-- Ícono con brillo -->
            <div class="relative w-12 h-12 flex-shrink-0">
                <div class="absolute inset-0 bg-green-400/30 rounded-2xl blur-md"></div>
                <div class="relative w-12 h-12 bg-gradient-to-br from-green-400 to-green-700 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
            </div>
            <div>
                <h1 class="text-white font-extrabold text-xl leading-none tracking-tight">SIPROF</h1>
                <p class="text-green-300/80 text-[11px] font-medium mt-0.5 tracking-wide">Sistema Fincario</p>
            </div>
        </div>

        <!-- Línea decorativa degradada -->
        <div class="mt-5 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    </div>



    <!-- ── NAVEGACIÓN ────────────────────────────────────── -->
    <nav id="sidebar-nav" class="relative z-10 flex-1 px-3 overflow-y-auto pb-2">

        <!-- Sección Principal -->
        <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-white/25 px-3 mb-2">
            Principal
        </p>

        <ul class="space-y-0.5 mb-5">
            <?php foreach ($menuPrincipal as $item): ?>
            <li>
                <a href="<?= $item['href'] ?>"
                   class="nav-item <?= $paginaActual === $item['href'] ? 'active' : '' ?>">
                    <span class="nav-icon">
                        <i class="fas <?= $item['icon'] ?>"></i>
                    </span>
                    <span><?= $item['label'] ?></span>
                    <?php if ($paginaActual === $item['href']): ?>
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-green-400 shadow-[0_0_6px_#4ade80]"></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <!-- Sección Administración -->
        <?php if ($esAdmin): ?>
        <div class="h-px bg-gradient-to-r from-transparent via-white/15 to-transparent mb-4"></div>

        <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-amber-300/50 px-3 mb-2 flex items-center gap-2">
            <i class="fas fa-shield-halved text-[9px]"></i> Administración
        </p>

        <ul class="space-y-0.5 mb-4">
            <?php foreach ($menuAdmin as $item): ?>
            <li>
                <a href="<?= $item['href'] ?>"
                   class="nav-item <?= $paginaActual === $item['href'] ? 'active' : '' ?>">
                    <span class="nav-icon <?= $paginaActual === $item['href'] ? '!bg-amber-400/20 !text-amber-300' : '' ?>">
                        <i class="fas <?= $item['icon'] ?>"></i>
                    </span>
                    <span><?= $item['label'] ?></span>
                    <?php if ($paginaActual === $item['href']): ?>
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_6px_#fbbf24]"></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

    </nav>

    <!-- ── PERFIL + CERRAR SESIÓN ───────────────────────── -->
    <div class="relative z-10 px-3 pb-5 pt-3">
        <div class="h-px bg-gradient-to-r from-transparent via-white/15 to-transparent mb-3"></div>

        <!-- Tarjeta de perfil -->
        <div class="bg-white/[0.07] border border-white/10 rounded-2xl px-4 py-3 flex items-center gap-3 backdrop-blur-sm mb-2">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-300 to-green-600 flex items-center justify-center shadow-md text-white font-bold text-base">
                    <?php
                    $nombreUsuario = isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'U';
                    echo strtoupper(substr($nombreUsuario, 0, 1));
                    ?>
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-green-900 rounded-full"></span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white text-sm font-semibold truncate leading-tight">
                    <?php echo htmlspecialchars(isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Usuario'); ?>
                </p>
                <?php
                $rolDisplay = '';
                if (isset($_SESSION['usuario']['rol'])) {
                    $rolDisplay = $_SESSION['usuario']['rol'];
                } elseif (isset($_SESSION['usuario']['Niveldeacceso'])) {
                    $rolDisplay = $_SESSION['usuario']['Niveldeacceso'];
                } else {
                    $rolDisplay = 'Trabajador';
                }
                $badgeClass = $esAdmin ? 'bg-amber-400/20 text-amber-300' : 'bg-emerald-400/20 text-emerald-300';
                $dotClass   = $esAdmin ? 'bg-amber-400' : 'bg-emerald-400';
                ?>
                <span class="inline-flex items-center gap-1 mt-0.5 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full <?php echo $badgeClass; ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?php echo $dotClass; ?>"></span>
                    <?php echo htmlspecialchars($rolDisplay); ?>
                </span>
            </div>
        </div>

        <!-- Cerrar sesión -->
        <a href="../../controllers/AuthController.php?accion=logout"
           class="nav-item group hover:!bg-red-500/15 hover:!text-red-300">
            <span class="nav-icon group-hover:!bg-red-500/25 group-hover:!text-red-300 group-hover:!shadow-none">
                <i class="fas fa-right-from-bracket"></i>
            </span>
            <span>Cerrar Sesión</span>
        </a>
    </div>

</aside>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    s.classList.toggle('hidden');
}
</script>
