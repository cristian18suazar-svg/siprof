<?php
session_start();

// CORREGIDO: ruta definitiva según estructura real (config/database.php)
require_once __DIR__ . '/../config/database.php';
// CORREGIDO: estaba duplicado el require de database.php y faltaba Usuario.php
require_once __DIR__ . '/../models/Usuario.php';

// ─── LOGOUT ────────────────────────────────────────────────────
if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    session_destroy();
    header("Location: ../views/usuarios/login.php");
    exit();
}

// ─── REGISTRO ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'registrar') {

    $nombre   = trim($_POST['nombre']   ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['correo']   ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rol      = trim($_POST['rol']      ?? 'trabajador');

    if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Por favor, complete todos los campos obligatorios.";
        header("Location: ../views/usuarios/registre.php");
        exit();
    }

    $nombreCompleto = $nombre . ' ' . $apellido;

    try {
        $conn         = getConnection();
        $usuarioModel = new Usuario($conn);

        if ($usuarioModel->existeCorreo($email)) {
            $_SESSION['error'] = "El correo electrónico ya está registrado.";
            header("Location: ../views/usuarios/registre.php");
            exit();
        }

        $datos = [
            'nombre'     => $nombreCompleto,
            'correo'     => $email,
            'celular'    => $telefono,
            'contrasena' => password_hash($password, PASSWORD_BCRYPT),
            'rol'        => $rol
        ];

        if ($usuarioModel->crear($datos)) {
            $_SESSION['exito'] = "Cuenta creada exitosamente. Por favor inicie sesión.";
            header("Location: ../views/usuarios/login.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al crear la cuenta. Intente nuevamente.";
            header("Location: ../views/usuarios/registre.php");
            exit();
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Error del sistema: " . $e->getMessage();
        header("Location: ../views/usuarios/registre.php");
        exit();
    }
}

// ─── LOGIN ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['correo']   ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Debe ingresar correo y contraseña.";
        header("Location: ../views/usuarios/login.php");
        exit();
    }

    try {
        $conn = getConnection();

        $query = $conn->prepare("SELECT * FROM usuario WHERE Correo = :correo AND Estado = 'Activo'");
        $query->bindParam(":correo", $email);
        $query->execute();
        $usuario = $query->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado o se encuentra inactivo.";
            header("Location: ../views/usuarios/login.php");
            exit();
        }

        // Verificar contraseña — soporta bcrypt y texto plano (legado)
        $passwordValida = false;
        if (isset($usuario['Contrasena'])) {
            if (strpos($usuario['Contrasena'], '$2y$') === 0) {
                $passwordValida = password_verify($password, $usuario['Contrasena']);
            } else {
                $passwordValida = ($password === $usuario['Contrasena']);

                if ($passwordValida) {
                    $nuevoHash = password_hash($password, PASSWORD_BCRYPT);
                    $upd = $conn->prepare("UPDATE usuario SET Contrasena = :hash WHERE IDusuario = :id");
                    $upd->execute([':hash' => $nuevoHash, ':id' => $usuario['IDusuario']]);
                }
            }
        }

        if (!$passwordValida) {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: ../views/usuarios/login.php");
            exit();
        }

        // Crear sesión — regenerar ID para evitar session fixation
        session_regenerate_id(true);
        $rol = strtolower(trim($usuario['Niveldeacceso'] ?? ''));

        $_SESSION['usuario'] = [
            'id'     => $usuario['IDusuario'],
            'nombre' => $usuario['Nombre'],
            'correo' => $usuario['Correo'],
            'rol'    => $rol
        ];

        // Redirección según rol
        switch ($rol) {
            case 'administrador':
            case 'admin':
                header("Location: ../views/dashboard/admin.php");
                break;
            case 'mayordomo':
                header("Location: ../views/dashboard/mayordomo.php");
                break;
            case 'trabajador':
                header("Location: ../views/dashboard/trabajador.php");
                break;
            default:
                header("Location: ../views/dashboard/admin.php");
                break;
        }
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Error del sistema: " . $e->getMessage();
        header("Location: ../views/usuarios/login.php");
        exit();
    }
}

// Si llega por GET sin acción válida → al login
header("Location: ../views/usuarios/login.php");
exit();
?>