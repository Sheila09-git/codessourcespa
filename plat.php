<?php session_start(); ?>
<?php
// Simulation BDD (plus tard → MySQL)
$plats = [
    [
        "nom" => "Œufs Mimosa Gourmands",
        "description" => "Œufs mimosa revisités avec une touche gourmande.",
        "prix" => 5.50,
        "image" => "https://via.placeholder.com/300x200",
        "categorie" => "entree"
    ],
    [
        "nom" => "Salade César",
        "description" => "Poulet grillé, parmesan et sauce césar.",
        "prix" => 8.00,
        "image" => "https://via.placeholder.com/300x200",
        "categorie" => "salade"
    ],
    [
        "nom" => "Frites Maison",
        "description" => "Pommes de terre fraîches croustillantes.",
        "prix" => 4.00,
        "image" => "https://via.placeholder.com/300x200",
        "categorie" => "accompagnement"
    ]
];

// Filtre catégorie
$categorie = $_GET['cat'] ?? 'entree';

// Filtrage
$platsFiltres = array_filter($plats, function ($plat) use ($categorie) {
    return $plat['categorie'] === $categorie;
});
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gastronomie Américaine</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleplat.css">

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- HERO -->
    <header class="hero">
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
                                <a class="nav-link" href="profile.php">Mon profile</a>
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

    <!-- FILTRES -->
    <div class="container mt-4 text-center">
        <a href="?cat=entree" class="btn btn-outline-dark <?= $categorie == 'entree' ? 'active' : '' ?>">Entrée</a>
        <a href="?cat=accompagnement" class="btn btn-outline-dark <?= $categorie == 'accompagnement' ? 'active' : '' ?>">Accompagnement</a>
        <a href="?cat=salade" class="btn btn-outline-dark <?= $categorie == 'salade' ? 'active' : '' ?>">Salades</a>
    </div>

    <!-- PRODUITS -->
    <div class="container mt-4">
        <div class="row g-4">

            <?php if (count($platsFiltres) > 0): ?>
                <?php foreach ($platsFiltres as $plat): ?>
                    <div class="col-md-4">
                        <div class="card product-card">
                            <img src="<?= $plat['image'] ?>" class="card-img-top">
                            <div class="card-body">
                                <h5><?= $plat['nom'] ?></h5>
                                <p><?= $plat['description'] ?></p>
                                <div class="text-end">
                                    <strong><?= number_format($plat['prix'], 2) ?>€</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>

                <!-- cartes vides -->
                <div class="col-md-4">
                    <div class="empty-card"></div>
                </div>
                <div class="col-md-4">
                    <div class="empty-card"></div>
                </div>
                <div class="col-md-4">
                    <div class="empty-card"></div>
                </div>

            <?php endif; ?>

        </div>

        <!-- bouton -->
        <div class="text-center mt-5">
            <a href="menu.php" class="btn btn-custom">Voir nos Menu</a>
        </div>
    </div>

</body>

</html>