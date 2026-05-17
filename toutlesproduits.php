<?php
require_once 'db.php';
$sql = "SELECT p.*, GROUP_CONCAT(c.nom SEPARATOR ', ') as categories 
        FROM produit p
        LEFT JOIN produit_categorie pc ON p.id_produit = pc.produit_id
        LEFT JOIN categorie c ON pc.categorie_id = c.id_categorie
        GROUP BY p.id_produit
        ORDER BY p.id_produit DESC";

$stmt = $pdo->query($sql);
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($plats);
