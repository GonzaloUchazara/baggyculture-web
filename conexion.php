<?php
$host = "localhost";
$port = "3307";                      
$db   = "baggy_culture"; 
$user = "root";                     
$pass = "";                         
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>