<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['id_user'])) {
    $pdo->prepare("UPDATE utilisateur SET last_activity = NOW() WHERE id_utilisateur = :id")
        ->execute(['id' => $_SESSION['id_user']]);
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location:connexion_admin.php?message=Accès réservé aux administrateurs');
    exit;
}


$query = $pdo->prepare("SELECT username FROM utilisateur WHERE id_utilisateur = :id");
$query->execute(['id' => $_SESSION['id_user']]);
$admin = $query->fetch();


$prenomAdmin = $admin ? $admin['username'] : "Admin";
$usersQuery = $pdo->query("SELECT COUNT(*) AS total_users FROM utilisateur");
$totalUsers = $usersQuery->fetch()['total_users'];
$reservationQuery = $pdo->query("SELECT COUNT(*) AS total_reservation FROM reservation");
$totalreservation = $reservationQuery->fetch()['total_reservation'];
$commandeQuery = $pdo->query("SELECT COUNT(*) AS total_commande FROM commande");
$totalcommande = $commandeQuery->fetch()['total_commande'];
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
                        <a class="nav-link" href="admin_recrutement.php"> Recrutement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="plat_admin.html">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_menu.php">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php">Activités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-reservation.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_offre.php">Promotions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newsletter.php">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salariés.php">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="message.php">Messages</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <div class="col-md-6 d-flex mt-4 pe-4">
                    <div class="d-flex align-items-center">
                        <form action="update_pdp.php" method="POST" enctype="multipart/form-data" class="me-3">
                            <label for="pdp_input" style="cursor: pointer;">
                                <img src="<?= $_SESSION['pdp'] ?? 'image/Design sans titre.png' ?>" 
                                    class="imagees" 
                                    title="Cliquez pour changer de photo" 
                                    style="border: 2px solid #a01818;">
                            </label>
                            <input type="file" name="photo" id="pdp_input" accept="image/*" onchange="this.form.submit()" style="display: none;">
                        </form>
                        
                        <h4 class="mb-0"><?php echo htmlspecialchars($prenomAdmin); ?></h4>
                    </div>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4><?= $totalreservation ?></h4>
                                <p>Reservations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4><?= $totalcommande ?></h4>
                                <p>Commandes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h4><?= $totalUsers ?></h4>
                                <p>Utilisateurs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>