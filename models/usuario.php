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
}
