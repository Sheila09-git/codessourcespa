<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location:connexion_admin.php?message=Accès réservé aux administrateurs');
    exit;
}


$query = $pdo->prepare("SELECT username FROM utilisateur WHERE id_utilisateur = :id");
$query->execute(['id' => $_SESSION['id_user']]);
$admin = $query->fetch();


$prenomAdmin = $admin ? $admin['username'] : "Admin";
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4 text-black">
                    <a class="nav-link active" href="admin.php">
                        <img src="image/subway--admin.svg" class="image" />Admin
                    </a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item">
                        <a class="nav-link" href="client_list.php"> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="platsad.php">Plats/menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php">Activités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newsletter.php">Nesletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card white">
                            <div class="card-body end">
                                <a href="#"><img src="image/Design sans titre.png" class="imagees" /></a>
                                <h4><?php echo htmlspecialchars($prenomAdmin); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4>Reservations</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4>Commandes</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4>Revenus</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4>Nouveaux clients</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Activité récente</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>