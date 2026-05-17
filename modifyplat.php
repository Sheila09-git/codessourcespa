<?php
require_once 'db.php';
$plat = null;
$selected_cats = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = ?");
    $stmt->execute([$id]);
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plat) {
        header("Location: plat_admin.html");
        exit();
    }
    $stmt_selected = $pdo->prepare("SELECT categorie_id FROM produit_categorie WHERE produit_id = ?");
    $stmt_selected->execute([$id]);
    $selected_cats = $stmt_selected->fetchAll(PDO::FETCH_COLUMN);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $description = $_POST['description'];
    $image_name = $_POST['current_image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "Image/" . $image_name);
    }
    $sql = "UPDATE produit SET nom=?, prix=?, quantite=?, description=?, image=? WHERE id_produit=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prix, $quantite, $description, $image_name, $id]);
    $pdo->prepare("DELETE FROM produit_categorie WHERE produit_id = ?")->execute([$id]);
    if (isset($_POST['categories'])) {
        foreach ($_POST['categories'] as $cat_id) {
            $pdo->prepare("INSERT INTO produit_categorie (produit_id, categorie_id) VALUES (?, ?)")
                ->execute([$id, $cat_id]);
        }
    }

    header("Location: plat_admin.html");
    exit();
}

$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Plat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plat.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex align-items-center mb-4">
            <a href="plat_admin.html" class="btn btn-outline-secondary btn-sm rounded-pill me-3 px-3">← Retour</a>
            <h1 class="display-title h2 mb-0">Modifier: <?= htmlspecialchars($plat['nom']) ?></h1>
        </div>

        <div class="admin-card">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $plat['id_produit'] ?>">
                <input type="hidden" name="current_image" value="<?= $plat['image'] ?>">

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nom du plat</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($plat['nom']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Prix (€)</label>
                        <input type="text" name="prix" class="form-control" value="<?= $plat['prix'] ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Quantité en stock</label>
                        <input type="number" name="quantite" class="form-control" value="<?= $plat['quantite'] ?>" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Image (Laisser vide pour garder l'actuelle)</label>
                        <input type="file" name="image" class="form-control">
                        <small class="text-muted">Actuelle: <?= $plat['image'] ?></small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($plat['description']) ?></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Sélectionner les catégories</label>
                        <div class="category-grid">
                            <?php foreach ($categories as $cat):
                                $checked = in_array($cat['id_categorie'], $selected_cats) ? 'checked' : '';
                            ?>
                                <div class="category-item">
                                    <input type="checkbox" name="categories[]" id="cat-<?= $cat['id_categorie'] ?>" value="<?= $cat['id_categorie'] ?>" <?= $checked ?>>
                                    <label for="cat-<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-coral px-5">Enregistrer les modifications</button>
                        <a href="plat_admin.html" class="btn btn-light px-4">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>