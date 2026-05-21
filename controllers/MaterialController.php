<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Material.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$materialModel = new Material($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'tipo' => trim($_POST['tipo'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'cantidad' => intval($_POST['cantidad'] ?? 0),
                'unidad' => trim($_POST['unidad'] ?? ''),
                'stock_minimo' => intval($_POST['stock_minimo'] ?? 0),
                'precio' => floatval($_POST['precio'] ?? 0),
                'estado' => 'activo'
            ];

            if ($materialModel->crear($datos)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Material registrado',
                    'text'  => 'El material ha sido guardado exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al crear',
                    'text'  => 'No se pudo registrar el material'
                ];
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_material'] ?? 0);
            if ($id > 0) {
                $datos = [
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'tipo' => trim($_POST['tipo'] ?? ''),
                    'descripcion' => trim($_POST['descripcion'] ?? ''),
                    'cantidad' => intval($_POST['cantidad'] ?? 0),
                    'unidad' => trim($_POST['unidad'] ?? ''),
                    'stock_minimo' => intval($_POST['stock_minimo'] ?? 0),
                    'precio' => floatval($_POST['precio'] ?? 0),
                    'estado' => trim($_POST['estado'] ?? 'activo')
                ];

                if ($materialModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Material actualizado',
                        'text'  => 'El material ha sido actualizado exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al actualizar',
                        'text'  => 'No se pudo actualizar el material'
                    ];
                }
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            if ($materialModel->eliminar($id)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Material eliminado',
                    'text'  => 'El material ha sido borrado exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al eliminar',
                    'text'  => 'No se pudo eliminar el material'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/materiales.php");
exit;
?>
