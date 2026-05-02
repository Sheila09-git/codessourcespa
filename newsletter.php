<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newletter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="stylenews.css" />
    <link rel="stylesheet" href="stylead.css">
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
                        <a class="nav-link" href="platsad.php">Plats/menus</a>
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
                        <a class="nav-link" href="newsletter.php">Nesletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                </ul>
            </nav>


            <body>
                <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                    <?php
                    $tab = $_GET['tab'] ?? 'send';
                    ?>

                    <h2 class="mb-3">Newsletter</h2>
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link <?= $tab == 'send' ? 'active' : '' ?>" href="?tab=send">
                                Envoyer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab == 'history' ? 'active' : '' ?>" href="?tab=history">
                                Historique
                            </a>
                        </li>
                    </ul>
                    <!-- TAB : ENVOYER -->
                    <?php if ($tab == 'send'): ?>
                        <div class="container">
                            <div class="card p-4 shadow" style="width:400px; margin:auto;">

                                <h1 class="mt-1 mb-4">Envoyer une newsletter</h1>

                                <form action="processing.php" method="post" enctype="multipart/form-data">

                                    <input type="text" name="sujet" placeholder="Sujet" class="form-control mb-3">

                                    <textarea name="message" placeholder="Message" class="form-control mb-3"></textarea>

                                    <button type="submit" name="envoyer" class="btn btn-primary veri w-100">
                                        Envoyer à tous les clients
                                    </button>

                                </form>

                            </div>
                        </div>

                    <?php endif; ?>
                    <!-- TAB : HISTORIQUE -->
                    <?php if ($tab == 'history'): ?>

                        <?php
                        $sql = "SELECT * FROM newsletter_logs ORDER BY tempsdenvoie DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $logs = $stmt->fetchAll();
                        ?>

                        <div class="card p-3 shadow">

                            <h4 class="mb-3"> Historique des envois</h4>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($log['email']) ?></td>
                                            <td><?= htmlspecialchars($log['subject']) ?></td>
                                            <td>
                                                <?php if ($log['status'] == 'sent'): ?>
                                                    <span class="badge bg-success">Envoyé</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Erreur</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $log['tempsdenvoie'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>
                </main>
            </body>

            </main>
</body>

</html>