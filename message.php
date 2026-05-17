<?php
session_start();
require_once 'db.php';


$id_session   = $_SESSION['id_utilisateur'] ?? $_SESSION['id_user'] ?? null;
$role_session = $_SESSION['role'] ?? null;

if (!$id_session) {
    header('location:connexion.php?message=Vous êtes déconnecté, veuillez vous reconnecter !');
    exit;
}

$query = $pdo->prepare("
    SELECT id_utilisateur, username, role, pdp
    FROM utilisateur
    WHERE is_delete = 0
      AND id_utilisateur != :moi
      AND role IN ('admin', 'salarie')
    ORDER BY role ASC, username ASC
");
$query->execute(['moi' => $id_session]);
$contacts = $query->fetchAll();


$groupes = ['admin' => [], 'salarie' => []];
foreach ($contacts as $c) {
    $groupes[$c['role']][] = $c;
}

$labelsGroupes = [
    'admin'   => 'Administrateurs',
    'salarie' => 'Salariés',
];

function labelRole($role) {
    return match($role) {
        'admin'   => 'Administrateur',
        'salarie' => 'Salarié',
        default   => ucfirst($role),
    };
}


$photo = $_SESSION['pdp'] ?? null;
$src   = ($photo && file_exists($photo)) ? $photo : 'image/avatar_default.png';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylead.css">
    <link rel="stylesheet" href="message.css">
</head>
<body class="page-messagerie">
    <div class="container-fluid">
        <div class="row">

            <?php if ($role_session === 'admin'): ?>
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
                        <a class="nav-link" href="plat_admin.html">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_menu.php">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php">Activités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-reservation.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newsletter.php">Newsletters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salariés.php">Salariés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="message.php">Messages</a>
                    </li>
                </ul>
            </nav>
            <?php else: ?>
          
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h4 class="text-center py-4 text-black">
                    <a class="nav-link active" href="salarcom.php" style="color: pink;">
                        <img src="image/subway--admin.svg" class="image" /> Mon compte
                    </a>
                </h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item"><a class="nav-link" href="salaredt.php">Mon emploi du temps</a></li>
                    <li class="nav-item"><a class="nav-link" href="salardocs.php">Mes documents</a></li>
                    <li class="nav-item"><a class="nav-link active" href="message.php">Messagerie</a></li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="processing.php?action=logout">Se déconnecter</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

            <main class="col-md-9 col-lg-10 ms-sm-auto p-4 d-flex justify-content-center align-items-center">
                <div class="messaging-card shadow">


                    <div class="contacts-sidebar">
                        <div class="search-container">
                            <input type="text" id="search_contact" class="search-bar" placeholder="Chercher un contact..." onkeyup="searchContacts()">
                        </div>

                        <div class="contacts-list" id="contacts_list">
                            <?php foreach ($groupes as $role => $liste): ?>
                                <?php if (empty($liste)) continue; ?>

                                <div class="groupe-label"><?= $labelsGroupes[$role] ?></div>

                                <?php foreach ($liste as $contact):
                                    $avatarSrc = $contact['pdp'] ? 'uploads/' . $contact['pdp'] : 'image/avatar_default.png';
                                ?>
                                <div class="contact-item" onclick="selectContact(<?= $contact['id_utilisateur'] ?>, '<?= htmlspecialchars($contact['username']) ?>', '<?= labelRole($contact['role']) ?>')">
                                    <img src="<?= htmlspecialchars($avatarSrc) ?>"
                                         style="width:45px; height:45px; border-radius:50%; object-fit:cover; margin-right:12px; flex-shrink:0;"
                                         onerror="this.src='image/avatar_default.png'">
                                    <div class="contact-info">
                                        <p class="name"><?= htmlspecialchars($contact['username']) ?></p>
                                        <span class="role-badge <?= $contact['role'] ?>"><?= labelRole($contact['role']) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                            <?php endforeach; ?>
                        </div>
                    </div>


                    <div class="chat-area">
                        <div id="salon-state">
                            <img src="image/685887.png" width="80">
                            <h3>Sélectionnez une discussion</h3>
                            <p>Choisissez un contact pour commencer à discuter</p>
                        </div>

                        <div id="chat-content" style="display: none; flex-direction: column; height: 100%;">
                            <div id="chat-header" class="chat-header">
                                <div class="avatar-header" id="header-avatar"></div>
                                <div>
                                    <span id="active-contact-name" class="fw-bold"></span><br>
                                    <small id="active-contact-role" style="color:#aaa; font-size:12px;"></small>
                                </div>
                            </div>

                            <div id="messages-container" class="messages-display flex-grow-1"></div>

                            <div class="chat-input-container">
                                <div class="input-wrapper">
                                    <input type="text" id="message-input" placeholder="Message..." onkeyup="gererTouche(event)">
                                    <button class="btn-send" onclick="envoyerMessage()">
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 21l21-9L2 3v7l15 2-15 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
        const MON_ID   = <?= (int)$id_session ?>;
        const MA_PHOTO = "<?= htmlspecialchars($src) ?>";
    </script>
    <script src="message.js"></script>
</body>
</html>