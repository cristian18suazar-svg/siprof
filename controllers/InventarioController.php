<?php
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: ../views/usuarios/login.php"); exit; }

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Material.php';

$db     = getConnection();
$accion = $_GET['accion'] ?? '';
$idUsuario = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['IDusuario'] ?? 0;

switch ($accion) {

    case 'movimiento':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idMaterial = intval($_POST['id_material'] ?? 0);
            $tipo       = trim($_POST['tipo']       ?? '');
            $cantidad   = intval($_POST['cantidad']  ?? 0);
            $fecha      = trim($_POST['fecha']       ?? date('Y-m-d'));
            $motivo     = trim($_POST['motivo']      ?? '');

            if ($idMaterial > 0 && $cantidad > 0 && in_array($tipo, ['Entrada', 'Salida'])) {
                try {
                    $db->beginTransaction();

                    // 1. Registrar movimiento
                    $stmt = $db->prepare("INSERT INTO movimientoinventario
                        (Tipomovimiento, Cantidad, Fecha, Motivo, IDmateriales, IDusuario)
                        VALUES (:tipo, :cantidad, :fecha, :motivo, :idmat, :idusr)");
                    $stmt->execute([
                        ':tipo'     => $tipo,
                        ':cantidad' => $cantidad,
                        ':fecha'    => $fecha,
                        ':motivo'   => $motivo,
                        ':idmat'    => $idMaterial,
                        ':idusr'    => $idUsuario,
                    ]);

                    // 2. Actualizar stock del material
                    $operacion = $tipo === 'Entrada' ? '+' : '-';
                    $db->prepare("UPDATE materiales SET Cantidad = Cantidad {$operacion} :cantidad WHERE IDmateriales = :id")
                       ->execute([':cantidad' => $cantidad, ':id' => $idMaterial]);

                    $db->commit();
                    $_SESSION['alert'] = [
                        'icon'  => 'success',
                        'title' => 'Movimiento registrado',
                        'text'  => "Se registró la {$tipo} de {$cantidad} unidades correctamente."
                    ];
                } catch (Exception $e) {
                    $db->rollBack();
                    $_SESSION['alert'] = [
                        'icon'  => 'error',
                        'title' => 'Error',
                        'text'  => 'No se pudo registrar el movimiento.'
                    ];
                }
            } else {
                $_SESSION['alert'] = [
                    'icon'  => 'warning',
                    'title' => 'Datos incompletos',
                    'text'  => 'Seleccione material, tipo y cantidad válida.'
                ];
            }
        }
        break;
}

header("Location: ../views/dashboard/inventario.php");
exit;
