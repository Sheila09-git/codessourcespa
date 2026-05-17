<?php
require_once 'db.php';
$sql = "SELECT m.*, GROUP_CONCAT(p.nom SEPARATOR ', ') as items 
        FROM menu m
        LEFT JOIN menu_produit mp ON m.id_menu = mp.menu_id
        LEFT JOIN produit p ON mp.produit_id = p.id_produit
        GROUP BY m.id_menu
        ORDER BY m.id_menu DESC";

$menus = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Menus - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_menu.css">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-title">Gestion des Menus</h1>
                    
                    <a href="ajoutermenu.php" class="btn btn-coral" style="background-color: #ff7e7e !important; color: white !important; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">
                        + Nouveau Menu
                    </a>
                </div>

                <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Nom du Menu</th>
                                <th>Composition</th>
                                <th>Prix</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $m): ?>
                                <tr>
                                    <td>
                                        <img src="Image/<?= htmlspecialchars($m['image']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" alt="menu">
                                    </td>
                                    <td><strong><?= htmlspecialchars($m['nom']) ?></strong></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= $m['items'] ? htmlspecialchars($m['items']) : '<em>Aucun produit</em>' ?>
                                        </small>
                                    </td>
                                    <td><?= number_format($m['prix'], 2) ?>€</td>
                                    <td>
                                        <?php if ($m['disponible']): ?>
                                            <a href="disponible_menu.php?id=<?= $m['id_menu'] ?>&status=0" class="btn btn-sm btn-success">Disponible</a>
                                        <?php else: ?>
                                            <a href="disponible_menu.php?id=<?= $m['id_menu'] ?>&status=1" class="btn btn-sm btn-secondary">Indisponible</a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <form action="supprimermenu.php" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce menu ?')">
    <input type="hidden" name="id" value="<?= $m['id_menu'] ?>">
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