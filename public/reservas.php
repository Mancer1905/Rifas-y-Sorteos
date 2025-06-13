<?php
// filepath: Rifas-y-Sorteos/public/reservas.php
$id = $_GET['id'] ?? '';
if (!$id) { header('Location: index.php'); exit; }

$rifas = file_exists('../data/rifas.json') ? json_decode(file_get_contents('../data/rifas.json'), true) : [];
$rifa = null;
foreach ($rifas as $r) if ($r['id'] === $id) $rifa = $r;
if (!$rifa) { header('Location: index.php'); exit; }

$reservaFile = "../data/reservas/reservas_$id.json";
if (!file_exists($reservaFile)) { header('Location: numeros.php?id='.$id); exit; }
$reservas = json_decode(file_get_contents($reservaFile), true);
$cantidad = $reservas['cantidad'];
$numeros = $reservas['numeros'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero'], $_POST['nombre'], $_POST['telefono'])) {
    $num = intval($_POST['numero']);
    if ($num >= 1 && $num <= $cantidad && $numeros[$num] === null) {
        $numeros[$num] = [
            'nombre' => trim($_POST['nombre']),
            'telefono' => trim($_POST['telefono'])
        ];
        $reservas['numeros'] = $numeros;
        file_put_contents($reservaFile, json_encode($reservas, JSON_PRETTY_PRINT));
        header("Location: reservas.php?id=$id");
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero'])) {
    $num = intval($_POST['numero']);
    $accion = $_POST['accion'] ?? '';
    if ($num >= 1 && $num <= $cantidad) {
        if ($accion === 'liberar' && !empty($numeros[$num]) && empty($numeros[$num]['pagado'])) {
            $numeros[$num] = null;
        }
        if ($accion === 'pagar' && !empty($numeros[$num]) && empty($numeros[$num]['pagado'])) {
            $numeros[$num]['pagado'] = true;
        }
        $reservas['numeros'] = $numeros;
        file_put_contents($reservaFile, json_encode($reservas, JSON_PRETTY_PRINT));
        header("Location: reservas.php?id=$id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($rifa['nombre']) ?></title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="reserva-header">
        <?php if ($rifa['premio_imagen']): ?>
            <img src="img/<?= htmlspecialchars($rifa['premio_imagen']) ?>" class="premio-img" alt="Premio">
        <?php endif; ?>
        <div class="premio-info">
            <div class="premio-nombre"><?= htmlspecialchars($rifa['premio_nombre']) ?></div>
        </div>
        <div class="rifa-meta">
            <div><?= htmlspecialchars($rifa['entidad']) ?></div>
            <div><?= htmlspecialchars($rifa['fecha']) ?></div>
        </div>
    </div>
    <h1 style="text-align:center;"><?= htmlspecialchars($rifa['nombre']) ?></h1>
    <div class="cuadricula-container">
    <table class="numeros-grid">
        <?php
        $perRow = ceil(sqrt($cantidad));
        for ($i = 1; $i <= $cantidad; $i++):
            if (($i-1) % $perRow === 0) echo "<tr>";
            $info = $numeros[$i];
            if (!$info) {
                $class = 'free';
            } elseif (!empty($info['pagado'])) {
                $class = 'paid';
            } else {
                $class = 'reserved-pending';
            }
        ?>
            <td class="<?= $class ?>">
                <div style="font-weight:bold;"><?= $i ?></div>
                <?php if (!$info): ?>
                    <form method="POST" style="margin:0;">
                        <input type="hidden" name="numero" value="<?= $i ?>">
                        <input type="text" name="nombre" placeholder="Nombre" required style="width:80px;">
                        <input type="text" name="telefono" placeholder="Teléfono" required style="width:80px;">
                        <button type="submit" class="send-btn">Reservar</button>
                    </form>
                <?php elseif (!empty($info['pagado'])): ?>
                    <div>Pagado</div>
                <?php else: ?>
                    <div><?= htmlspecialchars($info['nombre']) ?></div>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="numero" value="<?= $i ?>">
                        <input type="hidden" name="accion" value="liberar">
                        <button type="submit" class="action-btn cross" title="Liberar">&#10060;</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="numero" value="<?= $i ?>">
                        <input type="hidden" name="accion" value="pagar">
                        <button type="submit" class="action-btn check" title="Marcar como pagado">&#10004;</button>
                    </form>
                <?php endif; ?>
            </td>
        <?php if ($i % $perRow === 0) echo "</tr>"; endfor; ?>
    </table>
    </div>
    <div class="acciones-final">
        <a href="index.php" class="send-btn">Volver a principal</a>
        <a href="cerrar_rifa.php?id=<?= $id ?>" class="send-btn" style="background:#fc5c5c;">Cerrar rifa</a>
    </div>
</body>
</html>