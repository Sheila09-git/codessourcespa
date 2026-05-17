<?php
session_start();
require_once 'db.php';
if (isset($_SESSION['id_user'])) {
    $pdo->prepare("UPDATE utilisateur SET last_activity = NOW() WHERE id_utilisateur = :id")
        ->execute(['id' => $_SESSION['id_user']]);
}
if (!isset($_SESSION['id_user'])) {
    header('location:connexion.php');
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['id_user']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php if (isset($_SESSION['email'])) ?>
        <h1>Bienvenue sur ton compte ! </h1>
        <br>
        <?php
        if (isset($_GET['message'])) {
        ?>
            <p style="color:red"><?= $_GET['message'] ?></p>
        <?php
        }
        ?>
    </div>

    <form action="processing.php" method="POST" class="container">
        <input id="username" type="text" name="username" value="<?= $user['username'] ?>">
        <button type="submit" name="update_username">Modifier le prénom</button>
    </form>

    <form action="processing.php" method="POST" class="container">
        <input id="email" type="email" name="email" value="<?= $user['email'] ?>">
        <button type="submit" name="update_email">Modifier l'email</button>
    </form>


    <form action="processing.php" method="post" class="container">

        <input id="mot_de_passe" type="password" name="mot_de_passe" placeholder="Changer de mot de passe">
        <button type="submit" name="update_account">Modifier</button>
        <br>
        <a href="delete_account.php" onclick="return confirm('Etes vous sûr de vouloir supprimer votre compte ?');">
            <button type="button" class="btn btn-danger">Supprimer mon compte</button>
        </a>
        <a class="nav-link" href="processing.php?action=logout">Deconnexion</a>
    </form>


</body>

</html>