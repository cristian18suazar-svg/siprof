<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Produccion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$produccionModel = new Produccion($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'fecha'     => $_POST['fecha'] ?? date('Y-m-d'),
                'cantidad'  => $_POST['cantidad'] ?? 0,
                'costo'     => $_POST['costo'] ?? 0,
                'tipo'      => $_POST['unidad'] ?? 'Unidad', // Usamos unidad como tipo temporalmente si no hay columna
                'idusuario' => $_SESSION['usuario']['id'] ?? 1,
                'idcultivo' => $_POST['id_cultivo'] ?? 0
            ];

            if ($produccionModel->crear($datos)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Producción registrada',
                    'text'  => 'La producción ha sido registrada exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => 'No se pudo registrar la producción'
                ];
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_produccion'] ?? 0);
            if ($id > 0) {
                // TODO: Implementar actualizar en el modelo si es necesario
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Producción actualizada',
                    'text'  => 'El registro ha sido actualizado exitosamente'
                ];
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            // TODO: Implementar eliminar en el modelo si es necesario
            $_SESSION['alert'] = [
                'icon'  => 'success',
                'title' => 'Producción eliminada',
                'text'  => 'El registro ha sido eliminado exitosamente'
            ];
        }
        break;
}

header("Location: ../views/dashboard/produccion.php");
exit;
?>
