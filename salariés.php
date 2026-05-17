<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Sécurité 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: connexion_admin.php?message=Accès réservé aux administrateurs');
    exit;
}

// Mise à jour activité
if (isset($_SESSION['id_user'])) {
    $pdo->prepare("UPDATE utilisateur SET last_activity = NOW() WHERE id_utilisateur = :id")
        ->execute(['id' => $_SESSION['id_user']]);
}

// Nom admin
$query = $pdo->prepare("SELECT username FROM utilisateur WHERE id_utilisateur = :id");
$query->execute(['id' => $_SESSION['id_user']]);
$admin = $query->fetch();
$prenomAdmin = $admin ? $admin['username'] : "Admin";

$message = '';
$messageType = '';

// Ajout salarié
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'])) {
    $nom      = trim($_POST['nom']);
    $prenom   = trim($_POST['prenom']);
    $email    = trim($_POST['email']);
    $poste    = trim($_POST['poste']);
    $mdp_temp = trim($_POST['mot_de_passe']);

    $hash = password_hash($mdp_temp, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateur 
            (username, prenom, email, motdepasse, role, poste, date_embauche)
            VALUES (?, ?, ?, ?, 'salarie', ?, NOW())");
        $stmt->execute([$nom, $prenom, $email, $hash, $poste]);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'luu.alexandre.wong@gmail.com';
        $mail->Password   = 'qupd xnem gluk wcxc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom('noreply@winedining.com', 'Wine Dining');
        $mail->addAddress($email, $nom);
        $mail->Subject = 'Nouveau compte créé';
        $mail->isHTML(true);
        $mail->Body = "
            Bonjour <b>{$nom}</b>,<br><br>
            Votre compte salarié vient d'être créé :<br><br>
            <b>Email :</b> {$email}<br>
            <b>Mot de passe :</b> {$mdp_temp}<br>
            <b>Poste :</b> {$poste}<br><br>
            Connectez-vous sur <a href='https://winedining.eu/connexion.php'>votre espace salarié</a>.<br><br>
            Wine Dining
        ";
        $mail->send();
        $message = "Salarié ajouté et mail envoyé !";
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = "Email déjà utilisé ou erreur : " . $e->getMessage();
        $messageType = 'danger';
    } catch (Exception $e) {
        $message = "Salarié ajouté mais erreur mail : " . $e->getMessage();
        $messageType = 'warning';
    }
}

// Ajout horaire 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'horaire') {
    try {
        $stmt = $pdo->prepare("INSERT INTO emploi_du_temps 
            (salarie_id, jour, heure_debut, heure_fin, poste)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['salarie_id'],
            $_POST['jour'],
            $_POST['heure_debut'],
            $_POST['heure_fin'],
            $_POST['poste']
        ]);

        $stmtSal = $pdo->prepare("SELECT email, username FROM utilisateur WHERE id_utilisateur = ?");
        $stmtSal->execute([$_POST['salarie_id']]);
        $salarie = $stmtSal->fetch();

        $jourFormate = date('l d/m/Y', strtotime($_POST['jour']));

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'luu.alexandre.wong@gmail.com';
        $mail->Password   = 'qupd xnem gluk wcxc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom('noreply@winedining.com', 'Wine Dining');
        $mail->addAddress($salarie['email'], $salarie['username']);
        $mail->Subject = 'Nouvel horaire de travail';
        $mail->isHTML(true);
        $mail->Body = "
            Bonjour <b>{$salarie['username']}</b>,<br><br>
            Un nouvel horaire vient d'être ajouté à votre planning :<br><br>
            <b>Jour :</b> {$jourFormate}<br>
            <b>Début :</b> {$_POST['heure_debut']}<br>
            <b>Fin :</b> {$_POST['heure_fin']}<br>
            <b>Poste :</b> {$_POST['poste']}<br><br>
            Consultez votre planning sur <a href='https://winedining.eu/salarcomp.php'>votre espace salarié</a>.<br><br>
            Wine Dining
        ";
        $mail->send();
        $message = "Horaire ajouté et mail envoyé à {$salarie['username']} !";
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = "Erreur BDD : " . $e->getMessage();
        $messageType = 'danger';
    } catch (Exception $e) {
        $message = "Horaire ajouté mais erreur mail : " . $e->getMessage();
        $messageType = 'warning';
    }
}

