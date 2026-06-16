<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cultivo.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$cultivoModel = new Cultivo($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'inicio' => trim($_POST['fecha_inicio'] ?? ''),
                'cosecha' => trim($_POST['fecha_cosecha'] ?? ''),
                'estado' => 'Activo',
                'idfase' => intval($_POST['id_fase'] ?? 0),
                'idlote' => intval($_POST['id_lote'] ?? 0)
            ];

            if ($cultivoModel->crear($datos)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Cultivo creado',
                    'text'  => 'El cultivo ha sido registrado exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al crear',
                    'text'  => 'No se pudo registrar el cultivo'
                ];
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_cultivo'] ?? 0);
            if ($id > 0) {
                $datos = [
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'inicio' => trim($_POST['fecha_inicio'] ?? ''),
                    'cosecha' => trim($_POST['fecha_cosecha'] ?? ''),
                    'estado' => trim($_POST['estado'] ?? 'Activo'),
                    'idfase' => intval($_POST['id_fase'] ?? 0),
                    'idlote' => intval($_POST['id_lote'] ?? 0)
                ];

                if ($cultivoModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Cultivo actualizado',
                        'text'  => 'El cultivo ha sido actualizado exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al actualizar',
                        'text'  => 'No se pudo actualizar el cultivo'
                    ];
                }
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $db->beginTransaction();
                $cultivoModel->eliminarDependencias($id);
                $ok = $cultivoModel->eliminar($id);
                if ($ok) {
                    $db->commit();
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Cultivo eliminado',
                        'text'  => 'El cultivo y sus registros asociados fueron eliminados.'
                    ];
                } else {
                    $db->rollBack();
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al eliminar',
                        'text'  => 'No se pudo eliminar el cultivo.'
                    ];
                }
            } catch (Exception $e) {
                $db->rollBack();
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error del sistema',
                    'text'  => 'Ocurrió un error inesperado al eliminar el cultivo.'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/cultivos.php");
exit;
?>
