<?php
require_once 'db.php';

// ici joindre pour récupérer le nom de la catégorie
$sql = "SELECT p.*, c.nom AS nom_categorie 
        FROM produit p 
        LEFT JOIN categorie c ON p.id_categorie = c.id_categorie";
$result = $pdo->query($sql);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Plats</title>
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
                        <a class="nav-link" href="platsad.php">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Menus</a>
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
                        <a class="nav-link" href="newsletter.php">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salariés.php">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <h2 class="mb-4">Gestion des plats</h2>

                <a href="addplat.php" class="btn btn-dark mb-3">
                    Ajouter un plat
                </a>

                <table class="table table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($plat = $result->fetch()) { ?>

                            <tr>

                                <td>
                                    <img src="uploads/<?= htmlspecialchars($plat['photo']) ?>" width="80">
                                </td>

                                <td><?= htmlspecialchars($plat['nom']) ?></td>

                                <td><?= $plat['prix'] ?> €</td>

                                <td><?= htmlspecialchars($plat['nom_categorie']) ?></td>

                                <td>
                                    <a href="editplat.php?id=<?= $plat['id_produit'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                    <a href="deleteplat.php?id=<?= $plat['id_produit'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')">Supprimer</a>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>
                </table>
            </main>
        </div>
    </div>
</body>

</html>