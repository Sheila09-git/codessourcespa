<?php 
session_start();
require_once 'db.php';


if (isset($_SESSION['id_user'])){
    try {

        $stmt = $pdo->prepare("UPDATE utilisateur SET is_delete = 1 WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $_SESSION['id_user']]);

        session_unset();
        session_destroy();


        header('location:inscription.php?message=Ton compte a bien été supprimé !');
        exit;
    } catch(Exception $e) {

        header('location:profile.php?message=Erreur lors de la suppression.');
        exit;
    }
} else {
    header('location:inscription.php');
    exit;
}
?>