<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/subasta.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin/subastador.php');
    exit;
}

$modelo = new subasta($pdo);
$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'actualizar':
    case 'editar':
        $id_subasta      = (int)($_POST['id_subasta'] ?? 0);
        $precio_base     = (float)($_POST['precio_base'] ?? 0);
        $fecha_inicio    = $_POST['fecha_inicio'] ?? '';
        $fecha_fin       = $_POST['fecha_fin'] ?? '';
        $estado          = $_POST['estado'] ?? 'activa';
        $producto_nombre = trim($_POST['producto_nombre'] ?? '');

        if ($id_subasta > 0 && !empty($fecha_inicio) && !empty($fecha_fin)) {
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($fecha_inicio));
            $fecha_fin    = date('Y-m-d H:i:s', strtotime($fecha_fin));

            $modelo->actualizarSubastaAdmin($id_subasta, $precio_base, $fecha_inicio, $fecha_fin, $estado, $producto_nombre);
        }
        break;

    case 'extender':
        $id_subasta = (int)($_POST['id_subasta'] ?? 0);
        $minutos    = (int)($_POST['minutos_extra'] ?? 0);

        if ($id_subasta > 0 && $minutos > 0) {
            $modelo->extenderTiempoMinutos($id_subasta, $minutos);
        }
        break;

    case 'toggle_estado':
    case 'toggle-estado':
        $id_subasta   = (int)($_POST['id_subasta'] ?? 0);
        $nuevo_estado = $_POST['nuevo_estado'] ?? 'activa';

        if ($id_subasta > 0 && in_array($nuevo_estado, ['activa', 'finalizada', 'cancelada'])) {
            $modelo->cambiarEstado($id_subasta, $nuevo_estado);
        }
        break;


    case 'crear':
        $id_producto   = (int)($_POST['id_producto'] ?? 0);
        $precio_base   = (float)($_POST['precio_base'] ?? 0);
        $inc_minimo    = (float)($_POST['incremento_minimo'] ?? 5.00);
        $fecha_inicio  = $_POST['fecha_inicio'] ?? '';
        $fecha_fin     = $_POST['fecha_fin'] ?? '';

        if ($id_producto > 0 && $precio_base > 0 && !empty($fecha_inicio) && !empty($fecha_fin)) {
            $fecha_inicio = str_replace('T', ' ', $fecha_inicio);
            $fecha_fin    = str_replace('T', ' ', $fecha_fin);

            $modelo->crearSubasta($id_producto, $precio_base, $inc_minimo, $fecha_inicio, $fecha_fin); //
        }
        break;
}

header('Location: ../views/admin/subastador.php?msg=ok');
exit;