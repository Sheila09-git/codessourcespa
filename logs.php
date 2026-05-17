<?php

$fichier = "logs.txt";
if (file_exists($fichier)) {
    $logs = file($fichier);
} else {
    $logs = [];
}
$logs = array_reverse($logs);
require_once 'db.php';

$stmt = $pdo->query("
    SELECT username, email,
    CASE 
        WHEN last_activity > (NOW() - INTERVAL 5 MINUTE) THEN 'En ligne'
        ELSE 'Hors ligne'
    END as status
    FROM utilisateur
");

$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
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
                        <a class="nav-link" href="client_list.php"> Clients</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="admin_recrutement.php"> Recrutement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="plat_admin.html">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_menu.php">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php">Activités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-reservation.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_offre.php">Promotions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newsletter.php">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salariés.php">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="message.php">Messages</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 main content p-4">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Logs de connexion</button>
                        <button class="nav-link" id="nav-connect-tab" data-bs-toggle="tab" data-bs-target="#nav-connect" type="button" role="tab">Liste des connectés</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                        <table class="table table-striped table-hover shadow">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>utilisateur</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($logs as $log) {
                                    $data = explode("|", trim($log));
                                    if (count($data) >= 3) {
                                        echo "<tr>";
                                        echo "<td>" . $data[0] . "</td>";
                                        echo "<td>" . $data[1] . "</td>";
                                        echo "<td>" . $data[2] . "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="nav-connect" role="tabpanel">
                        <table class="table table-striped table-hover shadow">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($users as $user) {
                                    $color = ($user['status'] == 'En ligne') ? 'green' : 'red';

                                    echo "<tr>";
                                    echo "<td>{$user['username']}</td>";
                                    echo "<td>{$user['email']}</td>";
                                    echo "<td style='color:$color;'>● {$user['status']}</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>