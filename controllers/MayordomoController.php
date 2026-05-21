<?php
session_start();

/**
 * MayordomoController
 * Gestiona el acceso y la lógica de datos para el panel del Mayordomo.
 */

// 1. Validar sesión y rol
if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));
if ($rol !== 'mayordomo') {
    header("Location: ../usuarios/login.php");
    exit;
}

// 2. Cargar dependencias
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Labor.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../models/Cultivo.php';

$db = getConnection();

// 3. Cargar datos generales para el dashboard
$laborModel    = new Labor($db);
$materialModel = new Material($db);
$usuarioModel  = new Usuario($db);
$cultivoModel  = new Cultivo($db);

$labores    = $laborModel->obtenerTodos();
$materiales = $materialModel->obtenerTodos();
$usuarios   = $usuarioModel->obtenerTodos();
$cultivos   = $cultivoModel->obtenerTodos();

// 4. Procesar métricas
$metricas = [
    'pendientes' => count(array_filter($labores, fn($l) => strtolower($l['Estado']) === 'pendiente')),
    'enProceso'  => count(array_filter($labores, fn($l) => strtolower($l['Estado']) === 'proceso')),
    'stockCritico' => count(array_filter($materiales, fn($m) => $m['Cantidad'] <= $m['StockMinimo'])),
    'trabajadores' => count(array_filter($usuarios, fn($u) => strtolower($u['Niveldeacceso']) === 'trabajador'))
];

// Datos adicionales para tablas
$trabajadoresList = array_filter($usuarios, fn($u) => strtolower($u['Niveldeacceso']) === 'trabajador');

// 5. Manejo de acciones (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $idAdmin = $_SESSION['usuario']['id'] ?? 0;

    switch ($accion) {
        case 'asignar_labor':
            $datos = [
                'tarea'       => trim($_POST['tarea'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'inicio'      => $_POST['inicio'] ?? date('Y-m-d H:i'),
                'fin'         => $_POST['fin'] ?? date('Y-m-d H:i'),
                'estado'      => 'pendiente',
                'idtrabajador'=> $_POST['id_trabajador'] ?? 0,
                'idlote'      => $_POST['id_lote'] ?? 1,
                'idadmin'     => $idAdmin
            ];

            if ($laborModel->crear($datos)) {
                $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Asignación exitosa', 'text' => 'La labor ha sido asignada al trabajador.'];
            } else {
                $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo crear la asignación.'];
            }
            break;

        case 'registrar_produccion':
            require_once __DIR__ . '/../models/Produccion.php';
            $produccionModel = new Produccion($db);
            $datos = [
                'fecha'     => $_POST['fecha'] ?? date('Y-m-d'),
                'cantidad'  => $_POST['cantidad'] ?? 0,
                'costo'     => $_POST['costo'] ?? 0,
                'tipo'      => $_POST['tipo'] ?? 'Cosecha',
                'idusuario' => $idAdmin,
                'idcultivo' => $_POST['id_cultivo'] ?? 1
            ];

            if ($produccionModel->crear($datos)) {
                $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Producción registrada', 'text' => 'Los datos de cosecha se han guardado.'];
            } else {
                $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo registrar la producción.'];
            }
            break;

        case 'ajustar_stock':
            $idMat = $_POST['id_material'] ?? 0;
            $cant  = $_POST['cantidad'] ?? 0;
            $tipo  = $_POST['tipo'] ?? 'sumar'; // 'sumar' o 'restar'

            if ($materialModel->modificarStock($idMat, $cant, $tipo)) {
                $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Stock actualizado', 'text' => 'El inventario ha sido modificado.'];
            } else {
                $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo ajustar el stock.'];
            }
            break;
    }
    header("Location: ../views/dashboard/mayordomo.php");
    exit;
}
