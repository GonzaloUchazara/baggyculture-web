<?php
class subasta{
    private $pdo;
    
    public function __construct($conexion){
        $this->pdo = $conexion;
    }

    public function obtenersubastasactivas(){
        $sql = "SELECT s.*, p.nombre, p.descripcion, p.precio AS precio_original, p.foto_pro, p.foto_pro AS foto,
                       (SELECT COUNT(*) FROM pujas pu WHERE pu.id_subasta = s.id_subasta) AS total_pujas
                FROM subastas s
                INNER JOIN productos p ON s.id_producto = p.id_producto
                WHERE s.estado = 'activa' AND s.fecha_fin > NOW()
                ORDER BY s.fecha_fin ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerporid($id_subasta) {
        $sql = "SELECT s.*, p.nombre, p.descripcion, p.precio ,p.categoria AS precio_original, p.foto_pro, p.foto_pro AS foto, u.nombre AS nombre_ganador
                FROM subastas s
                INNER JOIN productos p ON s.id_producto = p.id_producto
                LEFT JOIN usuarios u ON s.id_ganador = u.id_usuario
                WHERE s.id_subasta = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_subasta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function obtenerHistorialPujas($id_subasta, $limite = 5) {
        $sql = "SELECT pu.monto, pu.fecha_puja, u.nombre AS usuario
                FROM pujas pu
                INNER JOIN usuarios u ON pu.id_usuario = u.id_usuario
                WHERE pu.id_subasta = :id
                ORDER BY pu.monto DESC, pu.fecha_puja DESC
                LIMIT :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id_subasta, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function registrarPuja($id_subasta, $id_usuario, $monto) {
        try {
            $this->pdo->beginTransaction();

            // bloquear subasta con for update 
            $sqlCheck = "SELECT precio_actual, incremento_minimo, fecha_fin, estado 
                         FROM subastas 
                         WHERE id_subasta = :id FOR UPDATE";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $id_subasta]);
            $subasta = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$subasta || $subasta['estado'] !== 'activa') {
                $this->pdo->rollBack();
                return ['success' => false, 'mensaje' => 'La subasta no está disponible.'];
            }

            if (new DateTime() > new DateTime($subasta['fecha_fin'])) {
                // finalizado si acabo 
                $this->pdo->prepare("UPDATE subastas SET estado = 'finalizada' WHERE id_subasta = :id")
                          ->execute([':id' => $id_subasta]);
                $this->pdo->commit();
                return ['success' => false, 'mensaje' => 'El tiempo de la subasta ha concluido tristemente.'];
            }

            $minimoRequerido = $subasta['precio_actual'] + $subasta['incremento_minimo'];
            if ($monto < $minimoRequerido) {
                $this->pdo->rollBack();
                return ['success' => false, 'mensaje' => "El monto mínimo permitido es de Bs. " . number_format($minimoRequerido, 2)];
            }

            // insertar pujas trigers
            // 1. 'actualizar_subasta_tras_puja' -> actualiza precio_actual e id_ganador en 'subastas'.
            // 2. 'auditoria_subasta_precio' -> registra el cambio en 'auditoria_subastas'.
            $sqlInsert = "INSERT INTO pujas (id_subasta, id_usuario, monto) 
                          VALUES (:id_subasta, :id_usuario, :monto)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                ':id_subasta' => $id_subasta,
                ':id_usuario' => $id_usuario,
                ':monto'      => $monto
            ]);

            $this->pdo->commit();
            return [
                'success' => true, 
                'nuevo_precio' => number_format($monto, 2),
                'mensaje' => '¡Puja realizada con éxito!'
            ];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'mensaje' => 'Error al procesar la oferta: intentelo de nuevo ' . $e->getMessage()];
        }
    }

    public function obtenerTodas() {
        $sql = "SELECT s.*, 
                       p.nombre AS producto_nombre, 
                       p.foto_pro AS producto_foto, 
                       p.precio AS producto_precio, 
                       p.categoria AS producto_categoria,
                       u.nombre AS ganador_nombre,
                       (SELECT COUNT(*) FROM pujas pu WHERE pu.id_subasta = s.id_subasta) AS total_pujas
                FROM subastas s
                INNER JOIN productos p ON s.id_producto = p.id_producto
                LEFT JOIN usuarios u ON s.id_ganador = u.id_usuario
                ORDER BY s.id_subasta DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticas() {
        $stats = [
            'activas'     => 0,
            'total_pujas' => 0,
            'puja_maxima' => 0.00,
            'finalizadas' => 0
        ];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM subastas WHERE estado = 'activa' AND fecha_fin > NOW()");
        $stats['activas'] = (int) $stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*), COALESCE(MAX(monto), 0) FROM pujas");
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stats['total_pujas'] = (int) ($row[0] ?? 0);
        $stats['puja_maxima'] = (float) ($row[1] ?? 0.00);

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM subastas WHERE estado = 'finalizada'");
        $stats['finalizadas'] = (int) $stmt->fetchColumn();

        return $stats;
    }

    public function crearSubasta($id_producto, $precio_base, $incremento_minimo, $fecha_inicio, $fecha_fin) {
        $sql = "INSERT INTO subastas (id_producto, precio_base, precio_actual, incremento_minimo, fecha_inicio, fecha_fin, estado) 
                VALUES (:prod, :base, :base, :inc, :inicio, :fin, 'activa')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':prod'   => $id_producto,
            ':base'   => $precio_base,
            ':inc'    => $incremento_minimo,
            ':inicio' => $fecha_inicio,
            ':fin'    => $fecha_fin
        ]);
    }

    public function editarSubasta($id_subasta, $precio_base, $incremento_minimo, $fecha_inicio, $fecha_fin, $estado) {
        $sql = "UPDATE subastas 
                SET precio_base = :base, 
                    incremento_minimo = :inc, 
                    fecha_inicio = :inicio, 
                    fecha_fin = :fin, 
                    estado = :estado 
                WHERE id_subasta = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':base'   => $precio_base,
            ':inc'    => $incremento_minimo,
            ':inicio' => $fecha_inicio,
            ':fin'    => $fecha_fin,
            ':estado' => $estado,
            ':id'     => $id_subasta
        ]);
    }

    public function actualizarSubastaAdmin($id_subasta, $precio_base, $fecha_inicio, $fecha_fin, $estado, $producto_nombre = '') {
        try {
            if (!empty($producto_nombre)) {
                $sqlProd = "UPDATE productos p
                            INNER JOIN subastas s ON p.id_producto = s.id_producto
                            SET p.nombre = :nombre
                            WHERE s.id_subasta = :id";
                $stmtProd = $this->pdo->prepare($sqlProd);
                $stmtProd->execute([
                    ':nombre' => $producto_nombre,
                    ':id'     => $id_subasta
                ]);
            }

            $stmtPujas = $this->pdo->prepare("SELECT COUNT(*) FROM pujas WHERE id_subasta = :id");
            $stmtPujas->execute([':id' => $id_subasta]);
            $totalPujas = (int) $stmtPujas->fetchColumn();

            if ($totalPujas === 0) {
                $sql = "UPDATE subastas 
                        SET precio_base = :base, 
                            precio_actual = :base,
                            fecha_inicio = :inicio, 
                            fecha_fin = :fin, 
                            estado = :estado 
                        WHERE id_subasta = :id";
            } else {
                $sql = "UPDATE subastas 
                        SET precio_base = :base, 
                            fecha_inicio = :inicio, 
                            fecha_fin = :fin, 
                            estado = :estado 
                        WHERE id_subasta = :id";
            }

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':base'   => $precio_base,
                ':inicio' => $fecha_inicio,
                ':fin'    => $fecha_fin,
                ':estado' => $estado,
                ':id'     => $id_subasta
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function cambiarEstado($id_subasta, $nuevo_estado) {
        if ($nuevo_estado === 'activa') {
            $sql = "UPDATE subastas 
                    SET estado = 'activa',
                        fecha_fin = CASE WHEN fecha_fin <= NOW() THEN DATE_ADD(NOW(), INTERVAL 1 HOUR) ELSE fecha_fin END
                    WHERE id_subasta = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id_subasta]);
        }

        $sql = "UPDATE subastas SET estado = :estado WHERE id_subasta = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':estado' => $nuevo_estado,
            ':id'     => $id_subasta
        ]);
    }

    public function extenderTiempoMinutos($id_subasta, $minutos) {
        $minutos = (int)$minutos;
        if ($minutos <= 0) return false;

        $sql = "UPDATE subastas 
                SET fecha_fin = DATE_ADD(CASE WHEN fecha_fin > NOW() THEN fecha_fin ELSE NOW() END, INTERVAL :minutos MINUTE),
                    estado = 'activa' 
                WHERE id_subasta = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':minutos' => $minutos,
            ':id'      => $id_subasta
        ]);
    }

    public function extenderTiempo($id_subasta, $fecha_fin) {
        $sql = "UPDATE subastas SET fecha_fin = :fin WHERE id_subasta = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':fin' => $fecha_fin,
            ':id'  => $id_subasta
        ]);
    }

    public function eliminarSubasta($id_subasta) {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("DELETE FROM auditoria_subastas WHERE id_subasta = :id");
            $stmt->execute([':id' => $id_subasta]);

            
            $stmt = $this->pdo->prepare("DELETE FROM pujas WHERE id_subasta = :id");
            $stmt->execute([':id' => $id_subasta]);

            
            $stmt = $this->pdo->prepare("DELETE FROM subastas WHERE id_subasta = :id");
            $stmt->execute([':id' => $id_subasta]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }




    public function obtenerAuditoriaGeneral() {
        $sql = "SELECT a.*, 
                       COALESCE(p.nombre, (SELECT pr.nombre FROM productos pr WHERE pr.id_producto = JSON_UNQUOTE(JSON_EXTRACT(COALESCE(a.datos_nuevos, a.datos_anteriores), '$.id_producto')))) AS producto_nombre
                FROM auditoria a
                LEFT JOIN subastas s ON a.clave_registro = s.id_subasta
                LEFT JOIN productos p ON s.id_producto = p.id_producto
                WHERE a.nombre_tabla = 'subastas'
                ORDER BY a.id_auditoria DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerAuditoriaSubastas() {
        $sql = "SELECT a.id_auditoria,
                       a.id_subasta,
                       a.id_usuario,
                       a.precio_anterior,
                       a.precio_nuevo,
                       a.accion,
                       a.fecha_registro,
                       u.nombre AS usuario_nombre,
                       p.nombre AS producto_nombre
                FROM auditoria_subastas a
                INNER JOIN subastas s ON a.id_subasta = s.id_subasta
                INNER JOIN productos p ON s.id_producto = p.id_producto
                LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
                ORDER BY a.fecha_registro DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function obtenerVentasPorCategoria() {
        
        $sql = "SELECT 
                    COALESCE(p.categoria, 'Sin categoría') AS categoria,
                    COUNT(s.id_subasta) AS total_vendidas,
                    SUM(s.precio_actual) AS total_recaudado
                FROM subastas s
                INNER JOIN productos p ON s.id_producto = p.id_producto
                WHERE s.id_ganador IS NOT NULL 
                  AND s.estado IN ('finalizada', 'activa')
                GROUP BY p.categoria
                ORDER BY total_recaudado DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerTopPostores($limite = 5) {
        $sql = "SELECT 
                    u.nombre, 
                    COUNT(pu.id_puja) AS total_ofertas,
                    COALESCE(SUM(pu.monto), 0) AS volumen_total
                FROM pujas pu
                INNER JOIN usuarios u ON pu.id_usuario = u.id_usuario
                GROUP BY pu.id_usuario, u.nombre
                ORDER BY total_ofertas DESC
                LIMIT :limite";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function obtenerRitmoPujasTiempo($id_subasta = null) {
        
        if ($id_subasta) {
            $sql = "SELECT 
                        DATE_FORMAT(fecha_puja, '%d/%m %H:%i') AS punto_tiempo,
                        monto,
                        id_puja
                    FROM pujas
                    WHERE id_subasta = :id
                    ORDER BY fecha_puja ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id_subasta]);
        } else {
            $sql = "SELECT 
                        DATE_FORMAT(fecha_puja, '%d/%m %H:%i') AS punto_tiempo,
                        monto,
                        id_puja
                    FROM pujas
                    ORDER BY fecha_puja ASC
                    LIMIT 20";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