// Upload document (champ caché action=document)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'document') {
    $salarie_id   = $_POST['salarie_id'];
    $type         = $_POST['type'];
    $fichier      = $_FILES['document'];
    $nom_original = basename($fichier['name']);
    $extension    = strtolower(pathinfo($nom_original, PATHINFO_EXTENSION));

    if ($extension !== 'pdf') {
        $message = "Seuls les fichiers PDF sont acceptés.";
        $messageType = 'danger';
    } elseif ($fichier['size'] > 5 * 1024 * 1024) {
        $message = "Fichier trop lourd (max 5MB).";
        $messageType = 'danger';
    } else {
        $nom_fichier = uniqid('doc_') . '_' . $salarie_id . '.pdf';
        $chemin = '/var/www/html/uploads/documents/' . $nom_fichier;

        if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
            $stmt = $pdo->prepare("INSERT INTO documents 
                (salarie_id, type, nom_fichier, nom_original) 
                VALUES (?, ?, ?, ?)");
            $stmt->execute([$salarie_id, $type, $nom_fichier, $nom_original]);
            $message = "Document ajouté avec succès !";
            $messageType = 'success';
        } else {
            $message = "Erreur lors de l'upload. Vérifiez que le dossier uploads/documents/ existe.";
            $messageType = 'danger';
        }
    }
}

// Suppression horaire
if (isset($_GET['supprimer_horaire'])) {
    $pdo->prepare("DELETE FROM emploi_du_temps WHERE id = ?")->execute([$_GET['supprimer_horaire']]);
    header('Location: salariés.php');
    exit;
}

// Suppression document
if (isset($_GET['supprimer_doc'])) {
    $stmt = $pdo->prepare("SELECT nom_fichier FROM documents WHERE id = ?");
    $stmt->execute([$_GET['supprimer_doc']]);
    $doc = $stmt->fetch();
    if ($doc) {
        @unlink('/var/www/html/uploads/documents/' . $doc['nom_fichier']);
        $pdo->prepare("DELETE FROM documents WHERE id = ?")->execute([$_GET['supprimer_doc']]);
    }
    header('Location: salariés.php');
    exit;
}

// Récupérer la liste des salariés (SELECT * pour avoir toutes les colonnes)
$salaries = $pdo->query("SELECT * FROM utilisateur WHERE role = 'salarie' ORDER BY username")->fetchAll();

