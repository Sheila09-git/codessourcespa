<?php 
session_start();
require_once 'db.php';
 
$utilisateur_id = $_SESSION['id_utilisateur'];
$id_contact = $_GET['id_contact'] ?? null;
 
if (!$id_contact) {
    echo json_encode([]);
    exit;
}
 
$query = $pdo->prepare("
    SELECT m.id_message, m.contenu, m.id_expediteur, m.id_destinataire, m.date_envoi, m.lu,
           u.pdp AS photo_expediteur
    FROM message m
    JOIN utilisateur u ON u.id_utilisateur = m.id_expediteur
    WHERE 
        (m.id_expediteur = ? AND m.id_destinataire = ?)
        OR
        (m.id_expediteur = ? AND m.id_destinataire = ?)
    ORDER BY m.date_envoi ASC
");
 
$query->execute([$utilisateur_id, $id_contact, $id_contact, $utilisateur_id]);
$messages = $query->fetchAll(PDO::FETCH_ASSOC);
 

$pdo->prepare("
    UPDATE message SET lu = 1
    WHERE id_expediteur = ? AND id_destinataire = ? AND lu = 0
")->execute([$id_contact, $utilisateur_id]);
 
echo json_encode($messages);