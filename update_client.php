<?php
session_start();
require_once 'db.php';

if (isset($_GET['id'])){
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $user = $stmt->fetch();
}

if(!$user) { 
    die("Utilisateur non trouvé ! ID demandé : " . htmlspecialchars($_GET['id'] ?? 'aucun')); 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification les roles</title>
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
                        <a class="nav-link" href="client_list.php"> <img src="image/noun-client-1401473.svg" class="imagees" />Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="image/emojione--fork-and-knife-with-plate.svg" class="imagees" />Plats/menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="image/noun-reservations-7984943.svg" class="imagees" />Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="image/noun-restaurant-8105794.svg" class="imagees" />Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="image/noun-client-7615501.svg" class="imagees" />Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><img src="image/noun-messages-680438.svg" class="imagees" />Messages</a>
                    </li>
                </ul>
            </nav>

            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <div class="container">
                    <div class="card shadow-sm mx-auto" style="max-width: 600px; border-radius: 15px;">
                        <div class="card-body p-4">
                            <h2 class="h4 mb-4">Modifier le rôle de : <span class="text-primary"><?= htmlspecialchars($user['username']) ?></span></h2>
                            
                            <form action="processing.php" method="POST">
                                <input type="hidden" name="id_utilisateur" value="<?= $user['id_utilisateur'] ?>">

                                <div class="mb-4">
                                    <label for="role" class="form-label fw-bold">Sélection du grade :</label>
                                    <select name="role" id="role" class="form-select form-select-lg">
                                        <option value="" <?= (empty($user['role'])) ? 'selected' : '' ?>>Client</option>
                                        <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Administrateur</option>
                                    </select>
                                    <div class="form-text mt-2 text-muted">
                                        Le rôle "Administrateur" donne accès à toutes les pages de gestion (Clients, Réservations, etc.).
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" name="update_role" class="btn btn-primary px-4 py-2 fw-bold">Enregistrer</button>
                                    <a href="client_list.php" class="btn btn-outline-secondary px-4 py-2">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>