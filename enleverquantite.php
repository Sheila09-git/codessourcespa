<?php
require_once 'db.php';
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit();
}
$id = $_GET['id'];

$sql = "UPDATE produit SET quantite=quantite-1 WHERE id_produit = ? AND quantite>0";



$stmt = $pdo->prepare($sql);
$success = $stmt->execute([
    $id
]);
if (!$success) {
    http_response_code(404);
    exit();
}
