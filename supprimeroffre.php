<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM offres WHERE id_offre = ?");
    $stmt->execute([$id]);
}

header("Location: admin_offre.php");
exit();