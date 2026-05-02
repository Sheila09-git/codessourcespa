<?php
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM produit WHERE id_produit=?");
$stmt->execute([$id]);

header("Location: admin.php");
