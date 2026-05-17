<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: connexion_admin.php?message=Accès réservé aux administrateurs');
    exit;
}


if (isset($_SESSION['id_user'])) {
    $pdo->prepare("UPDATE utilisateur SET last_activity = NOW() WHERE id_utilisateur = :id")
        ->execute(['id' => $_SESSION['id_user']]);
}

$query = $pdo->prepare("SELECT username FROM utilisateur WHERE id_utilisateur = :id");
$query->execute(['id' => $_SESSION['id_user']]);
$admin = $query->fetch();
$prenomAdmin = $admin ? $admin['username'] : "Admin";


$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom']);
    $prenom   = trim($_POST['prenom']);
    $email    = trim($_POST['email']);
    $poste    = trim($_POST['poste']);
    $mdp_temp = trim($_POST['mot_de_passe']);

    $hash = password_hash($mdp_temp, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET username=?, prenom=?, email=?, motdepasse=? , role='salarie', poste=?, date_embauche=NOW()");
        $stmt->execute([$nom, $prenom, $email, $hash, $poste]);
        $message = "Salarié modifié avec succès !";
        $messageType = 'success';
        header("Location: salariés.php");
        exit;
    } catch (PDOException $e) {
        $message = "Email déjà utilisé ou Erreur : " . $e->getMessage();
        $messageType = 'danger';
        header("Location: salariés.php");
        exit;
    }
}


?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin — Salariés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4 text-black">
                    <a class="nav-link active" href="admin.php">
                        <img src="image/subway--admin.svg" class="image" /> Admin
                    </a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item"><a class="nav-link" href="client_list.php">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="platsad.php">Plats</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Activités</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="newsletter.php">Newsletters</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="salariés.php">Salariés</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Messages</a></li>
                </ul>
            </nav>

            <!-- Contenu principal -->
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">


                <h3 class="mb-4">Gestion des Salariés</h3>

                <!-- Message retour -->
                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Formulaire modification salarié -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Modifier un salarié</div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" placeholder="Dupont">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" placeholder="Sheila">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="sheishei@restaurant.fr">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Poste</label>
                                    <select name="poste" class="form-select" required>
                                        <option value="">-- Choisir un poste --</option>
                                        <option value="Serveur">Serveur</option>
                                        <option value="Cuisinier">Cuisinier</option>
                                        <option value="Chef">Chef</option>
                                        <option value="Caissier">Caissier</option>
                                        <option value="Plongeur">Plongeur</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe temporaire</label>
                                    <input type="text" name="mot_de_passe" class="form-control" placeholder="Ex: Bienvenue2025!">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark mb-3">Modifié le salarié</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>