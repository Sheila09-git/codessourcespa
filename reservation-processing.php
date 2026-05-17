<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['id_user'])) {
    header('location:connexion.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $date = $_POST['date_reserv'];
    $time = $_POST['heure_reserv'];
    $people = $_POST['nb_personne'];
    $type = $_POST['type_event'];
    $id_user = $_SESSION['id_user'];

    try {
        $sql = "INSERT INTO reservation (nom, date_reserv, heure_reserv, nb_personne, type_event, id_utilisateur) 
                VALUES (:nom, :date_r, :heure, :nb, :type_e, :id_u)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom'    => $nom,
            'date_r' => $date,
            'heure'  => $time,
            'nb'     => $people,
            'type_e' => $type,
            'id_u'   => $id_user
        ]);
        header("Location: reservation.php?message=Merci $nom, votre table est réservée !");
        exit;
    } catch (PDOException $e) {
        header("Location: reservation.php?message=Erreur: " . $e->getMessage());
        exit;
    }
}
