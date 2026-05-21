<?php
session_start();

// CORREGIDO: ruta definitiva según estructura real
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    public function registrar() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../views/usuarios/registre.php");
            exit;
        }

        $nombres            = trim($_POST['nombres']            ?? '');
        $apellidos          = trim($_POST['apellidos']          ?? '');
        $email              = trim($_POST['email']              ?? '');
        $password           = trim($_POST['password']           ?? '');
        $confirmar_password = trim($_POST['confirmar_password'] ?? '');
        $rol                = trim($_POST['rol']                ?? 'trabajador');
        $telefono           = trim($_POST['telefono']           ?? '');

        // ── Validaciones ───────────────────────────────────────
        if (empty($nombres) || empty($apellidos) || empty($email) || empty($password) || empty($confirmar_password)) {
            $_SESSION['alert'] = [
                'icon'  => 'warning',
                'title' => 'Campos incompletos',
                'text'  => 'Debe completar todos los campos obligatorios'
            ];
            header("Location: ../views/usuarios/registre.php");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = [
                'icon'  => 'error',
                'title' => 'Correo inválido',
                'text'  => 'Ingrese un correo electrónico válido'
            ];
            header("Location: ../views/usuarios/registre.php");
            exit;
        }

        if ($password !== $confirmar_password) {
            $_SESSION['alert'] = [
                'icon'  => 'error',
                'title' => 'Contraseñas no coinciden',
                'text'  => 'Las contraseñas ingresadas no son iguales'
            ];
            header("Location: ../views/usuarios/registre.php");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['alert'] = [
                'icon'  => 'warning',
                'title' => 'Contraseña muy corta',
                'text'  => 'La contraseña debe tener al menos 6 caracteres'
            ];
            header("Location: ../views/usuarios/registre.php");
            exit;
        }

        // ── Conexión y modelo ──────────────────────────────────
        $db      = getConnection();
        $usuario = new Usuario($db);

        if ($usuario->existeCorreo($email)) {
            $_SESSION['alert'] = [
                'icon'  => 'error',
                'title' => 'Correo ya registrado',
                'text'  => 'Este correo ya tiene una cuenta asociada'
            ];
            header("Location: ../views/usuarios/registre.php");
            exit;
        }

        $datos = [
            'nombre'     => $nombres . ' ' . $apellidos,
            'correo'     => $email,
            'celular'    => $telefono,
            'contrasena' => password_hash($password, PASSWORD_BCRYPT),
            'rol'        => $rol
        ];

        if ($usuario->crear($datos)) {
            $_SESSION['alert'] = [
                'icon'  => 'success',
                'title' => 'Registro exitoso',
                'text'  => 'Tu cuenta fue creada. Por favor inicia sesión.'
            ];
            header("Location: ../views/usuarios/login.php");
            exit;

        } else {
            $_SESSION['alert'] = [
                'icon'  => 'error',
                'title' => 'Error al registrar',
                'text'  => 'No se pudo crear la cuenta. Intenta nuevamente.'
            ];
            header("Location: ../views/usuarios/registre.php");
            exit;
        }
    }
}

$controller = new UsuarioController();
$controller->registrar();
?>