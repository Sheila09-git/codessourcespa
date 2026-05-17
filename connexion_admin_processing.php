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


    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();


    if ($user) {
    if (isset($user['is_delete']) && $user['is_delete'] == 1) {
            header('location:account_delete.php');
            exit;
    }
    if (isset($user['is_blocked']) && $user['is_blocked'] == 1) {
        header('location:account_delete.php');
        exit;
    }
}

    if ($user && password_verify($mdp, $user['motdepasse'])) {
        
        if ($user['role'] === 'admin') {
            $_SESSION['id_user'] = $user['id_utilisateur'];
            $_SESSION['id_utilisateur'] = $user['id_utilisateur']; 
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'admin';
            $_SESSION['photo_profil'] = $user['photo_profil'];
            $_SESSION['pdp'] = $user['pdp'] ? 'uploads/' . $user['pdp'] : null;
            
            header('location:admin.php');
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