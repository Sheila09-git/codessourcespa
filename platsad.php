<?php include 'db.php';

// Récupérer les plats
$plats = $pdo->query("SELECT * FROM produit")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plats/menus</title>
</head>

<body>
    <h2>Gestion des plats</h2>

    <a href="add.php"> Ajouter un plat</a>

    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($plats as $plat): ?>
            <tr>
                <td><?= $plat['nom'] ?></td>
                <td><?= $plat['prix'] ?>€</td>
                <td>
                    <a href="edit.php?id=<?= $plat['id_produit'] ?>"> Modifier</a>
                    <a href="delete.php?id=<?= $plat['id_produit'] ?>"> Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>