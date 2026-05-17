<?php
session_start();
require_once 'db.php';
 
$utilisateur_id = $_SESSION['id_utilisateur'];
$id_destinataire = $_POST['id_destinataire'] ?? null;
$contenu = trim($_POST['contenu'] ?? '');

if (!$id_destinataire || $contenu === '') {
    echo json_encode(['succes' => false, 'erreur' => 'Données manquantes']);
    exit;
}
 
try {
    $query = $pdo->prepare("INSERT INTO message (contenu, id_expediteur, id_destinataire, date_envoi, lu)
        VALUES (?, ?, ?, NOW(), 0)
    ");
    $query->execute([$contenu, $utilisateur_id, $id_destinataire]);
 
    echo json_encode(['succes' => true]);
 
} catch (Exception $e) {
    echo json_encode(['succes' => false, 'erreur' => $e->getMessage()]);
}