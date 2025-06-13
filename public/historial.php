<?php
// filepath: Rifas-y-Sorteos/public/historial.php
$rifas = file_exists('../data/rifas.json') ? json_decode(file_get_contents('../data/rifas.json'), true) : [];
$cerradas = array_filter($rifas, fn($r) => $r['estado'] === 'cerrada');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Rifas</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <h1>Historial de Rifas</h1>
    <a href="index.php" class="send-btn">Volver a principal</a>
    <div class="rifas-list">
        <?php foreach ($cerradas as $rifa): ?>
            <div class="rifa-card">
                <strong><?= htmlspecialchars($rifa['nombre']) ?></strong><br>
                Premio: <?= htmlspecialchars($rifa['premio_nombre']) ?><br>
                Día: <?= htmlspecialchars($rifa['fecha']) ?><br>
                Entidad: <?= htmlspecialchars($rifa['entidad']) ?>
            </div>
        <?php endforeach; ?>
        <?php if (empty($cerradas)): ?>
            <p>No hay rifas cerradas.</p>
        <?php endif; ?>
    </div>
</body>
</html>