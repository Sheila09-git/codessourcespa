<!DOCTYPE html>
<html lang="fr-fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whine Dinning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleacc.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <header>
        <nav>
            <div class="bande"></div>
            <a href="file:///C:/Users/USER/Desktop/PA25-26/PA/site_web/PA/accuiel.html">
                <img src="image/c008f19d-8c1b-46ab-bef4-33551d9cd01f.webp" alt="logo" class="logo">
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
                        <li class="plat"> <a href="">PLATS</a> </li>
                        <br>
                        <li class="menu"> <a href="">MENU</a> </li>
                    </ul>
                </li>
                <li class="occasion">
                    <a href="">Nos occasions</a>
                </li>
                <li class="nous">
                    <a href="">Sur nous</a>
                </li>
                <li class="rejoindre">
                    <a href="">Nous rejoindre</a>
                </li>

                <li class="dropdown">
                    <input type="checkbox" id="icone-toggle" class="icone-checkbox">
                    <label for="icone-toggle" class="incone"><img src="image/pngtree-outline-person-icon-png-image_1869918.jpg" alt="icone" class="icone"></label>
                    <ul class="lecompte">
                        <li class="compte"><a href="connexion.php">Mon compte</a></li>
                        <br>
                        <li class="connexion-deconnexio"><a href="inscription.php">Inscription</a></li>
                    </ul>
                </li>
            </ul>

        </nav>
    </header>
    <main>
        <section class="hero position-relative">
            <div>
                <img src="image/Designersanstext.png" class="img-fluid w-100 vh-100" alt="pageaccuiel">
                <div class="pretitre position-absolute top-50 end-0 translate-middle-y me-5 text-end">
                    <h1>UNE SOIRÉE D'EXCEPTION</h1>
                    <br>
                    <p>RÉSERVEZ VOTRE TABLE</p>
                </div>
            </div>
        </section>
        <section class="section-horaire position-relative">
            <div>
                <img src="image/reserve.png" class="vue img-fluid w-100 vh-100" alt="pagereservation">
                <div class="horaire position-absolute end-0 me-5 text-end" style="top: 20%">
                    <div class="ligne-doree w-75 my-2 mx-auto " style="height: 4px;"></div>
                    <div class="ligne-doree w-100 my-2 mx-auto " style="height: 4px;"></div>
                    <h2>NOS HORAIRES</h2>
                    <br>
                    <br>
                    <br>
                    <br>
                    <ul class="heure list-unstyled">
                        <li>LUNDI 18h30-23h30H</li>
                        <li>MARDI 18h30-23h30H</li>
                        <li>MERCREDI 18h30-23h30H</li>
                        <li>JEUDI 18h30-23h30H</li>
                        <li>VENDREDI 18h30-23h30H</li>
                        <li>SAMEDI 18h30-23h30H</li>
                        <li>DIMANCHE 18h30-23h30H</li>
                    </ul>
                    <br>
                    <a href="" class="reserver btn btn-light px-5 py-2">RESERVER</a>
                    <div class="ligne-doree w-100 my-2 mx-auto " style="height: 4px;"></div>
                    <div class="ligne-doree w-75 my-2 mx-auto " style="height: 4px;"></div>
                </div>
            </div>
        </section>

        <section class="carousel position-relative">
            <div class="carde d-flex justify-content-center align-items-center p-5">


                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <div class="carde-or overflow-hidden ">

                                <div class="d-flex flex-column flex-md-row align-items-center bg-white" style="height: 400px;">
                                    <div class="deuxtitre z-3 w-30 ms-5 m-5" style="right: 30%;">
                                        <h2>Profitez de notre<br>offre de famille <br>dès maintenant!</h2>
                                        <br>
                                        <a href="" class="btn-profitez btn btn-light px-5 py-2">Profitez-en</a>
                                    </div>
                                    <div class="image-container position-relative h-100">
                                        <img src="image/27853949000_e00505c082_b.webp" alt="carrousel1" class="h-auto d-block" style="object-fit: cover;" style="max-width: 500px;">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="carousel-item">
                            <div class="carde-or overflow-hidden">
                                <div class="d-flex flex-column flex-md-row align-items-center bg-white" style="height: 400px;">
                                    <div class="deuxtitre z-3 w-30 ms-5 m-5" style="right: 30%;">
                                        <h2>Profitez de notre<br>offre d'anniversaire <br>dès maintenant!</h2>
                                        <br>
                                        <a href="" class="btn-profitez btn btn-light px-5 py-2">Profitez-en</a>
                                    </div>
                                    <div class="image-container position-relative h-100%">
                                        <img src="image/images_2.webp" alt="carrousel2" class="gateau h-100% w-100%" style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="carde-or overflow-hidden">
                                <div class="d-flex flex-column flex-md-row align-items-center bg-white" style="height: 400px;">
                                    <div class="deuxtitre z-3 w-30 ms-5 m-5" style="right: 30%;">
                                        <h2>Profitez de notre<br>offre exclusive <br>dès maintenant!</h2>
                                        <br>
                                        <a href="" class="btn-profitez btn btn-light px-5 py-2">Profitez-en</a>
                                    </div>
                                    <div class="image-container position-relative h-100">
                                        <img src="image/download_9.webp" alt="carrousel3" class="tarte h-100 w-auto" style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
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

</html>