// Récupérer tous les horaires
$horaires = $pdo->query("
    SELECT e.*, u.username, u.poste as poste_salarie
    FROM emploi_du_temps e
    JOIN utilisateur u ON e.salarie_id = u.id_utilisateur
    ORDER BY e.jour ASC, e.heure_debut ASC
")->fetchAll();

// Récupérer tous les documents
$documents = $pdo->query("
    SELECT d.*, u.username 
    FROM documents d
    JOIN utilisateur u ON d.salarie_id = u.id_utilisateur
    ORDER BY d.date_upload DESC
")->fetchAll();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin — Salariés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="stylead.css" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4 text-black">
                    <a class="nav-link active" href="admin.php">
                        <img src="image/subway--admin.svg" class="image" /> Admin
                    </a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item"><a class="nav-link" href="client_list.php">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="platsad.php">Plats</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="logs.php">Activités</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="newsletter.php">Newsletters</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold" href="salariés.php">Salariés</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Messages</a></li>
                </ul>
            </nav>

            <!-- Contenu principal -->
            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">

                <h3 class="mb-4">Gestion des Salariés</h3>

                <!-- Message retour -->
                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- ===== FORMULAIRE AJOUT SALARIÉ ===== -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Ajouter un salarié</div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" placeholder="Dupont" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" placeholder="Sheila" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="sheishei@restaurant.fr" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Poste</label>
                                    <select name="poste" class="form-select" required>
                                        <option value="">-- Choisir un poste --</option>
                                        <option value="Serveur">Serveur</option>
                                        <option value="Cuisinier">Cuisinier</option>
                                        <option value="Chef">Chef</option>
                                        <option value="Caissier">Caissier</option>
                                        <option value="Plongeur">Plongeur</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe temporaire</label>
                                    <input type="text" name="mot_de_passe" class="form-control" placeholder="Ex: Bienvenue2025!" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark mb-3">Ajouter le salarié</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===== LISTE DES SALARIÉS ===== -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Liste des salariés (<?= count($salaries) ?>)</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Poste</th>
                                    <th>Date d'embauche</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($salaries)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">Aucun salarié enregistré.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($salaries as $s): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($s['username']) ?></td>
                                            <td><?= htmlspecialchars($s['prenom'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($s['email'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($s['poste'] ?? '—') ?></td>
                                            <td><?= isset($s['date_embauche']) ? date('d/m/Y', strtotime($s['date_embauche'])) : '—' ?></td>
                                            <td>
                                                <?php if ($s['actif'] ?? 1): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="editsalarié.php?id=<?= $s['id_utilisateur'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                                <a href="deletesalarié.php?id=<?= $s['id_utilisateur'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce salarié ?')">Supprimer</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ===== FORMULAIRE AJOUT HORAIRE ===== -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Assigner un horaire</div>
                    <div class="card-body">
                        <form method="POST">

                            <input type="hidden" name="action" value="horaire">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Salarié</label>
                                    <select name="salarie_id" class="form-select" required>
                                        <option value="">-- Choisir --</option>
                                        <?php foreach ($salaries as $s): ?>
                                            <option value="<?= $s['id_utilisateur'] ?>">
                                                <?= htmlspecialchars($s['username']) ?> — <?= $s['poste'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jour</label>
                                    <input type="date" name="jour" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Début</label>
                                    <input type="time" name="heure_debut" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Fin</label>
                                    <input type="time" name="heure_fin" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Poste ce jour</label>
                                    <select name="poste" class="form-select">
                                        <option value="Serveur">Serveur</option>
                                        <option value="Cuisinier">Cuisinier</option>
                                        <option value="Chef">Chef</option>
                                        <option value="Caissier">Caissier</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Plongeur">Plongeur</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Ajouter l'horaire</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===== TABLEAU DES HORAIRES ===== -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Tous les horaires</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Salarié</th>
                                    <th>Jour</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Poste</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($horaires)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">Aucun horaire enregistré.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($horaires as $h): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($h['username']) ?></td>
                                            <td><?= date('l d/m/Y', strtotime($h['jour'])) ?></td>
                                            <td><?= $h['heure_debut'] ?></td>
                                            <td><?= $h['heure_fin'] ?></td>
                                            <td><?= $h['poste'] ?></td>
                                            <td>
                                                <a href="?supprimer_horaire=<?= $h['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Supprimer cet horaire ?')">
                                                    Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ===== FORMULAIRE UPLOAD DOCUMENT ===== -->
                <h3 class="mb-4">Documents salariés</h3>
                <div class="card mb-4">
                    <div class="card-header fw-bold">Ajouter un document</div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="document">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Salarié</label>
                                    <select name="salarie_id" class="form-select" required>
                                        <option value="">-- Choisir --</option>
                                        <?php foreach ($salaries as $s): ?>
                                            <option value="<?= $s['id_utilisateur'] ?>">
                                                <?= htmlspecialchars($s['username']) ?> — <?= $s['poste'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Type de document</label>
                                    <select name="type" class="form-select" required>
                                        <option value="contrat">Contrat</option>
                                        <option value="fiche_de_paie">Fiche de paie</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fichier PDF</label>
                                    <input type="file" name="document" class="form-control" accept=".pdf" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Uploader</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===== LISTE DOCUMENTS ===== -->
                <div class="card">
                    <div class="card-header fw-bold">Tous les documents (<?= count($documents) ?>)</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Salarié</th>
                                    <th>Type</th>
                                    <th>Fichier</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($documents)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Aucun document.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($documents as $d): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($d['username']) ?></td>
                                            <td>
                                                <?php
                                                $labels = [
                                                    'contrat'       => '<span class="badge bg-primary">Contrat</span>',
                                                    'fiche_de_paie' => '<span class="badge bg-success">Fiche de paie</span>',
                                                    'autre'         => '<span class="badge bg-secondary">Autre</span>'
                                                ];
                                                echo $labels[$d['type']] ?? $d['type'];
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($d['nom_original']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($d['date_upload'])) ?></td>
                                            <td>
                                                <a href="?supprimer_doc=<?= $d['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Supprimer ce document ?')">
                                                    Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>