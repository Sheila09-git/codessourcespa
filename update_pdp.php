<?php
session_start();
require_once 'db.php';


if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

    $informationsImage = pathinfo($_FILES['photo']['name']);
    $extensionImage = $informationsImage['extension'];
    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];


    if (in_array($extensionImage, $extensionsAutorisees)) {


        $nomImage = 'pdp_' . $_SESSION['id_utilisateur'] . '_' . time() . '.' . $extensionImage;


        if (move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $nomImage)) {

            $q = 'UPDATE utilisateur SET pdp = :pdp WHERE id_utilisateur = :id';
            $statement = $pdo->prepare($q);
            $statement->execute(['pdp' => $nomImage, 'id' => $_SESSION['id_utilisateur']]);

            $_SESSION['pdp'] = 'uploads/' . $nomImage;
            header('location:admin.php?message=Photo de profil mise à jour !');

            exit;
        } else {
            header('location:admin.php?message=Erreur lors de l\'enregistrement du fichier.');
            exit;
        }
    } else {
        header('location:admin.php?message=Format de fichier non autorisé (jpg, png, webp uniquement).');
        exit;
    }
} else {
    header('location:admin.php?message=Veuillez sélectionner une image.');
    exit;
}
?>

