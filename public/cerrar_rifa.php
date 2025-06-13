<?php
// filepath: Rifas-y-Sorteos/public/cerrar_rifa.php
$id = $_GET['id'] ?? '';
if (!$id) { header('Location: index.php'); exit; }

$rifas = file_exists('../data/rifas.json') ? json_decode(file_get_contents('../data/rifas.json'), true) : [];
foreach ($rifas as &$r) {
    if ($r['id'] === $id) $r['estado'] = 'cerrada';
}
file_put_contents('../data/rifas.json', json_encode($rifas, JSON_PRETTY_PRINT));
header('Location: index.php');
exit;