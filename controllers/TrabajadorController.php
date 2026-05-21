<?php
session_start();

/**
 * TrabajadorController
 * Gestiona el acceso y la lógica de datos para el panel del trabajador.
 */

// 1. Validar sesión y rol
if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));
if ($rol !== 'trabajador') {
    header("Location: ../usuarios/login.php");
    exit;
}

// 2. Cargar dependencias
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Labor.php';
require_once __DIR__ . '/../models/Pago.php';

$db = getConnection();
$idUsuario = $_SESSION['usuario']['id'] ?? 0;

// 3. Obtener datos para la vista
$laborModel = new Labor($db);
$todasLabores = $laborModel->obtenerTodos();
$misLabores = array_filter($todasLabores, fn($l) => (int)$l['IDtrabajador'] === (int)$idUsuario);

$pagoModel = new Pago($db);
$todosPagos = $pagoModel->obtenerTodos();
$misPagos = array_filter($todosPagos, fn($p) => (int)$p['IDtrabajador'] === (int)$idUsuario);

// 4. Procesar métricas (para evitar lógica pesada en la vista)
$metricas = [
    'totales'     => count($misLabores),
    'pendientes'  => count(array_filter($misLabores, fn($l) => strtolower($l['Estado']) === 'pendiente')),
    'enProceso'   => count(array_filter($misLabores, fn($l) => strtolower($l['Estado']) === 'proceso')),
    'ganadoTotal' => array_sum(array_column(array_values($misPagos), 'Monto')),
    'porCobrar'   => count(array_filter($misPagos, fn($p) => strtolower($p['Estado']) === 'pendiente'))
];

// 5. Manejo de acciones (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    
    switch ($accion) {
        case 'actualizar_estado':
            $idLabor = $_POST['id_labor'] ?? 0;
            $nuevoEstado = $_POST['estado'] ?? '';
            
            // Validar que la labor pertenece al trabajador logueado
            $todas = $laborModel->obtenerTodos();
            $laborValida = false;
            foreach ($todas as $l) {
                if ((int)$l['IDasignaciondelabor'] === (int)$idLabor && (int)$l['IDtrabajador'] === (int)$idUsuario) {
                    $laborValida = true;
                    break;
                }
            }
            
            if ($laborValida && !empty($nuevoEstado)) {
                if ($laborModel->actualizarEstado($idLabor, $nuevoEstado)) {
                    $_SESSION['alert'] = ['icon' => 'success', 'title' => '¡Éxito!', 'text' => 'Estado actualizado correctamente.'];
                } else {
                    $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo actualizar el estado.'];
                }
            }
            header("Location: ../views/dashboard/trabajador.php");
            exit;
    }
}
