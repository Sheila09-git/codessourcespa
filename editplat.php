<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$categories = $pdo->query("SELECT * FROM categorie")->fetchAll();

// Récupère le plat existant
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: platsad.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = ?");
$stmt->execute([$id]);
$plat = $stmt->fetch();

if (!$plat) {
    header("Location: platsad.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $imageName = $plat['photo'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }


    $sql = "UPDATE produit SET nom=?, description=?, prix=?, photo=?, id_categorie=? WHERE id_produit=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nom'],
        $_POST['description'],
        $_POST['prix'],
        $imageName,
        $_POST['id_categorie'],
        $id
    ]);

    header("Location: platsad.php");
    exit;
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier un plat</title>
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
                    <li class="nav-item"><a class="nav-link" href="client_list.php">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="platsad.php">Plats</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Activités</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="newsletter.php">Newsletters</a></li>
                    <li class="nav-item"><a class="nav-link" href="salariés.php">Salariés</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Messages</a></li>
                </ul>
            </nav>

            <main class="col-md-9 col-lg-10 ms-sm-auto p-5 bg-light min-vh-100">
                <div class="container">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">

                            <h2 class="mb-4 fw-bold text-dark">Modifier un plat</h2>

                            <form method="POST" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label class="form-label">Nom du plat</label>
                                    <!-- ✅ value pré-rempli avec les données existantes -->
                                    <input name="nom" type="text" class="form-control"
                                        value="<?= htmlspecialchars($plat['nom']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="4" required>
                                    <?= htmlspecialchars($plat['description']) ?>
                                </textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prix (€)</label>
                                    <!-- ✅ value pré-rempli -->
                                    <input name="prix" type="number" step="0.01" class="form-control"
                                        value="<?= $plat['prix'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label><br>
                                    <img src="uploads/<?= htmlspecialchars($plat['photo']) ?>"
                                        width="120" class="rounded mb-2"><br>
                                    <label class="form-label">Changer l'image (optionnel)</label>
                                    <input name="image" type="file" class="form-control" accept="image/*">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Catégorie</label>
                                    <!-- ✅ name="id_categorie" et selected sur la catégorie actuelle -->
                                    <select name="id_categorie" class="form-select">
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_categorie'] ?>"
                                                <?= $cat['id_categorie'] == $plat['id_categorie'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-dark px-4 py-2">Modifier le plat</button>
                                    <a href="platsad.php" class="btn btn-secondary px-4 py-2">Annuler</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>