<?php
$host = getenv('DB_HOST') ?: 'mariadb';
$db   = getenv('DB_NAME') ?: 'incidencias';
$user = getenv('DB_USER') ?: 'app_incidencias';
$pass = getenv('DB_PASS') ?: 'clave_ci';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "1. Conexión PDO correcta\n";

    $inicial = (int)$pdo->query("SELECT COUNT(*) FROM incidencias")->fetchColumn();
    echo "2. Registros iniciales: {$inicial}\n";
    if ($inicial !== 3) {
        throw new RuntimeException("Se esperaban 3 registros iniciales");
    }

    $stmt = $pdo->prepare(
        "INSERT INTO incidencias (usuario,equipo,descripcion)
         VALUES (:usuario,:equipo,:descripcion)"
    );
    $stmt->execute([
        ':usuario' => 'CI',
        ':equipo' => 'GitHub Actions',
        ':descripcion' => 'Prueba automática CI'
    ]);
    echo "3. INSERT correcto\n";

    $total = (int)$pdo->query("SELECT COUNT(*) FROM incidencias")->fetchColumn();
    echo "4. Registros tras INSERT: {$total}\n";
    if ($total !== 4) {
        throw new RuntimeException("Se esperaban 4 registros tras el INSERT");
    }

    $fila = $pdo->query(
        "SELECT usuario,equipo,descripcion
         FROM incidencias ORDER BY id DESC LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);

    if (!$fila || $fila['usuario'] !== 'CI' ||
        $fila['equipo'] !== 'GitHub Actions' ||
        $fila['descripcion'] !== 'Prueba automática CI') {
        throw new RuntimeException("El SELECT no devuelve los valores esperados");
    }

    echo "5. SELECT y validación correctos\n";
    echo "RESULTADO: PRUEBA DE INTEGRACIÓN SUPERADA\n";
    exit(0);

} catch (Throwable $e) {
    fwrite(STDERR, "ERROR DE INTEGRACIÓN: ".$e->getMessage().PHP_EOL);
    exit(1);
}