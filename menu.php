<?php session_start();
require_once 'db.php';
$sql = "SELECT m.*, GROUP_CONCAT(p.nom SEPARATOR '</li><li>') as items 
        FROM menu m
        LEFT JOIN menu_produit mp ON m.id_menu = mp.menu_id
        LEFT JOIN produit p ON mp.produit_id = p.id_produit
        WHERE m.disponible = 1
        GROUP BY m.id_menu
        ORDER BY m.id_menu DESC";

$menus = $pdo->query($sql)->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="menu.css" />

</head>

<body>
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
    <header class="offre-image">
        <h1>Découvrez nos <br> Spécialités Américaines</h1>


    </header>
    <main class="container my-5">
        <?php if (count($menus) > 0): ?>
            <?php foreach ($menus as $menu): ?>
                <div class="menu-square">
                    <img src="Image/<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['nom']) ?>">

                    <div class="menu-text">
                        <div class="header-row">
                            <h2><?= htmlspecialchars($menu['nom']) ?></h2>
                            <p class="subtitle"><?= htmlspecialchars($menu['description']) ?></p>
                        </div>
                        <hr>
                        <ul class="menu-items">
                            <li><?= $menu['items'] ?: 'Personnalisez votre menu' ?></li>
                        </ul>
                        <p class="price"><?= number_format($menu['prix'], 2) ?>€</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Nos menus arrivent bientôt !</p>
        <?php endif; ?>

        <div class="button-container">
            <a href="plat.php" class="btn-plats">Voir nos Plats</a>
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
    <script>
        const burger = document.getElementById('burger-menu');
        const menu = document.querySelector('.tableau');

        burger.addEventListener('click', () => {
            menu.classList.toggle('active');
            burger.classList.toggle('open');
        });
    </script>
</body>

</html>