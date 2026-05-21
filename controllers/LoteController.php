<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Lote.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$loteModel = new Lote($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'ubicacion' => trim($_POST['ubicacion'] ?? ''),
                'area' => trim($_POST['area'] ?? ''),
                'estado' => 'Activo'
            ];

            if (empty($datos['nombre']) || empty($datos['ubicacion']) || empty($datos['area'])) {
                $_SESSION['alert'] = [
                    'icon'  => 'warning',
                    'title' => 'Campos incompletos',
                    'text'  => 'Nombre, ubicación y área son obligatorios'
                ];
            } else {
                if ($loteModel->crear($datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Lote creado',
                        'text'  => 'El lote ha sido creado exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al crear',
                        'text'  => 'No se pudo crear el lote'
                    ];
                }
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_lote'] ?? 0);
            if ($id > 0) {
                $datos = [
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'ubicacion' => trim($_POST['ubicacion'] ?? ''),
                    'area' => trim($_POST['area'] ?? ''),
                    'estado' => trim($_POST['estado'] ?? 'Activo')
                ];

                if ($loteModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Lote actualizado',
                        'text'  => 'El lote ha sido actualizado exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al actualizar',
                        'text'  => 'No se pudo actualizar el lote'
                    ];
                }
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            if ($loteModel->eliminar($id)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Lote eliminado',
                    'text'  => 'El lote ha sido eliminado exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al eliminar',
                    'text'  => 'No se pudo eliminar el lote'
                ];
            }
        }
        break;
        
    case 'toggleEstado':
        $id = intval($_GET['id'] ?? 0);
        $estado = intval($_GET['estado'] ?? 0);
        if ($id > 0) {
            if ($loteModel->toggleEstado($id, $estado)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Estado actualizado',
                    'text'  => 'El estado del usuario ha sido cambiado'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al cambiar estado',
                    'text'  => 'No se pudo cambiar el estado del usuario'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/lotes.php");
exit;
?>
