<?php
session_start();
require_once 'db.php';

if (isset($_POST['connexion-admin'])) {
    $email = $_POST['email'];
    $mdp = $_POST['mot_de_passe'];

    if (empty($email) || empty($mdp)) {
        header('location:connexion_admin.php?message=Veuillez remplir tous les champs');
        exit;
    }

    // On cherche l'utilisateur par mail
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Vérification : existence, mot de passe et surtout le RÔLE admin
    if ($user && password_verify($mdp, $user['motdepasse'])) {

        if ($user['role'] === 'admin') {
            $_SESSION['id_user'] = $user['id_utilisateur'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'admin';

            header('location:admin.php'); // Redirection vers le panel admin
            exit;
        } else {
            header('location:connexion_admin.php?message=Accès refusé : vous n\'êtes pas administrateur');
            exit;
        }
    } else {
        header('location:connexion_admin.php?message=Identifiants incorrects');
        exit;
    }
}
