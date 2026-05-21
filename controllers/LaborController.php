<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Labor.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$laborModel = new Labor($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'tarea' => trim($_POST['tarea'] ?? ''),
                'inicio' => $_POST['inicio'] ?? '',
                'fin' => $_POST['fin'] ?? '',
                'estado' => 'Pendiente',
                'idadmin' => $_SESSION['usuario']['IDusuario'] ?? 1,
                'idtrabajador' => intval($_POST['id_trabajador'] ?? 1),
                'idlote' => intval($_POST['id_lote'] ?? 0)
            ];

            if ($laborModel->crear($datos)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Labor registrada',
                    'text'  => 'La labor ha sido registrada exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => 'No se pudo registrar la labor'
                ];
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_labor'] ?? 0);
            if ($id > 0) {
                $datos = [
                    'descripcion' => trim($_POST['descripcion'] ?? ''),
                    'tarea' => trim($_POST['tarea'] ?? ''),
                    'inicio' => $_POST['inicio'] ?? '',
                    'fin' => $_POST['fin'] ?? '',
                    'estado' => $_POST['estado'] ?? 'Pendiente',
                    'idtrabajador' => intval($_POST['id_trabajador'] ?? 1),
                    'idlote' => intval($_POST['id_lote'] ?? 0)
                ];

                if ($laborModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Labor actualizada',
                        'text'  => 'La labor ha sido actualizada exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error',
                        'text'  => 'No se pudo actualizar la labor'
                    ];
                }
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            if ($laborModel->eliminar($id)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Labor eliminada',
                    'text'  => 'La labor ha sido eliminada exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => 'No se pudo eliminar la labor'
                ];
            }
        }
        break;
        
    case 'completar':
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            if ($laborModel->actualizarEstado($id, 'Completada')) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Labor completada',
                    'text'  => 'La labor ha sido marcada como completada'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/labores.php");
exit;
?>
