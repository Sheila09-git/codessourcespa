<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (isset($_SESSION["last_login_timestamp"])) {
    if ((time() - $_SESSION['last_login_timestamp']) > 600) {
        session_unset();
        session_destroy();
        header("location:connexion.php?message=Session expirée ! REconnecter vous !");
        exit;
    } else {
        $_SESSION['last_login_timestamp'] = time();
    }
}
require_once 'db.php';
require_once 'captcha-processing.php';
include "logger.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
//Logs page d'inscription

// --- INSCRIPTION ---
if (isset($_POST['inscription'])) {
    if (!empty($_POST['email'])) {
        setcookie('email', $_POST['email'], time() + 24 * 3600);
    }

    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['mot_de_passe']) || empty($_POST['mobile'])) {
        header('location:inscription.php?message=Completer TOUT les cases !');
        exit;
    }

    if ($_POST['mot_de_passe'] !== $_POST['confirme_mdp']) {
        header('location:inscription.php?message=Les mots de passe ne correspondent pas !');
        exit;
    }

    if (!verifyCaptcha($_POST)) {
        header('location:inscription.php?message=Veuillez valider le puzzle CAPTCHA !');
        exit;
    }

    // Correction : On cherche 'username' et pas 'prenom'
    $statement = $pdo->prepare('SELECT id_utilisateur FROM utilisateur WHERE username = :username');
    $statement->execute(['username' => $_POST['username']]);
    if ($statement->fetch()) {
        header('location:inscription.php?message=Ce pseudo est déjà utilisé !');
        exit;
    }

    try {
        $token = bin2hex(random_bytes(25));
        $sql = "INSERT INTO utilisateur (username, email, motdepasse, telephone, token, confirme) 
        VALUES (:nom, :mail, :password, :mobile, :token, 0)";
        $query = $pdo->prepare($sql);
        $query->execute([
            'nom'      => $_POST['username'],
            'mail'     => $_POST['email'],
            'password' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
            'mobile'   => $_POST['mobile'],
            'token'    => $token
        ]);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'luu.alexandre.wong@gmail.com';
        $mail->Password   = 'qupd xnem gluk wcxc'; // ATTENTION : Changez ce mot de passe après vos tests !
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('noreply@whinedining.com', 'Wine Dining');
        $mail->addAddress($_POST['email']);
        $mail->Subject = 'Confirmez votre inscription';
        $url = "http://135.125.102.228/confirmation.php?token=$token";
        $mail->isHTML(true);
        $mail->Body = "Cliquez ici pour valider votre compte : <a href='$url'>Confirmer</a>";
        $mail->send();
        writeLog("Inscription réussie", $_POST['email']);
        header('location:inscription.php?message=Consultez votre boîte mail pour valider !');
        exit;
    } catch (Exception $e) {
        writeLog("Erreur envoi mail inscription", $_POST['email']);
        header('location:inscription.php?message=Erreur mail');
        exit;
    }
}

// --- CONNEXION ---

if (isset($_POST['connexion'])) {
    if (!verifyCaptcha($_POST)) {
        header('location:connexion.php?message=Veuillez valider le puzzle CAPTCHA !');
        exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $_POST['email']]);
    $user = $stmt->fetch();

    if ($user) {
        if (isset($user['is_delete']) && $user['is_delete'] == 1) {
            header('location:account_delete.php');
            exit;
        }
        if (isset($user['is_blocked']) && $user['is_blocked'] == 1) {
            header('location:account_delete.php');
            exit;
        }
    }
    if ($user && password_verify($_POST['mot_de_passe'], $user['motdepasse'])) {
        $_SESSION['id_user'] = $user['id_utilisateur'];
        $_SESSION['email'] = $user['email'];

        $_SESSION['last_login_timestamp'] = time();

        header('location:accueil.php');
        exit;
    } else {
        header('location:inscription.php?message=Erreur d\'identifiants');
        exit;
    }
}


//NEWSLETTERS"


require __DIR__ . '/vendor/autoload.php';



if (isset($_POST['envoyer'])) {

    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    $sql = "SELECT username, email FROM utilisateur";
    $result = $pdo->query($sql);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username   = 'luu.alexandre.wong@gmail.com';
        $mail->Password   = 'qupd xnem gluk wcxc';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('noreply@whinedining.com', 'Whine Dining');
        $mail->isHTML(true);
        while ($row = $result->fetch()) {

            $username = $row['username'];
            $email = $row['email'];

            try {
                $mail->clearAddresses(); //  très important
                $mail->addAddress($email, $username);

                $mail->Subject = $sujet;
                $mail->Body = "Bonjour $username,<br><br>$message<br><br>Wine Dining";

                $mail->send();


                //  log succès
                $stmt = $pdo->prepare("INSERT INTO newsletter_logs (email, subject, status) VALUES (?, ?, ?)");
                $stmt->execute([$email, $sujet, 'sent']);
            } catch (Exception $e) {


                //  log erreur
                $stmt = $pdo->prepare("INSERT INTO newsletter_logs (email, subject, status) VALUES (?, ?, ?)");
                $stmt->execute([$email, $sujet, 'error']);
            }

            sleep(1); // évite blocage Gmail
        }
        header("Location: newsletter.php?tab=history");
        exit;
    } catch (Exception $e) {
        echo "Erreur globale: {$mail->ErrorInfo}";
    }
}

