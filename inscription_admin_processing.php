<?php
session_start();
require_once 'db.php';

if (isset($_POST['inscription_admin'])) {
    if (!empty($_POST['email'])) {
        setcookie('email', $_POST['email'], time() + 24 * 3600);
    }
    
    if (
        empty($_POST['username']) ||
        empty($_POST['email']) ||
        empty($_POST['mot_de_passe'])
    ) {
        header('location:inscription_admin.php?message=Compléter TOUTES les cases !');
        exit;
    }

    if ($_POST['mot_de_passe'] !== $_POST['confirme_mdp']) {
        header('location:inscription_admin.php?message=Les mots de passe ne correspondent pas !');
        exit;
    }

    // Vérification du pseudo dans la table utilisateur
    $q = 'SELECT id_utilisateur FROM utilisateur WHERE username = :username';
    $statement = $pdo->prepare($q);
    $statement->execute(['username' => $_POST['username']]);
    
    if ($statement->fetch()) {
        header('location:inscription_admin.php?message=Ce pseudo est déjà utilisé !');
        exit;
    }

    // Vérification de l'email
    $q = 'SELECT id_utilisateur FROM utilisateur WHERE email = :email';
    $statement = $pdo->prepare($q);
    $statement->execute(['email' => $_POST['email']]);
    
    if ($statement->fetch()) {
        header('location:inscription_admin.php?message=Email déjà utilisé !');
        exit;
    }

    try {
    // Ajout de la colonne 'telephone' dans le INSERT
    $sql = "INSERT INTO utilisateur (username, email, motdepasse, telephone, role, confirme) 
            VALUES (:nom, :mail, :password, :tel, 'admin', 1)";
    $query = $pdo->prepare($sql);
    
    $query->execute([
        'nom'      => $_POST['username'], 
        'mail'     => $_POST['email'], 
        'password' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
        'tel'      => '0000000000' 
    ]);

    $_SESSION['id_user'] = $pdo->lastInsertId();
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['role'] = 'admin';

    header('location:admin.php');
    exit;
} catch (Exception $e) {
    header('location:inscription_admin.php?message=Erreur : ' . $e->getMessage());
    exit;
}
}