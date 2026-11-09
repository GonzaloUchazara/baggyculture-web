<?php
date_default_timezone_set('America/La_Paz');

$host = "localhost";
$port = "3307";                      
$db   = "baggyculture"; 
$user = "root";                     
$pass = "";                         
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("SET time_zone = '-04:00';");
} catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
