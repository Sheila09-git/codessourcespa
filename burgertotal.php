<?php
require_once 'db.php';

$sql = "SELECT SUM(quantite) AS total FROM produit";
$statement = $pdo->query($sql);
$result = $statement->fetch(PDO::FETCH_ASSOC);

echo $result['total'];
