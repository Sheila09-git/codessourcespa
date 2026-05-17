<?php
require_once 'db.php';

$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $description = $_POST['description'];
    $disponible = 1;
    $image_name = "plat.jpg";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "Image/" . $image_name);
    }

    $stmt = $pdo->prepare("INSERT INTO produit (nom, prix, quantite, description, image, disponible) VALUES (?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$nom, $prix, $quantite, $description, $image_name, $disponible]);

    if ($success) {
        $produit_id = $pdo->lastInsertId();
        if (isset($_POST['categories'])) {
            foreach ($_POST['categories'] as $cat_id) {
                $stmt_cat = $pdo->prepare("INSERT INTO produit_categorie (produit_id, categorie_id) VALUES (?, ?)");
                $stmt_cat->execute([$produit_id, $cat_id]);
            }
        }
        header("Location: plat_admin.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un plat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plat.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="admin-card">
            <header class="mb-4">
                <h1 class="display-title mb-0">Ajouter un plat</h1>
                <p class="text-muted small-subtitle">Création d'un nouveau menu</p>
            </header>

            <form action="ajouterplat.php" method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nom du plat</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Prix (€)</label>
                        <input type="number" step="0.01" name="prix" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Quantité</label>
                        <input type="number" name="quantite" class="form-control" value="1">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Image du plat</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Catégories</label>
                        <div class="category-grid">
                            <?php foreach ($categories as $cat): ?>
                                <div class="category-item">
                                    <input type="checkbox" name="categories[]"
                                        id="cat-<?= $cat['id_categorie'] ?>"
                                        value="<?= $cat['id_categorie'] ?>">
                                    <label for="cat-<?= $cat['id_categorie'] ?>">
                                        <?= htmlspecialchars($cat['nom']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-coral px-5">Enregistrer le plat</button>
                        <a href="plat_admin.html" class="btn btn-light px-4">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>