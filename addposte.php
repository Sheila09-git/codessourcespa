<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO recrutement (titre, type_poste, contrat, description, tags) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['titre'],
        $_POST['type_poste'],
        $_POST['contrat'],
        $_POST['description'],
        $_POST['tags']
    ]);
    header("Location: admin_recrutement.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plat.css">
</head>

<body>
    <div class="container py-5">
        <div class="admin-card">
            <h2 class="display-title mb-4">Nouvelle Offre</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nom de l'offre</label>
                    <input type="text" name="titre" class="form-control" placeholder="ex: Serveur de soir" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Poste</label>
                        <select name="type_poste" class="form-select">
                            <option value="Serveur">Serveur</option>
                            <option value="Cuisinier">Cuisinier</option>
                            <option value="Chef">Chef</option>
                            <option value="Caissier">Caissier</option>
                            <option value="Plongeur">Plongeur</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Contrat</label>
                        <select name="contrat" class="form-select">
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tags (mots clés)</label>
                    <input type="text" name="tags" class="form-control" placeholder="Expérience, Anglais...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-coral w-100">Enregistrer</button>
                <a href="admin_recrutement.php" class="btn btn-light w-100 mt-2">Annuler</a>
            </form>
        </div>
    </div>
</body>

</html>