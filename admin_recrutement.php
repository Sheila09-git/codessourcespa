<?php
session_start();
require_once 'db.php';
$offres = $pdo->query("SELECT * FROM recrutement ORDER BY id_offre DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plat.css">
</head>

<body class="bg-light">
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

            <div class="col-md-9 col-lg-10 py-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-title">Gestion des Postes</h1>
                    <a href="addposte.php" class="btn btn-coral">+ Ajouter un poste</a>
                </div>

                <div class="admin-card" style="max-width: 100%;">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Poste</th>
                                <th>Titre</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offres as $o): ?>
                                <tr>
                                    <td><img src="image/<?= $o['type_poste'] ?>.jpg" width="50" class="rounded"></td>
                                    <td><strong><?= $o['type_poste'] ?></strong></td>
                                    <td><?= htmlspecialchars($o['titre']) ?></td>
                                    <td class="text-end">
                                        <a href="deleteposte.php?id=<?= $o['id_offre'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Supprimer cette annonce ?')">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>