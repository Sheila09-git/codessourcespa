<?php
require_once 'db.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = (int)$_GET['status'];
    $stmt = $pdo->prepare("UPDATE produit SET disponible = ? WHERE id_produit = ?");
    $stmt->execute([$status, $id]);
}
