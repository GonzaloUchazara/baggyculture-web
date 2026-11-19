<?php
header('Content-Type: application/json; charset=utf-8');

// Incluir conexión PDO
require_once __DIR__ . '/../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {

        // -------------------------------------------------------------
        // 1. LISTAR PEDIDOS Y CALCULAR KPIS
        // -------------------------------------------------------------
        case 'listar':
            // Consulta de pedidos con datos del cliente
            $sql = "SELECT p.id_pedido, p.id_usuario, p.total, p.estado, 
                           p.metodo_pago, p.direccion_envio, p.fecha_pedido,
                           CONCAT(u.nombre, ' ', u.apellido) AS cliente_nombre,
                           u.telefono, u.email
                    FROM pedidos p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    ORDER BY p.fecha_pedido DESC";
            
            $stmt = $pdo->query($sql);
            $pedidos = $stmt->fetchAll();

            // Cálculo de métricas KPI
            $kpiTotalVentas = 0.00;
            $kpiPendientes = 0;
            $kpiEntregados = 0;
            $kpiCancelados = 0;

            foreach ($pedidos as $p) {
                if ($p['estado'] !== 'cancelado') {
                    $kpiTotalVentas += (float)$p['total'];
                }
                if ($p['estado'] === 'pendiente') {
                    $kpiPendientes++;
                } elseif ($p['estado'] === 'entregado') {
                    $kpiEntregados++;
                } elseif ($p['estado'] === 'cancelado') {
                    $kpiCancelados++;
                }
            }

            echo json_encode([
                'status' => 'success',
                'data' => $pedidos,
                'kpis' => [
                    'total_ventas' => number_format($kpiTotalVentas, 2, '.', ''),
                    'pendientes'   => $kpiPendientes,
                    'entregados'   => $kpiEntregados,
                    'cancelados'   => $kpiCancelados
                ]
            ]);
            break;

        // -------------------------------------------------------------
        // 2. OBTENER DETALLE COMPLETO DE UNA ORDEN
        // -------------------------------------------------------------
        case 'obtener_detalle':
            $id_pedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id_pedido) {
                echo json_encode(['status' => 'error', 'message' => 'ID de pedido inválido']);
                exit;
            }

            // Datos de cabecera del pedido y comprador
            $sqlPedido = "SELECT p.*, CONCAT(u.nombre, ' ', u.apellido) AS cliente_nombre, u.telefono, u.email
                          FROM pedidos p
                          INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                          WHERE p.id_pedido = ?";
            $stmtPedido = $pdo->prepare($sqlPedido);
            $stmtPedido->execute([$id_pedido]);
            $pedido = $stmtPedido->fetch();

            if (!$pedido) {
                echo json_encode(['status' => 'error', 'message' => 'Pedido no encontrado']);
                exit;
            }

            // Desglose de ítems comprados
            $sqlItems = "SELECT d.id_detalle, d.cantidad, d.precio_unitario,
                                (d.cantidad * d.precio_unitario) AS subtotal,
                                pr.nombre AS producto_nombre,
                                v.talla, v.color,
                                COALESCE(v.foto1_pro, pr.foto_pro) AS foto
                         FROM detalle_pedidos d
                         INNER JOIN variantes_producto v ON d.id_variante = v.id_variante
                         INNER JOIN productos pr ON v.id_producto = pr.id_producto
                         WHERE d.id_pedido = ?";
            
            $stmtItems = $pdo->prepare($sqlItems);
            $stmtItems->execute([$id_pedido]);
            $pedido['items'] = $stmtItems->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $pedido]);
            break;

        // -------------------------------------------------------------
        // 3. CAMBIAR ESTADO DEL PEDIDO (CON CONTROL DE STOCK EN CANCELACIÓN)
        // -------------------------------------------------------------
        case 'cambiar_estado':
            $id_pedido    = filter_input(INPUT_POST, 'id_pedido', FILTER_VALIDATE_INT);
            $nuevo_estado = trim($_POST['estado'] ?? '');

            $estadosValidos = ['pendiente', 'en_camino', 'entregado', 'cancelado'];
            if (!$id_pedido || !in_array($nuevo_estado, $estadosValidos)) {
                echo json_encode(['status' => 'error', 'message' => 'Parámetros no válidos']);
                exit;
            }

            $pdo->beginTransaction();

            // 1. Obtener estado previo
            $stmtCheck = $pdo->prepare("SELECT estado FROM pedidos WHERE id_pedido = ? FOR UPDATE");
            $stmtCheck->execute([$id_pedido]);
            $pedidoActual = $stmtCheck->fetch();

            if (!$pedidoActual) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'El pedido no existe']);
                exit;
            }

            $estadoAnterior = $pedidoActual['estado'];

            
            if ($nuevo_estado === 'cancelado' && $estadoAnterior !== 'cancelado') {
                $stmtItems = $pdo->prepare("SELECT v.id_producto, d.cantidad 
                                            FROM detalle_pedidos d
                                            INNER JOIN variantes_producto v ON d.id_variante = v.id_variante
                                            WHERE d.id_pedido = ?");
                $stmtItems->execute([$id_pedido]);
                $items = $stmtItems->fetchAll();

                $stmtRestaurar = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id_producto = ?");
                foreach ($items as $item) {
                    $stmtRestaurar->execute([$item['cantidad'], $item['id_producto']]);
                }
            }

            
            if ($estadoAnterior === 'cancelado' && $nuevo_estado !== 'cancelado') {
                $stmtItems = $pdo->prepare("SELECT v.id_producto, d.cantidad 
                                            FROM detalle_pedidos d
                                            INNER JOIN variantes_producto v ON d.id_variante = v.id_variante
                                            WHERE d.id_pedido = ?");
                $stmtItems->execute([$id_pedido]);
                $items = $stmtItems->fetchAll();

                $stmtDescontar = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
                foreach ($items as $item) {
                    $stmtDescontar->execute([$item['cantidad'], $item['id_producto']]);
                }
            }

            
            $stmtUpdate = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id_pedido = ?");
            $stmtUpdate->execute([$nuevo_estado, $id_pedido]);

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Estado actualizado a ' . $nuevo_estado]);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
            break;
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}