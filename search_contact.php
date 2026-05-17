<?php
session_start();
require_once 'db.php';
 
$id_session = $_SESSION['id_utilisateur'] ?? $_SESSION['id_user'] ?? null;
 
if (!$id_session) {
    echo json_encode([]);
    exit;
}
 
$search = '%' . trim($_GET['search'] ?? '') . '%';
 
$query = $pdo->prepare("
    SELECT id_utilisateur, username, role, pdp
    FROM utilisateur
    WHERE is_delete = 0
      AND id_utilisateur != :moi
      AND role IN ('admin', 'salarie')
      AND username LIKE :search
    ORDER BY role ASC, username ASC
");
$query->execute(['moi' => $id_session, 'search' => $search]);
 
echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
 