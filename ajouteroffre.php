<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $image_name = "default_announcement.jpg";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "Image/" . $image_name);
    }
    $stmt = $pdo->prepare("INSERT INTO offres (nom, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $description, $image_name]);

    header("Location: admin_offre.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une Annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="offre_admin.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="max-width-form mx-auto bg-white p-5 shadow-sm rounded-4">
            <h2 class="display-title mb-4">Nouvelle Annonce</h2>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Titre de l'annonce</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: Soirée Jazz, Fermeture exceptionnelle..." required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Détails de l'annonce..." required></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Image d'illustration</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-12 mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-coral px-5">Publier</button>
                        <a href="admin_offres.php" class="btn btn-light">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>