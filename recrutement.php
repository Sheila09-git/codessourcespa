<?php session_start();
require_once 'db.php';
$offres = $pdo->query("SELECT * FROM recrutement ORDER BY id_offre DESC")->fetchAll();
 ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recrutement - Whine Dining</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleacc.css"> <link rel="stylesheet" href="style_occassion.css"> 
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <header>
        <nav>
            <div class="bande"></div>
            <a href="accueil.php">
                <img src="image/logo.webp" alt="logo" class="logo">
            </a>
            <button class="burger" id="burger-menu">
                <span></span><span></span><span></span>
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
                <li class="occasion"><a href="offre.php">Nos occasions</a></li>
                <li class="nous"><a href="surnous.php">Sur nous</a></li>
                <li class="rejoindre"><a href="recrutement.php">Nous rejoindre</a></li>
                <li class="dropdown">
                    <input type="checkbox" id="icone-toggle" class="icone-checkbox">
                    <label for="icone-toggle" class="incone"><img src="image/pngtree-outline-person-icon-png-image_1869918.jpg" alt="icone" class="icone"></label>
                    <ul class="lecompte">
                        <?php if (isset($_SESSION['email'])) { ?>
                            <li class="nav-item"><a class="nav-link" href="compte.php">Mon profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="processing.php?action=logout">Deconnexion</a></li>
                        <?php } else { ?>
                            <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                            <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <section class="hero-recrutement d-flex align-items-center justify-content-center text-center">
            <div class="container">
                <h1 class="display-3 text-white mb-5">Nous Recrutons</h1>
                <div class="filter-bar d-flex justify-content-center gap-3 p-4">
                    <select id="filter-position" class="form-select w-auto">
                        <option value="all" selected>Position</option>
                        <option value="Serveur">Serveur</option>
                        <option value="Cuisinier">Cuisinier</option>
                        <option value="Chef">Chef</option>
                        <option value="Caissier">Caissier</option>
                        <option value="Plongeur">Plongeur</option>
                    </select>
                    <select id="filter-contrat" class="form-select w-auto">
                        <option value="all" selected>Contrat</option>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                    </select>
                    <button class="btn btn-dark-custom px-5" onclick="filtrepost()">Filtrer</button>
                    <script src="filtrepost.js"></script>
                </div>
            </div>
    </section>

    <section class="container mb-5">
    <div class="row justify-content-center">
        <?php foreach ($offres as $index => $offre): 
            $image_path = "image/" . $offre['type_poste'] . ".jpg";
            $flex_direction = ($index % 2 == 0) ? 'flex-md-row' : 'flex-md-row-reverse';
            $text_align = ($index % 2 == 0) ? '' : 'text-end';
            $btn_align = ($index % 2 == 0) ? 'text-end' : 'text-start';
        ?>
            <div class="col-md-10 mb-4 job-card" data-type="<?= $offre['type_poste'] ?>" data-contrat="<?= $offre['contrat'] ?>">
                <div class="card card-offre <?= $flex_direction ?> align-items-center">
                    <div class="img-container">
                        <img src="<?= $image_path ?>" alt="<?= $offre['type_poste'] ?>">
                    </div>
                    <div class="card-body px-4 <?= $text_align ?>">
                        <h3 class="card-title fw-bold"><?= htmlspecialchars($offre['titre']) ?> - <?= $offre['contrat'] ?></h3>
                        <div class="mb-3">
                            <?php 
                            $tags = explode(',', $offre['tags']);
                            foreach ($tags as $tag): if(!empty($tag)): ?>
                                <span class="badge-tag"><?= trim(htmlspecialchars($tag)) ?></span>
                            <?php endif; endforeach; ?>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($offre['description']) ?></p>
                        <div class="<?= $btn_align ?>">
                            <a href="postuler.php?id=<?= $offre['id_offre'] ?>" class="btn-gold">Postuler</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
    </main>

    <footer class="fin h-400 pt-30 d-flex justify-content-around py-3">
        <div class="info1">
            <h2>Whine Dinning</h2>
            <p>242 Rue du Faubourg Saint-Antoine<br>75012 Paris</p>
            <p>Email: <a href="mailto:whine.dinning.admin@gmail.com" target="_blank">whine.dinning.admin@gmail.com</a></p>
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
                © LE RELAIS DU WHINE DINNIG 2022 • Allée des Boulevards - 75456 PARIS • Tel : 01 60 20 92 95 •
            </div>
        </div>
    </div>
</body>

    <script>
        const burger = document.getElementById('burger-menu');
        const menu = document.querySelector('.tableau');

        burger.addEventListener('click', () => {
            menu.classList.toggle('active');
            burger.classList.toggle('open');
        });
    </script>

</html>