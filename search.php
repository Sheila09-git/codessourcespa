<?php
header("Content-Type: application/json");
require "db.php";

$q = "%" . $_GET["q"] . "%";
$stmt = $pdo->prepare("
    SELECT id_produit, nom, description, prix, image 
    FROM produit 
    WHERE nom LIKE ? AND disponible = 1
");
$stmt->execute([$q]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
