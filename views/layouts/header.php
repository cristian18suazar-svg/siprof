<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'SIPROF - Sistema Fincario' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar-transition { transition: all 0.3s ease; }
        .hover-scale { transition: transform 0.2s ease; }
        .hover-scale:hover { transform: scale(1.02); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">

            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-900 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <span class="text-gray-700 font-semibold text-lg"><?= htmlspecialchars($titulo ?? 'SIPROF') ?></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600 hidden md:block">
                            Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Usuario') ?></strong>
                        </div>
                        <a href="../../controllers/AuthController.php?accion=logout"
                           class="flex items-center gap-2 text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden md:inline">Salir</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
            
            <?php if (isset($_SESSION['alert'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: '<?= $_SESSION['alert']['icon'] ?>',
                        title: '<?= $_SESSION['alert']['title'] ?>',
                        text: '<?= $_SESSION['alert']['text'] ?>',
                        timer: 3000,
                        timerProgressBar: true,
                        confirmButtonColor: '#10b981'
                    });
                });
            </script>
            <?php unset($_SESSION['alert']); endif; ?>
