<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_reservation.css">
</head>

<body>
    <div class="d-flex">
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

        <div class="main-content w-100 p-4">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold m-0">Gestion des Réservations</h2>
                    <div class="total-badge">Total Invités: <span id="total-guests">0</span></div>
                </div>

                <div class="mb-3">
                    <button class="btn btn-outline-danger" onclick="deleteAllReservations()">Tout Supprimer</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Pers.</th>
                                <th>Événement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="admin-table-body">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="reservation-admin.js"></script>
</body>
</html>