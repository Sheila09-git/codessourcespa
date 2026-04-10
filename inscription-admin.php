<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <div class="position-absolute top-50 start-50 translate-middle">
        <div style="width: 500px; height: 650px; background: white; border-radius: 10px; outline: 3px solid black;">
        </div>
    </div>

    <main>

        <main>
            <div class="position-absolute top-50 start-50 translate-middle">
                <div class="container">
                    <div class="card p-4 shadow" style="width:400px; margin:auto;">
                        <h1 class="mt-1" style="margin-bottom: 25px;">Inscription Admin</h1>
                    </div>
                    <?php
                    if (isset($_GET['message'])) {
                    ?>
                        <p style="color:red"><?= $_GET['message'] ?></p>
                    <?php
                    }
                    ?>


                    <form action="inscription_admin_processing.php" method="post" enctype="multipart/form-data">
                        <div class="m-3">
                            <input id="username" type="text" name="username" class="form-control " placeholder="Psuedo" value="<?= isset($_COOKIE['username']) ? $_COOKIE['username'] : '' ?>" required>
                            <br>
                            <br>
                            <input id="email" type="email" name="email" class="form-control " placeholder="Mettre votre mail" value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : '' ?>" required>
                            <br>
                            <br>
                            <input id="mot_de_passe" type="password" name="mot_de_passe" class="form-control " placeholder=" mot de passe" required>
                            <br><br>
                            <input id="mot_de_passe" type="password" name="confirme_mdp" class="form-control " placeholder="Vérifier mot de passe" required>

                            <div class="mt-2 mb-2">

                                <a href="connexion.php">Se connecter</a>

                            </div>

                        </div>
                        <input type="submit" name="inscription_admin" class="btn btn-primary veri w-100" value="S'inscrire">
                </div>
                </form>
            </div>

        </main>



</body>

</html>