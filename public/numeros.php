<?php
// filepath: Rifas-y-Sorteos/public/numeros.php
$id = $_GET['id'] ?? '';
if (!$id) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cantidad'])) {
    $cantidad = intval($_POST['cantidad']);
    file_put_contents("../data/reservas/reservas_$id.json", json_encode([
        'cantidad' => $cantidad,
        'numeros' => array_fill(1, $cantidad, null)
    ], JSON_PRETTY_PRINT));
    header("Location: reservas.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cantidad de Números</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="centered">
        <form method="POST">
            <label for="cantidad">¿Cuántos números necesita?</label>
            <input type="number" id="cantidad" name="cantidad" required min="1">
            <button type="submit" class="send-btn">Continuar</button>
        </form>
    </div>
</body>
</html>