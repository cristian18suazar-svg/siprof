<?php
session_start();

// RUTAS DEFINITIVAS según estructura real del proyecto
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

// Validar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

// Validar rol de administrador
$rol = strtolower(trim($_SESSION['usuario']['rol'] ?? ''));
if (!in_array($rol, ['administrador', 'admin'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$db = getConnection();

if (!$db) {
    die("Error de conexión a la base de datos");
}

$usuarioModel = new Usuario($db);

$accion = $_GET['accion'] ?? '';

switch ($accion) {

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres   = trim($_POST['nombres']   ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $nombre    = trim($nombres . ' ' . $apellidos);

            $datos = [
                'nombre'     => $nombre,
                'correo'     => trim($_POST['email']    ?? ''),
                'celular'    => trim($_POST['celular']  ?? ''),
                'contrasena' => trim($_POST['password'] ?? ''),
                'rol'        => trim($_POST['rol']      ?? 'trabajador')
            ];

            if (empty($datos['nombre']) || empty($datos['correo']) || empty($datos['contrasena'])) {
                $_SESSION['alert'] = [
                    'icon'  => 'warning',
                    'title' => 'Campos incompletos',
                    'text'  => 'Nombre, correo y contraseña son obligatorios'
                ];
            } elseif ($usuarioModel->existeCorreo($datos['correo'])) {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Correo duplicado',
                    'text'  => 'Este correo ya está registrado'
                ];
            } else {
                $datos['contrasena'] = password_hash($datos['contrasena'], PASSWORD_BCRYPT);

                if ($usuarioModel->crear($datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Usuario creado',
                        'text'  => 'El usuario ha sido creado exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al crear',
                        'text'  => 'No se pudo crear el usuario'
                    ];
                }
            }
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_usuario'] ?? 0);

            if ($id > 0) {
                $nombres   = trim($_POST['nombres']   ?? '');
                $apellidos = trim($_POST['apellidos'] ?? '');
                $nombre    = trim($nombres . ' ' . $apellidos);

                $datos = [
                    'nombre'  => $nombre,
                    'correo'  => trim($_POST['email']   ?? ''),
                    'celular' => trim($_POST['celular'] ?? ''),
                    'rol'     => trim($_POST['rol']     ?? 'trabajador')
                ];

                if (!empty($_POST['password'])) {
                    $datos['contrasena'] = password_hash(
                        trim($_POST['password']),
                        PASSWORD_BCRYPT
                    );
                }

                if ($usuarioModel->actualizar($id, $datos)) {
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Usuario actualizado',
                        'text'  => 'El usuario ha sido actualizado exitosamente'
                    ];
                } else {
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error al actualizar',
                        'text'  => 'No se pudo actualizar el usuario'
                    ];
                }
            }
        }
        break;

    case 'toggleEstado':
        $id     = intval($_GET['id']     ?? 0);
        $estado = intval($_GET['estado'] ?? 0);

        if ($id > 0) {
            if ($usuarioModel->toggleEstado($id, $estado)) {
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

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);

        if ($id > 0) {
            if ($usuarioModel->eliminar($id)) {
                $_SESSION['alert'] = [
                    'icon'  => 'success',
                    'title' => 'Usuario eliminado',
                    'text'  => 'El usuario ha sido eliminado exitosamente'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'error',
                    'title' => 'Error al eliminar',
                    'text'  => 'No se pudo eliminar el usuario'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/admin.php");
exit;
?>