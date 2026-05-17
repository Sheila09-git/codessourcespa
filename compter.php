<?php
require_once 'db.php';
$sqlTotal = "SELECT COUNT(*) AS total FROM produit";
$resTotal = $pdo->query($sqlTotal)->fetch(PDO::FETCH_ASSOC);
$sqlDisp = "SELECT COUNT(*) AS disponible FROM produit WHERE disponible = 1";
$resDisp = $pdo->query($sqlDisp)->fetch(PDO::FETCH_ASSOC);


echo json_encode([
    "total" => $resTotal['total'],
    "disponible" => $resDisp['disponible']
]);
