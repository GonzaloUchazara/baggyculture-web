<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Directorio para almacenar imágenes
$uploadDir = dirname(__DIR__) . '/public/uploads/productos/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Función auxiliar para mover archivos subidos
function guardarImagen($file, $uploadDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return null;
    }

    $fileName = uniqid('prod_', true) . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return null;
}

try {
    switch ($action) {

        // -------------------------------------------------------------
        // 1. LISTAR PRODUCTOS (con total de variantes asociadas)
        // -------------------------------------------------------------
        case 'listar':
            $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, 
                           p.categoria, p.calificacion, p.estado, p.foto_pro,
                           COUNT(v.id_variante) AS total_variantes
                    FROM productos p
                    LEFT JOIN variantes_producto v ON p.id_producto = v.id_producto
                    GROUP BY p.id_producto
                    ORDER BY p.id_producto DESC";
            
            $stmt = $pdo->query($sql);
            $productos = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $productos]);
            break;

        // -------------------------------------------------------------
        // 2. OBTENER DETALLE DE UN PRODUCTO Y SUS VARIANTES
        // -------------------------------------------------------------
        case 'obtener':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
                exit;
            }

            // Datos base del producto
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
            $stmt->execute([$id]);
            $producto = $stmt->fetch();

            if (!$producto) {
                echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado']);
                exit;
            }

            // Variantes asociadas
            $stmtVar = $pdo->prepare("SELECT * FROM variantes_producto WHERE id_producto = ?");
            $stmtVar->execute([$id]);
            $producto['variantes'] = $stmtVar->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $producto]);
            break;

        // -------------------------------------------------------------
        // 3. GUARDAR / EDITAR PRODUCTO CON TRANSACCIÓN PDO
        // -------------------------------------------------------------
        case 'guardar':
            $id_producto  = !empty($_POST['id_producto']) ? (int)$_POST['id_producto'] : null;
            $nombre       = trim($_POST['nombre'] ?? '');
            $descripcion  = trim($_POST['descripcion'] ?? '');
            $precio       = (int)($_POST['precio'] ?? 0);
            $stock        = (int)($_POST['stock'] ?? 0);
            $categoria    = trim($_POST['categoria'] ?? '');
            $estado       = $_POST['estado'] ?? 'activo';
            $calificacion = 5.00; // Calificación base inicial predeterminada

            if (empty($nombre) || $precio <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Nombre y precio válido son obligatorios']);
                exit;
            }

            $pdo->beginTransaction();

            if ($id_producto) {
                // Modo Edición
                $foto_pro = null;
                if (!empty($_FILES['foto_pro']['name'])) {
                    $foto_pro = guardarImagen($_FILES['foto_pro'], $uploadDir);
                }

                if ($foto_pro) {
                    $sqlUpdate = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria = ?, estado = ?, foto_pro = ? WHERE id_producto = ?";
                    $stmt = $pdo->prepare($sqlUpdate);
                    $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria, $estado, $foto_pro, $id_producto]);
                } else {
                    $sqlUpdate = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria = ?, estado = ? WHERE id_producto = ?";
                    $stmt = $pdo->prepare($sqlUpdate);
                    $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria, $estado, $id_producto]);
                }
            } else {
                // Modo Inserción
                $foto_pro = guardarImagen($_FILES['foto_pro'] ?? null, $uploadDir) ?? 'uploads/productos/default.webp';

                $sqlInsert = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, calificacion, estado, foto_pro) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sqlInsert);
                $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria, $calificacion, $estado, $foto_pro]);
                $id_producto = (int)$pdo->lastInsertId();
            }

            
            if (isset($_POST['tallas']) && is_array($_POST['tallas'])) {
                $tallas = $_POST['tallas'];
                $colores = $_POST['colores'] ?? [];

                // 1. Mapeo de fotos por COLOR (para que diferentes tallas del mismo color compartan foto1_pro y foto2_pro)
                $fotosPorColor = [];

                foreach ($tallas as $index => $talla) {
                    $colorVal = trim($colores[$index] ?? '');
                    $colorKey = strtolower($colorVal);
                    if (empty($colorKey)) continue;

                    if (!isset($fotosPorColor[$colorKey])) {
                        $foto1 = null;
                        $foto2 = null;

                        if ($id_producto) {
                            $stmtExistColor = $pdo->prepare("SELECT foto1_pro, foto2_pro FROM variantes_producto WHERE id_producto = ? AND LOWER(TRIM(color)) = ? LIMIT 1");
                            $stmtExistColor->execute([$id_producto, $colorKey]);
                            $rowColor = $stmtExistColor->fetch(PDO::FETCH_ASSOC);
                            if ($rowColor) {
                                $foto1 = $rowColor['foto1_pro'];
                                $foto2 = $rowColor['foto2_pro'];
                            }
                        }

                        $fotosPorColor[$colorKey] = [
                            'foto1' => $foto1 ?: 'default.jpg',
                            'foto2' => $foto2 ?: 'default.jpg'
                        ];
                    }

                    if (isset($_FILES['variantes_foto1']['name'][$index]) && !empty($_FILES['variantes_foto1']['name'][$index])) {
                        $f1 = [
                            'name'     => $_FILES['variantes_foto1']['name'][$index],
                            'type'     => $_FILES['variantes_foto1']['type'][$index],
                            'tmp_name' => $_FILES['variantes_foto1']['tmp_name'][$index],
                            'error'    => $_FILES['variantes_foto1']['error'][$index],
                            'size'     => $_FILES['variantes_foto1']['size'][$index]
                        ];
                        $saved1 = guardarImagen($f1, $uploadDir);
                        if ($saved1) $fotosPorColor[$colorKey]['foto1'] = $saved1;
                    }

                    if (isset($_FILES['variantes_foto2']['name'][$index]) && !empty($_FILES['variantes_foto2']['name'][$index])) {
                        $f2 = [
                            'name'     => $_FILES['variantes_foto2']['name'][$index],
                            'type'     => $_FILES['variantes_foto2']['type'][$index],
                            'tmp_name' => $_FILES['variantes_foto2']['tmp_name'][$index],
                            'error'    => $_FILES['variantes_foto2']['error'][$index],
                            'size'     => $_FILES['variantes_foto2']['size'][$index]
                        ];
                        $saved2 = guardarImagen($f2, $uploadDir);
                        if ($saved2) $fotosPorColor[$colorKey]['foto2'] = $saved2;
                    }
                }

                // 2. Guardar o actualizar cada combinación (talla, color) asignando las fotos de su color
                foreach ($tallas as $index => $talla) {
                    $tallaVal = trim($talla);
                    $colorVal = trim($colores[$index] ?? '');
                    $colorKey = strtolower($colorVal);

                    if (empty($tallaVal)) continue;

                    $foto1_pro = $fotosPorColor[$colorKey]['foto1'] ?? 'default.jpg';
                    $foto2_pro = $fotosPorColor[$colorKey]['foto2'] ?? 'default.jpg';

                    // Verificar si ya existe esta variante
                    $stmtVarCheck = $pdo->prepare("SELECT id_variante FROM variantes_producto WHERE id_producto = ? AND TRIM(talla) = ? AND LOWER(TRIM(color)) = ? LIMIT 1");
                    $stmtVarCheck->execute([$id_producto, $tallaVal, $colorKey]);
                    $varExistente = $stmtVarCheck->fetch(PDO::FETCH_ASSOC);

                    if ($varExistente) {
                        $stmtVarUpd = $pdo->prepare("UPDATE variantes_producto SET foto1_pro = ?, foto2_pro = ? WHERE id_variante = ?");
                        $stmtVarUpd->execute([$foto1_pro, $foto2_pro, $varExistente['id_variante']]);
                    } else {
                        $stmtVarInsert = $pdo->prepare("INSERT INTO variantes_producto (id_producto, talla, color, foto1_pro, foto2_pro) VALUES (?, ?, ?, ?, ?)");
                        $stmtVarInsert->execute([$id_producto, $tallaVal, $colorVal, $foto1_pro, $foto2_pro]);
                    }
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Producto guardado correctamente']);
            break;

       
        case 'cambiar_estado':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nuevoEstado = $_POST['estado'] ?? '';

            if (!$id || !in_array($nuevoEstado, ['activo', 'desactivado', 'agotado'])) {
                echo json_encode(['status' => 'error', 'message' => 'Parámetros inválidos']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE productos SET estado = ? WHERE id_producto = ?");
            $stmt->execute([$nuevoEstado, $id]);

            echo json_encode(['status' => 'success', 'message' => 'Estado actualizado a ' . $nuevoEstado]);
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