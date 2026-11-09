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
}
