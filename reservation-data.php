<?php
session_start();
require_once 'db.php';


$stmt = $pdo->query("SELECT * FROM reservation ORDER BY date_reserv ASC");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($reservations);
