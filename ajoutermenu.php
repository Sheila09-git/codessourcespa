<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'db.php';
$products = $pdo->query("SELECT id_produit, nom, prix FROM produit WHERE disponible = 1 ORDER BY nom ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $image_name = "menu.jpg";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "Image/" . $image_name);
    }
    $stmt = $pdo->prepare("INSERT INTO menu (nom, prix, description, image, disponible) VALUES (?, ?, ?, ?, 1)");
    $success = $stmt->execute([$nom, $prix, $description, $image_name]);

    if ($success) {
        $menu_id = $pdo->lastInsertId();
        if (isset($_POST['selected_products']) && is_array($_POST['selected_products'])) {
            $stmt_link = $pdo->prepare("INSERT INTO menu_produit (menu_id, produit_id) VALUES (?, ?)");
            foreach ($_POST['selected_products'] as $produit_id) {
                $stmt_link->execute([$menu_id, $produit_id]);
            }
        }
        header("Location: admin_menu.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un Menu - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_menu.css">
</head>

<body>
    <div class="container py-5">
        <div class="admin-card">
            <h2 class="display-title mb-4">Créer un Nouveau Menu</h2>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nom du Menu</label>
                        <input type="text" name="nom" class="form-control" placeholder="ex: Menu Maxi Burger" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Prix Total (€)</label>
                        <input type="number" step="0.01" name="prix" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Image du Menu</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Sélectionner les Plats inclus</label>
                        <input type="text" id="searchProd" class="form-control mb-2" placeholder="Filtrer les plats...">
                        <div class="product-selector">
                            <?php foreach ($products as $p): ?>
                                <div class="product-item">
                                    <input type="checkbox" name="selected_products[]" value="<?= $p['id_produit'] ?>" id="p<?= $p['id_produit'] ?>">
                                    <label for="p<?= $p['id_produit'] ?>">
                                        <?= htmlspecialchars($p['nom']) ?> (<?= $p['prix'] ?>€)
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-12 mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-coral px-5">Enregistrer le Menu</button>
                        <a href="admin_menu.php" class="btn btn-light">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('searchProd').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            document.querySelectorAll('.product-item').forEach(item => {
                let text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>

</html>