<?php include 'db.php';

$id = $_GET['id'];

// récupérer le plat
$stmt = $pdo->prepare("SELECT * FROM plats WHERE id=?");
$stmt->execute([$id]);
$plat = $stmt->fetch();

// update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE produit SET nom=?, description=?, prix=?, image=?, categorie=? WHERE id=?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nom'],
        $_POST['description'],
        $_POST['prix'],
        $_POST['image'],
        $_POST['categorie'],
        $id
    ]);

    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit</title>
</head>

<body>
    <h2>Modifier</h2>

    <form method="POST">
        <input name="nom" value="<?= $plat['nom'] ?>"><br>
        <textarea name="description"><?= $plat['description'] ?></textarea><br>
        <input name="prix" value="<?= $plat['prix'] ?>"><br>
        <input name="image" value="<?= $plat['image'] ?>"><br>

        <select name="categorie">
            <option value="entree">Entrée</option>
            <option value="accompagnement">Accompagnement</option>
            <option value="salade">Salade</option>
        </select><br>

        <button>Modifier</button>
    </form>
</body>

</html>
<h2>Modifier</h2>