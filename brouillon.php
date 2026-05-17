<?php include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO produit(nom, description, prix, photo, id_categorie)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nom'],
        $_POST['description'],
        $_POST['prix'],
        $_POST['image'],
        $_POST['categorie']
    ]);

    header("Location: admin.php");
}
?>
<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['id_user'])) {
    $pdo->prepare("UPDATE utilisateur SET last_activity = NOW() WHERE id_utilisateur = :id")
        ->execute(['id' => $_SESSION['id_user']]);
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location:connexion_admin.php?message=Accès réservé aux administrateurs');
    exit;
}


$query = $pdo->prepare("SELECT username FROM utilisateur WHERE id_utilisateur = :id");
$query->execute(['id' => $_SESSION['id_user']]);
$admin = $query->fetch();


$prenomAdmin = $admin ? $admin['username'] : "Admin";
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4 text-black">
                    <a class="nav-link active" href="admin.php">
                        <img src="image/subway--admin.svg" class="image" />Admin
                    </a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item">
                        <a class="nav-link" href="client_list.php"> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="platsad.php">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php">Activités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newsletter.php">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Messages</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <h2>Ajouter un plat</h2>
                <form method="POST">
                    <input name="nom" placeholder="Nom" type="text"><br><br>
                    <textarea name="description" placeholder="Description"></textarea><br><br>
                    <input name="prix" placeholder="Prix" type="number"><br><br>
                    <input name="image" placeholder="image" type="file"><br><br>

                    <select name="categorie">
                        <option value="entree">Entrée</option>
                        <option value="accompagnement">Accompagnement</option>
                        <option value="salade">Salade</option>
                    </select><br>
                    <button>Ajouter</button>
                </form>
            </main>
        </div>
    </div>
</body>

</html>