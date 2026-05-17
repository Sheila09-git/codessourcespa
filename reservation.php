<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header('location:connexion.php');
    exit;
}

require_once 'db.php';
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = :id");
$stmt->execute(['id' => $_SESSION['id_user']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="reservation.css">
</head>

<body>
    <header>
        <nav>
            <div class="bande"></div>
            <a href="accueil.php">
                <img src="image/logo.png" alt="logo" class="logo">
            </a>

            <button class="burger" id="burger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="tableau">
                <li class="dropdown">
                    <input type="checkbox" id="menu-toggle" class="menu-checkbox">
                    <label for="menu-toggle" class="menu-plat">Notre carte ▾</label>
                    <ul class="lacarte">
                        <li class="plat"> <a href="plat.php">PLATS</a> </li>
                        <br>
                        <li class="menu"> <a href="menu.php">MENU</a> </li>
                    </ul>
                </li>
                <li class="occasion">
                    <a href="offre.php">Nos occasions</a>
                </li>
                <li class="nous">
                    <a href="surnous.php">Sur nous</a>
                </li>
                <li class="rejoindre">
                    <a href="recrutement.php">Nous rejoindre</a>
                </li>

                <li class="dropdown">
                    <input type="checkbox" id="icone-toggle" class="icone-checkbox">
                    <label for="icone-toggle" class="incone"><img src="image/pngtree-outline-person-icon-png-image_1869918.jpg" alt="icone" class="icone"></label>
                    <ul class="lecompte">
                        <?php if (isset($_SESSION['email'])) { ?>

                            <li class="nav-item">
                                <a class="nav-link" href="compte.php">Mon profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="processing.php?action=logout">Deconnexion</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="inscription.php">Inscription</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="connexion.php">Connexion</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>

        </nav>
    </header>
    <main>
        <div class="Boite p-4">
            <h1 class="text-center mt-2">Réservation</h1>

            <?php if (isset($_GET['message'])): ?>
                <p class="text-danger text-center small"><?= htmlspecialchars($_GET['message']) ?></p>
            <?php endif; ?>

            <form action="reservation-processing.php" method="POST" class="mt-4">

                <div class="row">
                    <div class="col-12 mb-3">
                        <input type="text" id="name" name="nom" class="form-control" placeholder="Votre nom" required>
                    </div>
                    <div class="col-12 mb-3">
                        <input type="date" id="res_date" name="date_reserv" class="form-control" required>
                        <small class="text-muted" style="font-size: 0.7rem;">Format: MM/JJ/AAAA (Fermé le jeudi)</small>
                    </div>

                    <div class="col-12 mb-3">
                        <select name="heure_reserv" class="form-select" required>
                            <option value="" disabled selected>Choisir une heure (19h - 23h)</option>
                            <option value="19:00">19:15</option>
                            <option value="19:30">19:30</option>
                            <option value="19:30">19:45</option>
                            <option value="20:00">20:00</option>
                            <option value="19:30">20:15</option>
                            <option value="20:30">20:30</option>
                            <option value="19:30">20:45</option>
                            <option value="21:00">21:00</option>
                            <option value="19:30">21:15</option>
                            <option value="21:30">21:30</option>
                            <option value="19:30">20:45</option>
                            <option value="22:00">22:00</option>
                            <option value="22:00">22:15</option>
                            <option value="22:00">22:30</option>
                        </select>
                    </div>

                    <div class="col-6 mb-3">
                        <input type="number" name="nb_personne" class="form-control" placeholder="Pers." min="1" required>
                    </div>
                    <div class="col-6 mb-3">
                        <select name="type_event" class="form-select">
                            <option value="Standard">Standard</option>
                            <option value="Annive">Anniversaire</option>
                            <option value="Mariage">Mariage</option>
                            <option value="Soiree">Soirée</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-primary w-100" value="Réserver">
                </div>
            </form>

        </div>
        </div>

        <script>
            const dateInput = document.getElementById('res_date');
            dateInput.min = new Date().toISOString().split("T")[0];
            dateInput.addEventListener('input', function() {
                const day = new Date(this.value).getUTCDay();
                if (day === 4) {
                    alert("Nous sommes fermés le jeudi !");
                    this.value = "";
                }
            });
        </script>
    </main>
</body>
<footer class="fin h-400 pt-30 d-flex justify-content-around py-3">
    <div class="info1">
        <h2>Wine Dinning</h2>
        <p>242 Rue du Faubourg Saint-Antoine<br>75012 Paris</p>

        <p>Email: <a href="mailto:wine.dinning.admin@gmail.com" target="_blank">wine.dinning.admin@gmail.com</a></p>

        <a href="https://www.google.com/maps/@48.840181,2.622687,15z?hl=fr&entry=ttu&g_ep=EgoyMDI2MDIyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="maps btn btn-light px-5 py-2 m-3">Voir l'itinéaire</a>

    </div>

    <div class="info2">
        <h2>Suivez nous sur:</h2>
        <div class="reseau-img">
            <a href="https://www.instagram.com" target="_blank"><img src="image/insta.jpg" alt="insta" class="m-3"></a>
            <a href="https://www.facebook.com" target="_blank"><img src="image/facebook.png" alt="facebook" class="m-3"></a>
            <a href="https://x.com/home?lang=fr" target="_blank"><img src="image/x.webp" alt="x" class="m-3"></a>
        </div>
    </div>




</footer>

<div class="footer-footer py-4">

    <div class="scroll-top">
        <div class="footer-info text-center">
            © LE RELAIS DU WINE DINING 2022 • Allée des Boulevards - 75456 PARIS • Tel : 01 60 20 92 95 •
        </div>
    </div>

</div>

</html>