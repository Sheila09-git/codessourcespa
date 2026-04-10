<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="position-absolute top-50 start-50 translate-middle">
        <div class="Boite"></div>
    </div>
    <main>

        <main>
            <div class="position-absolute top-50 start-50 translate-middle">
                <div class="container">

                    <h1 class="mt-1" style="margin-bottom: 25px;">Réservation</h1>

                    <?php
                    if (isset($_GET['message'])) {
                    ?>
                        <p style="color:red"><?= $_GET['message'] ?></p>
                    <?php
                    }
                    ?>



                    <div class="m-3">
                        <select class="form-select">
                            <option selected disabled>Choisir une date</option>
                            <option value="1">ojd</option>
                            <option value="2">demain</option>
                            <option value="3">semaine</option>
                        </select>
                        <br>
                        <select class="form-select">
                            <option selected disabled>Pick an option</option>
                            <option value="1">Annive</option>
                            <option value="2">Mariage</option>
                            <option value="3">Soiree</option>
                        </select>
                        <br>
                        <input id="nombre" type="number" name="nombre" class="form-control" placeholder="Combien personne" required>
                        <br><br>

                        <br>

                        <input type="submit" class="btn btn-primary verif w-100" value="Réserver">
                    </div>

                </div>

        </main>



</body>

</html>