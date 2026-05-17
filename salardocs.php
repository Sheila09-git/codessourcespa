<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'salarie') {
    header('Location: connexion.php');
    exit;
}


if (isset($_GET['telecharger'])) {
    $stmt = $pdo->prepare("SELECT * FROM documents WHERE id = ? AND salarie_id = ?");
    $stmt->execute([$_GET['telecharger'], $_SESSION['id_user']]);
    $doc = $stmt->fetch();

    if ($doc) {
        $chemin = '/var/www/html/uploads/documents/' . $doc['nom_fichier'];
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $doc['nom_original'] . '"');
        readfile($chemin);
        exit;
    } else {
        header('Location: salardocs.php?erreur=1');
        exit;
    }
}


$stmt = $pdo->prepare("SELECT * FROM documents WHERE salarie_id = ? ORDER BY date_upload DESC");
$stmt->execute([$_SESSION['id_user']]);
$documents = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Mes documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4">
                    <a class="nav-link" href="salarcom.php">Mon compte</a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item"><a class="nav-link" href="salaredt.php">Mon emploi du temps</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="salardocs.php">Mes documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="message.php">Messagerie</a></li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="processing.php?action=logout">Se déconnecter</a>
                    </li>
                </ul>
            </nav>

            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <h3 class="mb-4">Mes documents</h3>

                <?php if (isset($_GET['erreur'])): ?>
                    <div class="alert alert-danger">Document introuvable ou accès refusé.</div>
                <?php endif; ?>

                <?php if (empty($documents)): ?>
                    <div class="alert alert-info">Aucun document disponible pour le moment.</div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($documents as $d): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        
                                        <div style="font-size: 48px;">📄</div>
                                        <h6 class="mt-2"><?= htmlspecialchars($d['nom_original']) ?></h6>
                                        <?php
                                        $labels = [
                                            'contrat'       => '<span class="badge bg-primary">Contrat</span>',
                                            'fiche_de_paie' => '<span class="badge bg-success">Fiche de paie</span>',
                                            'autre'         => '<span class="badge bg-secondary">Autre</span>'
                                        ];
                                        echo $labels[$d['type']] ?? $d['type'];
                                        ?>
                                        <p class="text-muted small mt-1">
                                            Ajouté le <?= date('d/m/Y', strtotime($d['date_upload'])) ?>
                                        </p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="?telecharger=<?= $d['id'] ?>" class="btn btn-primary btn-sm">
                                            ⬇️ Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>