<?php http_response_code(500); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 500</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="vh-100 d-flex align-items-center justify-content-center bg-light">
    <header>
        <style>
        .card-custom {
            width: 90%; 
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
    </header>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm p-5">
                <h1 class="display-1 fw-bold text-danger mb-2">Erreur 500</h1>
                <p class="text-muted mb-4">
                   Une erreur interne est survenue.
                   <br>
                   Merci de réessayer plus tard.
                </p>
                <div class="d-grid gap-2">
                    <a href="accueil.php" class="btn btn-primary btn-lg">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>