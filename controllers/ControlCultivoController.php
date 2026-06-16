<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ControlCultivo.php';

$db    = getConnection();
$model = new ControlCultivo($db);
$accion = $_GET['accion'] ?? '';

switch ($accion) {

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'tipo'          => trim($_POST['tipo']          ?? ''),
                'valor'         => trim($_POST['valor']         ?? ''),
                'descripcion'   => trim($_POST['descripcion']   ?? ''),
                'estado'        => trim($_POST['estado']        ?? 'abierto'),
                'fechareporte'  => trim($_POST['fechareporte']  ?? date('Y-m-d')),
                'fechasolucion' => trim($_POST['fechasolucion'] ?? ''),
                'idcultivo'     => intval($_POST['idcultivo']   ?? 0),
                'idfase'        => intval($_POST['idfase']      ?? 0),
                'idusuario'     => $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['IDusuario'] ?? 0,
            ];

            $_SESSION['alert'] = $model->crear($datos)
                ? ['icon' => 'success', 'title' => 'Problema registrado',   'text' => 'El problema fue guardado correctamente.']
                : ['icon' => 'error',   'title' => 'Error al registrar',    'text' => 'No se pudo guardar el problema.'];
        }
        break;

    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id    = intval($_POST['id_control'] ?? 0);
            $datos = [
                'tipo'          => trim($_POST['tipo']          ?? ''),
                'valor'         => trim($_POST['valor']         ?? ''),
                'descripcion'   => trim($_POST['descripcion']   ?? ''),
                'estado'        => trim($_POST['estado']        ?? 'abierto'),
                'fechareporte'  => trim($_POST['fechareporte']  ?? ''),
                'fechasolucion' => trim($_POST['fechasolucion'] ?? ''),
                'idcultivo'     => intval($_POST['idcultivo']   ?? 0),
                'idfase'        => intval($_POST['idfase']      ?? 0),
            ];

            $_SESSION['alert'] = ($id > 0 && $model->actualizar($id, $datos))
                ? ['icon' => 'success', 'title' => 'Problema actualizado', 'text' => 'Los cambios fueron guardados.']
                : ['icon' => 'error',   'title' => 'Error al actualizar',  'text' => 'No se pudo actualizar el problema.'];
        }
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        $_SESSION['alert'] = ($id > 0 && $model->eliminar($id))
            ? ['icon' => 'success', 'title' => 'Eliminado',       'text' => 'El registro fue eliminado.']
            : ['icon' => 'error',   'title' => 'Error al eliminar','text' => 'No se pudo eliminar el registro.'];
        break;
}

header("Location: ../views/dashboard/control_cultivos.php");
exit;
