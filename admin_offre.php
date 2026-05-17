<?php
require_once 'db.php';
$offres = $pdo->query("SELECT * FROM offres ORDER BY id_offre DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - Promotions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="offre_admin.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-title">Gestion des Promotions</h1>
                    <a href="ajouteroffre.php" class="btn btn-coral" style="background-color: #ff7e7e !important; color: white !important; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">
                        + Nouvelle Annonce
                    </a>
                </div>

                <div class="card shadow-sm border-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Aperçu</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offres as $o): ?>
                                <tr>
                                    <td><img src="Image/<?= $o['image'] ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;"></td>
                                    <td><strong><?= htmlspecialchars($o['nom']) ?></strong></td>
                                    <td><small class="text-muted"><?= mb_strimwidth(htmlspecialchars($o['description']), 0, 60, "...") ?></small></td>
                                    <td class="text-end">
                                        <form action="supprimeroffre.php" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette Promotion ?')">
    <input type="hidden" name="id" value="<?= $o['id_offre'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
</form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>