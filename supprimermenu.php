<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    try {
        $pdo->beginTransaction();
        $stmt1 = $pdo->prepare("DELETE FROM menu_produit WHERE menu_id = ?");
        $stmt1->execute([$id]);
        $stmt2 = $pdo->prepare("DELETE FROM menu WHERE id_menu = ?");
        $stmt2->execute([$id]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

header("Location: admin_menu.php");
exit();
