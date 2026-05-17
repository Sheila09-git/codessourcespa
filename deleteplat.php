<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM produit WHERE id_produit=?");
$stmt->execute([$id]);

header("Location: platsad.php");
