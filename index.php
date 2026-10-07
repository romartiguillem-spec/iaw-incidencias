<?php
require 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $equipo = trim($_POST['equipo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($usuario !== '' && $equipo !== '' && $descripcion !== '') {
        $sql = "INSERT INTO incidencias (usuario, equipo, descripcion) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario, $equipo, $descripcion]);
        $mensaje = 'Incidencia registrada correctamente.';
    } else {
        $mensaje = 'Debes completar todos los campos.';
    }
}

$consulta = $pdo->query(
    "SELECT id, usuario, equipo, descripcion, fecha FROM incidencias ORDER BY id DESC"
);
$incidencias = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de incidencias TIC</title>
<link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
<header>
<h1>Gestión de incidencias TIC</h1>
<p>Sistema interno de registro de incidencias</p>
</header>

<section class="panel">
<h2>Registrar nueva incidencia</h2>
<form method="POST" action="">
<label for="usuario">Usuario</label>
<input type="text" id="usuario" name="usuario" required>

<label for="equipo">Equipo afectado</label>
<input type="text" id="equipo" name="equipo" required>

<label for="descripcion">Descripción del problema</label>
<textarea id="descripcion" name="descripcion" rows="5" required></textarea>

<button type="submit">Registrar incidencia</button>
</form>

<?php if ($mensaje !== ''): ?>
<div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>
</section>

<section class="panel">
<h2>Incidencias registradas</h2>
<?php if (count($incidencias) === 0): ?>
<p>Todavía no hay incidencias registradas.</p>
<?php else: ?>
<div class="tabla-responsive">
<table>
<thead>
<tr><th>ID</th><th>Usuario</th><th>Equipo</th><th>Descripción</th><th>Fecha</th></tr>
</thead>
<tbody>
<?php foreach ($incidencias as $incidencia): ?>
<tr>
<td><?= (int)$incidencia['id'] ?></td>
<td><?= htmlspecialchars($incidencia['usuario']) ?></td>
<td><?= htmlspecialchars($incidencia['equipo']) ?></td>
<td><?= htmlspecialchars($incidencia['descripcion']) ?></td>
<td><?= htmlspecialchars($incidencia['fecha']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>
</section>
</div>
</body>
</html>
