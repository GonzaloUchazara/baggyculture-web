<?php

class Usuario {
    private $db;
    private $table = 'usuarios'; 

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function emailExiste($email) {
        $sql = "SELECT id_usuario FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function registrar($nombre, $apellido, $email, $password, $telefono = null, $rol = 'cliente') {
        $sql = "INSERT INTO " . $this->table . " (nombre, apellido, email, password_hash, telefono, rol) 
                VALUES (:nombre, :apellido, :email, :password_hash, :telefono, :rol)";
        $stmt = $this->db->prepare($sql);

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function obtenerPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email OR nombre = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'nombre' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_usuario) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPassword($id_usuario, $nuevo_hash) {
        $sql = "UPDATE " . $this->table . " SET password_hash = :password_hash WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':password_hash' => $nuevo_hash,
            ':id_usuario' => $id_usuario
        ]);
    }

    public function actualizarFotoPerfil($id_usuario, $nombre_archivo) {
        try {
            $sql = "UPDATE " . $this->table . " SET foto_perfil = :foto WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':foto' => $nombre_archivo,
                ':id'   => $id_usuario
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar foto: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarDatos($id_usuario, $datos) {
        if (empty($datos)) {
            return true;
        }
        $setParts = [];
        $params = [':id' => $id_usuario];
        foreach ($datos as $col => $val) {
            $setParts[] = "`$col` = :$col";
            $params[":$col"] = $val;
        }
        $sql = "UPDATE " . $this->table . " SET " . implode(', ', $setParts) . " WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function actualizarDatosCompletos($id_usuario, $nombre, $apellido, $telefono, $nuevoHash = null) {
        $datos = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono
        ];
        if (!empty($nuevoHash)) {
            $datos['password_hash'] = $nuevoHash;
        }
        return $this->actualizarDatos($id_usuario, $datos);
    }
}
