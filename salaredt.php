<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'salarie') {
    header('Location: connexion.php');
    exit;
}
require_once 'db.php';


$semaine_offset = isset($_GET['semaine']) ? (int)$_GET['semaine'] : 0;
$debut_semaine  = date('Y-m-d', strtotime("monday this week +{$semaine_offset} week"));
$fin_semaine    = date('Y-m-d', strtotime("sunday this week +{$semaine_offset} week"));

$stmt = $pdo->prepare("
    SELECT * FROM emploi_du_temps
    WHERE salarie_id = :id 
    AND jour BETWEEN :debut AND :fin
    ORDER BY jour ASC, heure_debut ASC
");
$stmt->execute([
    'id'    => $_SESSION['id_user'],
    'debut' => $debut_semaine,
    'fin'   => $fin_semaine
]);
$horaires = $stmt->fetchAll();


$planning = [];
foreach ($horaires as $h) {
    $planning[$h['jour']][] = $h;
}


$total_minutes = 0;
foreach ($horaires as $h) {
    $debut = strtotime($h['heure_debut']);
    $fin   = strtotime($h['heure_fin']);
    $total_minutes += ($fin - $debut) / 60;
}
$total_heures = floor($total_minutes / 60);
$reste_minutes = $total_minutes % 60;
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mon emploi du temps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4">
                    <a class="nav-link active" href="salarcom.php">Mon compte</a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item"><a class="nav-link active fw-bold" href="salaredt.php">Mon emploi du temps</a></li>
                    <li class="nav-item"><a class="nav-link" href="salardocs.php">Mes documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="message.php">Messagerie</a></li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="processing.php?action=logout">Se déconnecter</a>
                    </li>
                </ul>
            </nav>

            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3> Mon emploi du temps</h3>
                   
                    <div>
                        <a href="?semaine=<?= $semaine_offset - 1 ?>" class="btn btn-outline-secondary btn-sm">← Semaine préc.</a>
                        <span class="mx-2 fw-bold text-white">
                            <?= date('d/m', strtotime($debut_semaine)) ?> — <?= date('d/m/Y', strtotime($fin_semaine)) ?>
                        </span>
                        <a href="?semaine=<?= $semaine_offset + 1 ?>" class="btn btn-outline-secondary btn-sm">Semaine suiv. →</a>
                    </div>
                </div>

                
                <div class="alert alert-info">
                    Total cette semaine : <strong><?= $total_heures ?>h<?= $reste_minutes > 0 ? $reste_minutes : '' ?></strong>
                </div>

                <?php
                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                for ($i = 0; $i < 7; $i++):
                    $date_jour = date('Y-m-d', strtotime("monday this week +{$semaine_offset} week +{$i} days"));
                    $label = $jours[$i] . ' ' . date('d/m', strtotime($date_jour));
                    $est_aujourd_hui = ($date_jour === date('Y-m-d'));
                ?>
                    <div class="card mb-2 <?= $est_aujourd_hui ? 'border-primary' : '' ?>">
                        <div class="card-header <?= $est_aujourd_hui ? 'bg-primary text-white' : '' ?>">
                            <?= $label ?> <?= $est_aujourd_hui ? '← Aujourd\'hui' : '' ?>
                        </div>
                        <div class="card-body py-2">
                            <?php if (isset($planning[$date_jour])): ?>
                                <?php foreach ($planning[$date_jour] as $h): ?>
                                    <span class="badge bg-success me-2">
                                        <?= $h['heure_debut'] ?> → <?= $h['heure_fin'] ?>
                                    </span>
                                    <span class="text-muted"><?= htmlspecialchars($h['poste']) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small">Repos </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>