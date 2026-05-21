<?php
session_start();

require_once __DIR__ . '/../config/database.php';
// require_once __DIR__ . '/../models/Inventario.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$db = getConnection();
if (!$db) {
    die("Error de conexión a la base de datos");
}

// $inventarioModel = new Inventario($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO: Lógica para crear ítem de inventario
            $_SESSION['alert'] = [
                'icon'  => 'success',
                'title' => 'Ítem creado',
                'text'  => 'El ítem ha sido añadido al inventario'
            ];
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                // TODO: Lógica para editar ítem
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Ítem actualizado',
                    'text'  => 'El ítem del inventario ha sido actualizado'
                ];
            }
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            // TODO: Lógica para eliminar
            $_SESSION['alert'] = [
                'icon'  => 'success',
                'title' => 'Ítem eliminado',
                'text'  => 'El ítem ha sido eliminado del inventario'
            ];
        }
        break;
        
    case 'movimiento':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO: Registrar entrada/salida de inventario
            $_SESSION['alert'] = [
                'icon'  => 'success',
                'title' => 'Movimiento registrado',
                'text'  => 'El movimiento de inventario fue registrado exitosamente'
            ];
        }
        break;
}

header("Location: ../views/dashboard/inventario.php");
exit;
?>
