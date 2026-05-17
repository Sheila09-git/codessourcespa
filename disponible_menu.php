<?php
require_once 'db.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = (int)$_GET['status'];

    $stmt = $pdo->prepare("UPDATE menu SET disponible = ? WHERE id_menu = ?");
    $stmt->execute([$status, $id]);
}
header("Location: admin_menu.php");
exit();
