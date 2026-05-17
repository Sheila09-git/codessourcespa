<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'salarie') {
    header('Location: connexion.php?message=Accès réservé aux salariés');
    exit;
}

require_once 'db.php';


$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id");
$stmt->execute(['id' => $_SESSION['id_user']]);
$salarie = $stmt->fetch();

if (!$salarie) {
    session_destroy();
    header('Location: connexion.php?message=Compte introuvable');
    exit;
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mon compte — <?= htmlspecialchars($salarie['username']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4 text-black">
                    <a class="nav-link active" href="salarcom.php" style="color: pink;">
                        <img src="image/subway--admin.svg" class="image" /> Mon compte
                    </a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item"><a class="nav-link" href="salaredt.php">Mon emploi du temps</a></li>
                    <li class="nav-item"><a class="nav-link" href="salardocs.php">Mes documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="message.php">Messagerie</a></li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="processing.php?action=logout">Se déconnecter</a>
                    </li>
                </ul>
            </nav>

            
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">

                <h3 class="mb-4">Bonjour, <?= htmlspecialchars($salarie['username']) ?> </h3>

                <div class="card mb-4">
                    <div class="card-header fw-bold"> Mes informations</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Nom d'utilisateur</label>
                                <p class="fw-bold"><?= htmlspecialchars($salarie['username']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Email</label>
                                <p class="fw-bold"><?= htmlspecialchars($salarie['email']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Téléphone</label>
                                <p class="fw-bold"><?= htmlspecialchars($salarie['telephone'] ?? 'Non renseigné') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Poste</label>
                                <p class="fw-bold"><?= htmlspecialchars($salarie['poste'] ?? 'Non renseigné') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date d'embauche</label>
                                <p class="fw-bold">
                                    <?= isset($salarie['date_embauche'])
                                        ? date('d/m/Y', strtotime($salarie['date_embauche']))
                                        : 'Non renseignée' ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Statut</label>
                                <p>
                                    <?php if ($salarie['actif'] ?? 1): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header fw-bold">Prochains horaires</div>
                    <div class="card-body">
                        <?php
                        $stmt2 = $pdo->prepare("
                        SELECT * FROM emploi_du_temps 
                        WHERE salarie_id = :id AND jour >= CURDATE() 
                        ORDER BY jour ASC 
                        LIMIT 5
                    ");
                        $stmt2->execute(['id' => $_SESSION['id_user']]);
                        $horaires = $stmt2->fetchAll();
                        ?>

                        <?php if (empty($horaires)): ?>
                            <p class="text-muted">Aucun horaire planifié pour le moment.</p>
                        <?php else: ?>
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jour</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Poste</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horaires as $h): ?>
                                        <tr>
                                            <td><?= date('l d/m/Y', strtotime($h['jour'])) ?></td>
                                            <td><?= $h['heure_debut'] ?></td>
                                            <td><?= $h['heure_fin'] ?></td>
                                            <td><?= htmlspecialchars($h['poste']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="salaredt.php" class="btn btn-outline-primary btn-sm">Voir tout mon emploi du temps →</a>
                        <?php endif; ?>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>