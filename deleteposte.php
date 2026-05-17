<?php
session_start();
require_once 'db.php';
if (isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM recrutement WHERE id_offre = ?");
    $stmt->execute([$id_a_supprimer]);
}
header("Location: admin_recrutement.php");
exit();
