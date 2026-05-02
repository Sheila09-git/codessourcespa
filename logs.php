<?php

$fichier = "logs.txt";
if (file_exists($fichier)) {
    $logs = file($fichier);
} else {
    $logs = [];
}
$logs = array_reverse($logs);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    w
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
                        <a class="nav-link" href="#">Plats/menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php">Activités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newsletter.php">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 main content p-4">
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
            </main>
        </div>
    </div>
</body>

</html>