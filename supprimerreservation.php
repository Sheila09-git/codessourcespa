<?php
require_once 'db.php';
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit();
}
$id = $_GET['id'];

$sql = "DELETE FROM reservation WHERE id_reservation = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([
    $id
]);
if (!$success) {
    http_response_code(404);
    exit();
}
