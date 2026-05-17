<?php
session_start();
require_once 'db.php';


$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$products = $pdo->query("
    SELECT p.*, pc.categorie_id 
    FROM produit p
    LEFT JOIN produit_categorie pc ON p.id_produit = pc.produit_id
    WHERE p.disponible = 1
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="plat_client.css" />
</head>

<body>
    <nav>
        <div class="bande"></div>
        <a href="accueil.php"><img src="image/logo.png" alt="logo" class="logo"></a>
        <ul class="tableau">
            <li class="dropdown">
                <input type="checkbox" id="menu-toggle" class="menu-checkbox">
                <label for="menu-toggle" class="menu-plat">Notre carte ▾</label>
                <ul class="lacarte">
                    <li><a href="plat.php">PLATS</a></li>
                    <li><a href="menu.php">MENU</a></li>
                </ul>
            </li>
            <li><a href="offre.php">Nos occasions</a></li>
            <li><a href="">Sur nous</a></li>
            <li><a href="recrutement.php">Nous rejoindre</a></li>
            <li class="dropdown">
                <input type="checkbox" id="icone-toggle" class="icone-checkbox">
                <label for="icone-toggle" class="incone"><img src="image/pngtree-outline-person-icon-png-image_1869918.jpg" alt="icone" class="icone"></label>
                <ul class="lecompte">
                    <?php if (isset($_SESSION['email'])) : ?>
                        <li><a href="compte.php">Mon profile</a></li>
                        <li><a href="processing.php?action=logout">Deconnexion</a></li>
                    <?php else : ?>
                        <li><a href="inscription.php">Inscription</a></li>
                        <li><a href="connexion.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="recherche">
                <input type="text" id="search" placeholder="Rechercher un plat..." autocomplete="off">
                <div id="resultats-recherche"></div>
            </li>
        </ul>
    </nav>

    <header class="offre-image">
        <h1>La Gastronomie <br>Américaine</h1>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="category-filter text-center mb-5">
                <a href="#" class="filter-link active mx-2" data-filter="all">TOUS</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="#" class="filter-link mx-2" data-filter="<?= $cat['id_categorie'] ?>">
                        <?= strtoupper(htmlspecialchars($cat['nom'])) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="row g-4 justify-content-center" id="product-grid">
                <?php foreach ($products as $p): ?>
                    <div class="col-6 product-item" data-category="<?= $p['categorie_id'] ?>">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="Image/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                            </div>
                            <div class="product-info p-3">
                                <h3><?= htmlspecialchars($p['nom']) ?></h3>
                                <p class="text-muted small"><?= htmlspecialchars($p['description']) ?></p>
                                <span class="price"><?= number_format($p['prix'], 2) ?>€</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

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

    <script src="theme.js"></script>
    <script src="searchplat.js"></script>
</body>

</html>