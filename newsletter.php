<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newletter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="stylenews.css" />
</head>

<body>
    <main>

        <body>
            <main>
                <div class="position-absolute top-50 start-50 translate-middle">
                    <div class="container">
                        <div class="card p-4 shadow" style="width:400px; margin:auto;">

                            <h1 class="mt-1 mb-4">Envoyer une newsletter</h1>

                            <form action="processing.php" method="post" enctype="multipart/form-data">

                                <input type="text" name="sujet" placeholder="Sujet" class="form-control mb-3">

                                <textarea name="message" placeholder="Message" class="form-control mb-3"></textarea>

                                <button type="submit" name="envoyer" class="btn btn-primary veri w-100">
                                    Envoyer à tous les clients
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
            </main>
        </body>

    </main>
</body>

</html>