<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pago.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

$pagoModel = new Pago($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'registrar':
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
                'monto' => floatval($_POST['monto'] ?? 0),
                'tipo' => trim($_POST['tipo'] ?? ''),
                'estado' => 'Pendiente',
                'idtrabajador' => intval($_POST['id_trabajador'] ?? 0)
            ];

            if ($pagoModel->crear($datos)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Pago registrado',
                    'text'  => 'El pago ha sido registrado exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => 'No se pudo registrar el pago'
                ];
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_pago'] ?? 0);
            if ($id > 0) {
                $datos = [
                    'fecha' => $_POST['fecha'] ?? '',
                    'monto' => floatval($_POST['monto'] ?? 0),
                    'tipo' => trim($_POST['tipo'] ?? ''),
                    'estado' => trim($_POST['estado'] ?? 'Pendiente'),
                    'idtrabajador' => intval($_POST['id_trabajador'] ?? 0)
                ];

                if ($pagoModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Pago actualizado',
                        'text'  => 'El registro de pago ha sido actualizado'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error',
                        'text'  => 'No se pudo actualizar el pago'
                    ];
                }
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            if ($pagoModel->eliminar($id)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Pago eliminado',
                    'text'  => 'El registro de pago ha sido eliminado'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => 'No se pudo eliminar el pago'
                ];
            }
        }
        break;
        
    case 'aprobar':
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            if ($pagoModel->actualizarEstado($id, 'Pagado')) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Pago aprobado',
                    'text'  => 'El pago ha sido aprobado exitosamente'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/pagos.php");
exit;
?>
