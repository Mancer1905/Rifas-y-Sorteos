<?php
// filepath: Rifas-y-Sorteos/public/index.php
$rifas = [];
if (file_exists('../data/rifas.json')) {
    $json = file_get_contents('../data/rifas.json');
    $rifas = json_decode($json, true);
    if (!is_array($rifas)) $rifas = [];
}
$activas = array_filter($rifas, fn($r) => $r['estado'] === 'activa');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rifas Activas</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="top-bar">
        <button onclick="window.location='historial.php'">Ver historial de rifas</button>
        <button onclick="window.location='nueva_rifa.php'">Añadir nueva rifa</button>
    </div>
    <h1>Rifas Activas</h1>
    <div class="rifas-list">
        <?php foreach ($activas as $rifa): ?>
            <div class="rifa-card">
                <?php if (!empty($rifa['premio_imagen'])): ?>
                    <img src="img/<?= htmlspecialchars($rifa['premio_imagen']) ?>" class="premio-img" alt="Premio">
                <?php endif; ?>
                <strong><?= htmlspecialchars($rifa['nombre']) ?></strong><br>
                Premio: <?= htmlspecialchars($rifa['premio_nombre']) ?><br>
                <a href="reservas.php?id=<?= $rifa['id'] ?>">Ver/Reservar</a>
            </div>
        <?php endforeach; ?>
        <?php if (empty($activas)): ?>
            <p>No hay rifas activas.</p>
        <?php endif; ?>
    </div>
</body>
</html>