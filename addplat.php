<?php include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO produit(nom, description, prix, image, categorie)
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add</title>
</head>

<body>
    <h2>Ajouter un plat</h2>

    <form method="POST">
        <input name="nom" placeholder="Nom"><br>
        <textarea name="description"></textarea><br>
        <input name="prix" placeholder="Prix"><br>
        <input name="image" placeholder="URL image"><br>

        <select name="categorie">
            <option value="entree">Entrée</option>
            <option value="accompagnement">Accompagnement</option>
            <option value="salade">Salade</option>
        </select><br>

        <button>Ajouter</button>
    </form>
</body>

</html>