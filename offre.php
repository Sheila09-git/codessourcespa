<?php
session_start();
require_once 'db.php'; 
$stmt = $pdo->query("SELECT * FROM offres ORDER BY id_offre DESC");
$offres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Offres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="offre.css">
</head>

<body class="offre-page-body">
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

   <main class="text-center">
        <div class="offers-wrapper">
            <div class="page-title-container">
                <h1>Nos Offres Spéciales</h1>
            </div>

            <?php foreach ($offres as $index => $o): ?>
                <div class="offer-box text-start <?= ($index % 2 != 0) ? 'flex-row-reverse' : '' ?>">
                    <div class="offer-text-side">
                        <h3><?= htmlspecialchars($o['nom']) ?></h3>
                        <p><?= htmlspecialchars($o['description']) ?></p>
                        <a href="reservation.php" class="btn-gold-action">Profitez-en</a>
                    </div>
                    <div class="offer-image-side">
                        <img src="Image/<?= htmlspecialchars($o['image']) ?>" alt="<?= htmlspecialchars($o['nom']) ?>">
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="button-container">
                <a href="plat.php" class="btn-plats">Voir nos Plats</a>
            </div>
        </div>
    </main>
    <script src="theme.js"></script>
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

<script>
        const burger = document.getElementById('burger-menu');
        const menu = document.querySelector('.tableau');

        burger.addEventListener('click', () => {
            menu.classList.toggle('active');
            burger.classList.toggle('open');
        });
    </script>
</html>