<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Fase.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$faseModel = new Fase($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'duracion' => trim($_POST['duracion'] ?? '')
            ];

            if (empty($datos['nombre'])) {
                $_SESSION['alert'] = [
                    'icon'  => 'warning',
                    'title' => 'Campo requerido',
                    'text'  => 'El nombre de la fase es obligatorio'
                ];
            } else {
                if ($faseModel->crear($datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Fase creada',
                        'text'  => 'La fase ha sido creada exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al crear',
                        'text'  => 'No se pudo crear la fase'
                    ];
                }
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_fase'] ?? 0);
            if ($id > 0) {
                $datos = [
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'descripcion' => trim($_POST['descripcion'] ?? ''),
                    'duracion' => trim($_POST['duracion'] ?? '')
                ];

                if ($faseModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Fase actualizada',
                        'text'  => 'La fase ha sido actualizada exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al actualizar',
                        'text'  => 'No se pudo actualizar la fase'
                    ];
                }
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            if ($faseModel->eliminar($id)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Fase eliminada',
                    'text'  => 'La fase ha sido eliminada exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al eliminar',
                    'text'  => 'No se pudo eliminar la fase'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/fases.php");
exit;
?>
