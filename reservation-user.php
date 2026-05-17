<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['id_user'])) {
    echo json_encode([]);
    exit;
}

$id_user = $_SESSION['id_user'];
$stmt = $pdo->prepare("SELECT * FROM reservation WHERE id_utilisateur = :id ORDER BY date_reserv DESC");
$stmt->execute(['id' => $id_user]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($reservations);
