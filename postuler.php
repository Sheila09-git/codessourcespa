<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler - Whine Dining</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleacc.css">
    <link rel="stylesheet" href="postuler.css">
</head>
<body>
    <?php
    session_start();
    ?>
    <header>
        </header>

    <main class="container">
        <?php if (!isset($_SESSION['email'])) : ?>
            
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="alert alert-warning text-center shadow-sm" role="alert">
                        <h4 class="alert-heading">Souhaitez-vous suivre votre candidature ?</h4>
                        <p>Pour une meilleure gestion de votre dossier, nous vous conseillons de vous connecter avant de postuler.</p>
                        <hr>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="connexion.php" class="btn btn-outline-dark btn-sm">Se connecter</a>
                            <a href="inscription.php" class="btn btn-dark btn-sm">Créer un compte</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-md-8 form-container">
                <h2 class="text-center mb-4">Déposer votre candidature</h2>
                <p class="text-center mb-5">Rejoignez l'équipe de Whine Dining. Veuillez remplir le formulaire ci-dessous.</p>

                <form action="processing.php" method="POST" enctype="multipart/form-data">
                    

                    <div class="mb-3">
                        <label for="message" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Dites-nous en quelques mots pourquoi vous ?"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="cv" class="form-label">Votre CV (Format PDF uniquement) *</label>
                        <input type="file" class="form-control" id="cv" name="cv" accept=".pdf" required>
                        <div class="pdf-info mt-1">Seul le format PDF est accepté. Taille max : 5Mo.</div>
                    </div>
                     <div class="mb-4">
                        <label for="cv" class="form-label">Lettre de motivation (Format PDF uniquement) *</label>
                        <input type="file" class="form-control" id="lm" name="lm" accept=".pdf" required>
                        <div class="pdf-info mt-1">Seul le format PDF est accepté. Taille max : 5Mo.</div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="postuler" class="btn btn-submit rounded-pill">Envoyer ma candidature</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="text-center py-4">
        <p>© 2024 Whine Dining - Tous droits réservés</p>
    </footer>
</body>
</html>