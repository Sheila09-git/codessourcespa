<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['id_user'])) {
    header('location:connexion.php');
    exit;
}

$user_id = $_SESSION['id_user'];

// --- 1. UPDATE USERNAME ---
if (isset($_POST['update_username'])) {
    $username = trim($_POST['username']);
    if (!preg_match("/^[a-zA-Z]+$/", $username)) {
        header('location:compte.php?message=Le nom ne doit contenir que des lettres');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE username = :username AND id_utilisateur != :id");
    $stmt->execute(['username' => $username, 'id' => $user_id]);

    if ($stmt->fetch()) {
        header('location:compte.php?message=Ce pseudo est déjà utilisé');
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET username = :username WHERE id_utilisateur = :id");
        $stmt->execute(['username' => $username, 'id' => $user_id]);
        header('location:compte.php?message=Username mis à jour !');
        exit;
    } catch (Exception $e) {
        header('location:compte.php?message=Erreur username');
        exit;
    }
}

// --- 2. UPDATE EMAIL ---
if (isset($_POST['update_email'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email AND id_utilisateur != :id");
    $stmt->execute(['email' => $email, 'id' => $user_id]);

    if ($stmt->fetch()) {
        header('location:compte.php?message=Cet email est déjà utilisé');
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET email = :email WHERE id_utilisateur = :id");
        $stmt->execute(['email' => $email, 'id' => $user_id]);
        header('location:compte.php?message=Email mis à jour !');
        exit;
    } catch (Exception $e) {
        header('location:compte.php?message=Erreur email');
        exit;
    }
}

// --- 3. UPDATE PASSWORD ---
if (isset($_POST['update_password'])) {
    $newPassword = $_POST['mot_de_passe'];

    if (strlen($newPassword) < 8 || strlen($newPassword) > 16) {
        header('location:compte.php?message=Le mot de passe doit faire entre 8 et 16 caractères');
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET motdepasse = :mdp WHERE id_utilisateur = :id");
        $stmt->execute(['mdp' => $hashedPassword, 'id' => $user_id]);
        header('location:compte.php?message=Mot de passe mis à jour !');
        exit;
    } catch (Exception $e) {
        header('location:compte.php?message=Erreur mot de passe');
        exit;
    }
}

// --- 4. UPDATE MOBILE ---
if (isset($_POST['update_mobile'])) {
    $mobile = trim($_POST['mobile']);
    if (!preg_match("/^[0-9]+$/", $mobile)) {
        header('location:compte.php?message=Le numéro doit contenir uniquement des chiffres');
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET telephone = :mobile WHERE id_utilisateur = :id");
        $stmt->execute(['mobile' => $mobile, 'id' => $user_id]);
        header('location:compte.php?message=Mobile mis à jour !');
        exit;
    } catch (Exception $e) {
        header('location:compte.php?message=Erreur mobile');
        exit;
    }
}

header('location:compte.php');
exit;
