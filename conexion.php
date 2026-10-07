<?php
$host = 'localhost';
$db = 'incidencias';
$user = 'app_incidencias';
$pass = 'Cambia_Esta_Clave_123!';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se ha podido conectar con la base de datos.");
}
?>
