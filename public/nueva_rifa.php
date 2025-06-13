<?php
// filepath: Rifas-y-Sorteos/public/nueva_rifa.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rifas = file_exists('../data/rifas.json') ? json_decode(file_get_contents('../data/rifas.json'), true) : [];
    $id = uniqid();
    $nombre = trim($_POST['nombre']);
    $premio_nombre = trim($_POST['premio_nombre']);
    $premio_imagen = '';
    $fecha = trim($_POST['fecha']);
    $entidad = trim($_POST['entidad']);

    // Guardar imagen si se subió
    if (!empty($_FILES['premio_imagen']['name'])) {
        $ext = pathinfo($_FILES['premio_imagen']['name'], PATHINFO_EXTENSION);
        $premio_imagen = $id . '.' . $ext;
        move_uploaded_file($_FILES['premio_imagen']['tmp_name'], "img/$premio_imagen");
    }

    $rifas[] = [
        'id' => $id,
        'nombre' => $nombre,
        'premio_nombre' => $premio_nombre,
        'premio_imagen' => $premio_imagen,
        'fecha' => $fecha,
        'entidad' => $entidad,
        'estado' => 'activa'
    ];
    file_put_contents('../data/rifas.json', json_encode($rifas, JSON_PRETTY_PRINT));
    header("Location: numeros.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Rifa</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <h1>Crear Nueva Rifa</h1>
    <form method="POST" enctype="multipart/form-data" class="form-nueva-rifa">
        <label>Nombre del sorteo: <input type="text" name="nombre" required></label><br>
        <label>Premio (nombre): <input type="text" name="premio_nombre" required></label><br>
        <label>Premio (imagen, opcional): <input type="file" name="premio_imagen" accept="image/*"></label><br>
        <label>Día del juego: <input type="date" name="fecha" required></label><br>
        <label>Entidad que hará el sorteo: <input type="text" name="entidad" required></label><br>
        <button type="submit" class="send-btn">Continuar</button>
    </form>
</body>
</html>