<?php
class Producto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function agregar($nombre, $descripcion, $precio, $stock, $categoria, $calificacion, $estado, $foto_pro) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, calificacion, estado, foto_pro) 
                VALUES (:nombre, :descripcion, :precio, :stock, :categoria, :calificacion, :estado, :foto_pro)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $nombre,
            ':descripcion'  => $descripcion,
            ':precio'       => $precio,
            ':stock'        => $stock,
            ':categoria'    => $categoria,
            ':calificacion' => $calificacion,
            ':estado'       => $estado,
            ':foto_pro'     => $foto_pro
        ]);
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM productos ORDER BY id_producto DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function eliminar($id_producto) {
        $sql = "DELETE FROM productos WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id_producto]);
    }
}