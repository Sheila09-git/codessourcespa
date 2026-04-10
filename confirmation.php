<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'db.php';

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    $query = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE token = :token");
    $query->execute(['token' => $token]);
    $user = $query->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE utilisateur SET confirme = 1, token = NULL WHERE id_utilisateur = :id");
        $update->execute(['id' => $user['id']]);
        header('location:inscription.php?message=Compte validé ! Vous pouvez vous connecter.');
        exit;
    } else {
        header('location:inscription.php?message=Lien invalide ou expiré');
        exit;
    }
} else {
    header('location:inscription.php?message=Aucun token trouvé');
    exit;
}