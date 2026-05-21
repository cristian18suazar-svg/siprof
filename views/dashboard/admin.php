<?php
//  1. Validar sesión
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

//  2. Validar rol de administrador
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));

if (!in_array($rol, ['administrador', 'admin'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

//  3. Conexión BD — RUTAS DEFINITIVAS
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Usuario.php';

$db = getConnection();

if (!$db) {
    die("Error de conexión a la base de datos");
}

//  4. Obtener usuarios
$usuarioModel = new Usuario($db);

try {
    $usuarios = $usuarioModel->obtenerTodos();
} catch (Exception $e) {
    $usuarios = [];
    error_log("Error al obtener usuarios: " . $e->getMessage());
}

//  5. Layout
$titulo = "Dashboard Administrador | SIPROF";
require_once __DIR__ . '/../layouts/header.php';
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
                    Gestión de <span class="text-emerald-300">Usuarios</span>
                </h2>
                <p class="text-emerald-50 text-lg md:text-xl font-light">Administra el acceso y los roles de tu equipo de trabajo en la finca.</p>
            </div>
            <button onclick="openModal('modalCrear')"
                class="group bg-white hover:bg-emerald-50 text-emerald-900 font-bold px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 hover:scale-105 flex items-center gap-3 whitespace-nowrap">
                <i class="fas fa-user-plus text-emerald-600 group-hover:rotate-12 transition-transform text-lg"></i>
                Nuevo Usuario
            </button>
        </div>
    </div>

    <!-- Alerta SweetAlert2 -->
    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?= htmlspecialchars($_SESSION['alert']['icon']) ?>',
                    title: '<?= htmlspecialchars($_SESSION['alert']['title']) ?>',
                    text: '<?= htmlspecialchars($_SESSION['alert']['text']) ?>',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#059669',
                    customClass: {
                        popup: 'rounded-3xl font-outfit',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold'
                    }
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <!-- Tabla -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Usuario</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Contacto</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest">Rol del Sistema</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Estado</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200 <?= ($usuario['Estado'] ?? '') !== 'Activo' ? 'bg-red-50/30' : '' ?>">

                                <!-- Usuario Info -->
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xl shadow-sm">
                                            <?= strtoupper(substr($usuario['Nombre'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-lg"><?= htmlspecialchars($usuario['Nombre'] ?? '') ?></p>
                                            <p class="text-sm text-gray-500 font-medium">ID: #<?= htmlspecialchars($usuario['IDusuario'] ?? '') ?></p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Contacto -->
                                <td class="p-6">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-gray-700 font-medium flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-envelope text-xs"></i>
                                            </div>
                                            <?= htmlspecialchars($usuario['Correo'] ?? '') ?>
                                        </span>
                                        <?php if (!empty($usuario['Celular'])): ?>
                                            <span class="text-gray-600 text-sm flex items-center gap-2 font-medium">
                                                <div class="w-6 h-6 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-phone text-xs"></i>
                                                </div>
                                                <?= htmlspecialchars($usuario['Celular']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Rol -->
                                <td class="p-6">
                                    <?php $r = strtolower($usuario['Niveldeacceso'] ?? ''); ?>
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider
                                        <?= $r === 'administrador' || $r === 'admin'
                                            ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-200'
                                            : ($r === 'mayordomo'
                                                ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200'
                                                : 'bg-orange-50 text-orange-700 ring-1 ring-orange-200') ?>">
                                        <?php if ($r === 'administrador' || $r === 'admin'): ?>
                                            <i class="fas fa-user-shield text-purple-500"></i>
                                        <?php elseif ($r === 'mayordomo'): ?>
                                            <i class="fas fa-user-tie text-blue-500"></i>
                                        <?php else: ?>
                                            <i class="fas fa-user-gear text-orange-500"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($usuario['Niveldeacceso'] ?? '') ?>
                                    </span>
                                </td>

                                <!-- Estado -->
                                <td class="p-6 text-center">
                                    <?php if (($usuario['Estado'] ?? '') === 'Activo'): ?>
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold ring-1 ring-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-700 text-xs font-bold ring-1 ring-red-200">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Acciones -->
                                <td class="p-6 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button"
                                            onclick='openEditModal(<?= json_encode($usuario) ?>)'
                                            class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5"
                                            title="Editar Usuario">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <?php if (($usuario['Estado'] ?? '') === 'Activo'): ?>
                                            <a href="../../controllers/AdminUsuarioController.php?accion=toggleEstado&id=<?= $usuario['IDusuario'] ?>&estado=0"
                                               onclick="return confirm('¿Estás seguro de desactivar a este usuario?')"
                                               class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5"
                                               title="Desactivar">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="../../controllers/AdminUsuarioController.php?accion=toggleEstado&id=<?= $usuario['IDusuario'] ?>&estado=1"
                                               onclick="return confirm('¿Estás seguro de activar a este usuario?')"
                                               class="w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5"
                                               title="Activar">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-16 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                                    </div>
                                    <p class="text-xl font-bold text-gray-800">No hay usuarios registrados</p>
                                    <p class="text-sm mt-1 text-gray-500">Comienza agregando miembros a tu equipo.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear Usuario -->
<div id="modalCrear" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-8 flex justify-between items-center text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                Nuevo Usuario
            </h3>
            <button onclick="closeModal('modalCrear')" class="text-emerald-100 hover:text-white transition-colors relative z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminUsuarioController.php?accion=crear" method="POST" class="p-8 space-y-6 bg-white">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombres</label>
                    <input type="text" name="nombres" required
                        class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Apellidos</label>
                    <input type="text" name="apellidos" required
                        class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-envelope"></i></div>
                    <input type="email" name="email" required
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-lock"></i></div>
                    <input type="password" name="password" required
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Rol del Sistema</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-briefcase"></i></div>
                    <select name="rol" required
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50 appearance-none">
                        <option value="">Seleccionar rol...</option>
                        <option value="administrador">Administrador</option>
                        <option value="mayordomo">Mayordomo</option>
                        <option value="trabajador">Trabajador</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-xs"></i></div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Celular <span class="text-xs font-normal text-gray-400">(Opcional)</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-phone"></i></div>
                    <input type="tel" name="celular"
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="closeModal('modalCrear')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl font-bold shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-1">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div id="modalEditar" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 font-outfit">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-8 flex justify-between items-center text-white relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay filter blur-xl transform translate-x-1/2 -translate-y-1/2"></div>
            <h3 class="text-2xl font-extrabold flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-pen text-xl"></i>
                </div>
                Editar Usuario
            </h3>
            <button onclick="closeModal('modalEditar')" class="text-blue-100 hover:text-white transition-colors relative z-10 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminUsuarioController.php?accion=editar" method="POST" class="p-8 space-y-6 bg-white">
            <input type="hidden" name="id_usuario" id="edit_id_usuario">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombres</label>
                    <input type="text" name="nombres" id="edit_nombres" required
                        class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Apellidos</label>
                    <input type="text" name="apellidos" id="edit_apellidos"
                        class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-envelope"></i></div>
                    <input type="email" name="email" id="edit_email" required
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nueva Contraseña <span class="text-xs font-normal text-gray-400">(Opcional)</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-lock"></i></div>
                    <input type="password" name="password" id="edit_password"
                        placeholder="Dejar en blanco para no cambiar"
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Rol del Sistema</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-briefcase"></i></div>
                    <select name="rol" id="edit_rol" required
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium bg-gray-50/50 appearance-none">
                        <option value="administrador">Administrador</option>
                        <option value="mayordomo">Mayordomo</option>
                        <option value="trabajador">Trabajador</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-xs"></i></div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Celular <span class="text-xs font-normal text-gray-400">(Opcional)</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fas fa-phone"></i></div>
                    <input type="tel" name="celular" id="edit_celular"
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium bg-gray-50/50">
                </div>
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="closeModal('modalEditar')"
                    class="px-6 py-3.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-1">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function openEditModal(usuario) {
    document.getElementById('edit_id_usuario').value = usuario.IDusuario;

    const partes = (usuario.Nombre ?? '').trim().split(' ');
    const mitad  = Math.ceil(partes.length / 2);
    document.getElementById('edit_nombres').value   = partes.slice(0, mitad).join(' ');
    document.getElementById('edit_apellidos').value = partes.slice(mitad).join(' ');

    document.getElementById('edit_email').value    = usuario.Correo ?? '';
    document.getElementById('edit_celular').value  = usuario.Celular ?? '';
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_rol').value      = (usuario.Niveldeacceso ?? '').toLowerCase();
    openModal('modalEditar');
}

document.addEventListener('click', function(e) {
    ['modalCrear', 'modalEditar'].forEach(function(id) {
        const modal = document.getElementById(id);
        if (e.target === modal) closeModal(id);
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
