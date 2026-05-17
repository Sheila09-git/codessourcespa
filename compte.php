<?php
session_start();


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: accueil.php');
    exit;
}

require_once 'db.php';

if (!isset($_SESSION['id_user'])) {
    header('location:connexion.php');
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id");
$stmt->execute(['id' => $_SESSION['id_user']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="compte.css" />
</head>

<body>

    <div class="profile-card">
        <input type="radio" name="tab" id="tab-personal" checked>
        <input type="radio" name="tab" id="tab-reservation">
        <input type="radio" name="tab" id="tab-apparence">

        <div class="sidebar">
            <div class="sidebar-top">
                <label for="tab-personal">Personal Info</label>
                <label for="tab-reservation">Réservation</label>
                <label for="tab-apparence">Apparence</label>
            </div>

            <div class="sidebar-bottom">
                <a href="accueil.php" class="sidebar-back-link">Retourner</a>
            </div>
        </div>

        <div class="content-area">
            <div class="section" id="content-personal">

                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-info py-2 text-center small mb-4">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <h1 class="display-4 fw-bold mb-0"><?php echo htmlspecialchars($user['username']); ?></h1>
                    <a href="?edit=username" class="btn btn-sm btn-outline-primary mt-2">Modifier le nom</a>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <label class="text-muted small d-block">Email</label>
                        <span class="fs-5"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <a href="?edit=email" class="btn btn-sm btn-link text-decoration-none">Modifier</a>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <label class="text-muted small d-block">Mot de passe</label>
                        <span class="fs-5">••••••••</span>
                    </div>
                    <a href="?edit=password" class="btn btn-sm btn-link text-decoration-none">Modifier</a>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <label class="text-muted small d-block">Numéro mobile</label>
                        <span class="fs-5"><?php echo htmlspecialchars($user['telephone'] ?? 'Non renseigné'); ?></span>
                    </div>
                    <a href="?edit=mobile" class="btn btn-sm btn-link text-decoration-none">Modifier</a>
                </div>

                <?php if (isset($_GET['edit'])): ?>
                    <div class="minimal-popup-overlay">
                        <div class="minimal-popup-card">
                            <form action="compte-processing.php" method="POST">
                                <h6 class="mb-3">Modifier <?php echo htmlspecialchars($_GET['edit']); ?></h6>

                                <?php if ($_GET['edit'] == 'username'): ?>
                                    <input type="text" name="username" class="form-control mb-3" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    <button type="submit" name="update_username" class="btn btn-sm btn-success w-100 mb-2">Confirmer</button>

                                <?php elseif ($_GET['edit'] == 'email'): ?>
                                    <input type="email" name="email" class="form-control mb-3" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    <button type="submit" name="update_email" class="btn btn-sm btn-success w-100 mb-2">Confirmer</button>

                                <?php elseif ($_GET['edit'] == 'password'): ?>
                                    <input type="password" name="mot_de_passe" class="form-control mb-3" placeholder="Nouveau mot de passe" required>
                                    <button type="submit" name="update_account" class="btn btn-sm btn-success w-100 mb-2">Confirmer</button>

                                <?php elseif ($_GET['edit'] == 'mobile'): ?>
                                    <input type="text" name="mobile" class="form-control mb-3" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>" required>
                                    <button type="submit" name="update_mobile" class="btn btn-sm btn-success w-100 mb-2">Confirmer</button>
                                <?php endif; ?>

                                <a href="compte.php" class="btn btn-sm btn-light w-100 border">Annuler</a>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mt-5 pt-4 border-top">
                    <a href="?action=logout" class="btn btn-outline-danger w-100 py-2">Déconnexion</a>
                </div>
            </div>

            <div class="section" id="content-reservation">
                <h3>Mes Réservations</h3>
                <div id="reservation-list">
                    <p>Chargement de vos réservations...</p>
                </div>
            </div>

            <div class="section" id="content-apparence">
                <h3>Apparence</h3>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                    <label class="form-check-label" for="darkModeToggle">Activer le Mode Sombre</label>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script src="theme.js"></script>
    <script src="reservation.js"></script>
    <script>
        chargerMesReservations();
    </script>
</body>

</html>