// --- MODIFICATION PSEUDO ---
if (isset($_POST['update_username'])) {
    $username = $_POST['username'];
    $user_id = $_SESSION['id_user'];

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET username = :username WHERE id_utilisateur = :id");
        $stmt->execute(['username' => $username, 'id' => $user_id]);
        header('location:profile.php?message=Modification réussie !');
        exit;
    } catch (Exception $e) {
        header('location:profile.php?message=Echec !');
        exit;
    }
}

// --- MODIFICATION EMAIL ---
if (isset($_POST['update_email'])) {
    $email = $_POST['email'];
    $user_id = $_SESSION['id_user']; // Changé user_id en id_user pour être cohérent

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email AND id_utilisateur != :id");
    $stmt->execute(['email' => $email, 'id' => $user_id]);

    if ($stmt->fetch()) {
        header('location:profile.php?message=Cet email est déja utilisé');
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET email = :email WHERE id_utilisateur = :id");
        $stmt->execute(['email' => $email, 'id' => $user_id]);
        header('location:profile.php?message=Email modifier !');
        exit;
    } catch (Exception $e) {
        header('location:profile.php?message=Echec email !');
        exit;
    }
}

// --- MODIFICATION MOT DE PASSE ---
if (isset($_POST['update_account'])) {
    $newPassword = $_POST['mot_de_passe'];
    $userId = $_SESSION['id_user'];

    if (!empty($newPassword)) {
        if (strlen($newPassword) < 8 || strlen($newPassword) > 16) {
            header('location:profile.php?message=Le mot de passe doit faire entre 8 et 16 caractères');
            exit;
        }
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("UPDATE utilisateur SET motdepasse = :mdp WHERE id_utilisateur = :id");
            $stmt->execute(['mdp' => $hashedPassword, 'id' => $userId]);
            header('location:profile.php?message=Modification du mdp réussi !');
            exit;
        } catch (Exception $e) {
            header('location:profile.php?message=Erreur !');
            exit;
        }
    }
}

// --- LOGOUT ---
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $email = $_SESSION['email'] ?? 'inconnu';
    writeLog("Déconnexion", $email);

    session_unset();
    session_destroy();
    header('Location: accueil.php');
    exit;
}

// --- DELETE (ADMIN) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $email = $_SESSION['email'] ?? 'admin inconnu';
    $id = $_GET['id'];
    writeLog("Suppression utilisateur ID " . $id, $email);
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET is_delete = 1 WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $id]);
        header('location:client_list.php?message=Suppression réussie');
        exit;
    } catch (Exception $e) {
        header('location:client_list.php?message=Erreur lors de la suppression');
        exit;
    }
}
// bloquer un compte depuis l'admin
if (isset($_GET['action']) && $_GET['action'] == 'block' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE utilisateur SET is_blocked = 1 WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $id]);
        header('location:client_list.php?message=Utilisateur bloqué avec succès');
        exit;
    } catch (Exception $e) {
        header('location:client_list.php?message=Erreur lors du blocage');
        exit;
    }
}
// débloquer le compte

if (isset($_GET['action']) && $_GET['action'] == 'unblock' && isset($_GET['id'])){
    $id = $_GET['id'];
try {
    $stmt = $pdo->prepare("UPDATE utilisateur SET is_blocked = 0 WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $id]);
    header('location:client_list.php?message=Utilisateur débloqué ! ');
    exit;
} catch (Exception $e) {
    header('location:client_list.php?message=Erreur lors du déblocage');
        exit;
}

}
// envoi de CV
if (isset($_POST['postuler'])) {

    $commentaire = $_POST['message'] ?? '';
    $id_utilisateur = $_SESSION['id_utilisateur'] ?? null;

    $dossier = "uploads/";
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }


    if (isset($_FILES['cv'], $_FILES['lm'])) {


        $nom_cv = uniqid('cv_') . '_' . basename($_FILES['cv']['name']);
        $nom_lm = uniqid('lm_') . '_' . basename($_FILES['lm']['name']);

        if (
            move_uploaded_file($_FILES['cv']['tmp_name'], $dossier . $nom_cv) &&
            move_uploaded_file($_FILES['lm']['tmp_name'], $dossier . $nom_lm)
        ) {

            $sql = "INSERT INTO candidatures (id_utilisateur, commentaire, cv_path, lm_path)
                    VALUES (?, ?, ?, ?)";

            $requete = $pdo->prepare($sql);
            $requete->execute([
                $id_utilisateur,
                $commentaire,
                $nom_cv,
                $nom_lm
            ]);

            header('Location:recrutement.php?message=Candidature envoyée !');
            exit;
        }
    }
}
