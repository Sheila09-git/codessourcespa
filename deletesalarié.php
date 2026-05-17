<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$id = $_GET['id'];


$stmt = $pdo->prepare("SELECT username, email FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(); // ✅ 2 — fetch() pour avoir les données


$stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$id]);

// Envoi du mail
if ($utilisateur) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'luu.alexandre.wong@gmail.com';
    $mail->Password   = 'qupd xnem gluk wcxc';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('noreply@winedining.com', 'Wine Dining');
    $mail->addAddress($utilisateur['email'], $utilisateur['username']); // 
    $mail->Subject = 'Compte supprimé';
    $mail->isHTML(true);
    $mail->Body = "
        Bonjour <b>{$utilisateur['username']}</b>,<br><br>
        Votre compte salarié vient d'être supprimé.<br><br>
        Pour plus d'informations, veuillez contacter Wine Dining 
        à l'adresse <a href='mailto:luu.alexandre.wong@gmail.com'>luu.alexandre.wong@gmail.com</a>.<br><br>
        Wine Dining
    ";
    $mail->send();
}

header("Location: salariés.php");
exit;
