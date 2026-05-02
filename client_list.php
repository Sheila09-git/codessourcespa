<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM utilisateur ORDER BY id_utilisateur DESC");
$list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylead.css" />
    <link rel="stylesheet" href="stylenews.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
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
                        <a class="nav-link active" href="client_list.php">
                            <img src="image/noun-client-1401473.svg" class="imagees"/>Clients
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="image/emojione--fork-and-knife-with-plate.svg" class="imagees"/>Plats/menus</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><img src="image/noun-reservations-7984943.svg" class="imagees"/>Réservations</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><img src="image/noun-restaurant-8105794.svg" class="imagees"/>Commandes</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><img src="image/noun-client-7615501.svg" class="imagees"/>Salariés</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><img src="image/noun-messages-680438.svg" class="imagees"/>Messages</a>
                    </li>
                </ul>

            </nav>

            <main class="col-md-9 col-lg-10 ms-sm-auto p-4">
                <h1>Tout les utilisateurs</h1>

            <?php 
             if(isset($_GET['message'])) {
        ?>
            <div class="alert alert-danger" role="alert">
        <?= $_GET['message'] ?></div>
        <?php
             }
        ?>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All Users</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Newsletter</button>
            <button class="nav-link" id="nav-candidat-tab" data-bs-toggle="tab" data-bs-target="#nav-candidat" type="button" role="tab">Candidatures</button>
        </div>
    </nav>

<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
        <div class="card mt-3 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Emai</th>
                            <th>Mot de passe</th>
                            <th>Mobile</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $users): ?>
    <tr>
        <td># <?php echo htmlspecialchars($users['id_utilisateur']); ?> </td>
        <td><?php echo htmlspecialchars($users['username']); ?></td>
        <td><?php echo htmlspecialchars($users['email']); ?></td>
        <td>******</td>
        <td><?php echo htmlspecialchars($users['mobile']); ?></td> <td class="text-end">
            <a href="update_client.php?id=<?php echo $users['id_utilisateur']; ?>" class="btn btn-primary">
                Modifier
            </a>
            
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?php echo $users['id_utilisateur']; ?>">Supprimer</button>

            <?php if ($users['is_blocked'] == 0): ?>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalBlock<?php echo $users['id_utilisateur']; ?>">Bloquer</button>
            <?php else: ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUnblock<?php echo $users['id_utilisateur']; ?>">Débloquer</button>
            <?php endif; ?>

            <div class="mt-1">
                <?php if ($users['is_blocked'] == 1): ?>
                    <span class="badge bg-dark">Compte Bloqué</span>
                <?php endif; ?>
                <?php if ($users['is_delete'] == 1): ?>
                    <span class="badge bg-secondary">Compte Supprimé</span>
                <?php endif; ?>
            </div>
            <div class="modal fade" id="modalBlock<?php echo $users['id_utilisateur']; ?>">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Confirmer le blocage</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment bloquer l'utilisateur <strong><?php echo htmlspecialchars($users['username']); ?></strong> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="processing.php?action=block&id=<?php echo $users['id_utilisateur']; ?>" class="btn btn-warning">Confirmer le blocage</a>
            </div>
        </div>
    </div>
</div>

            <div class="modal fade" id="modalUnblock<?php echo $users['id_utilisateur']; ?>">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Confirmer le déblocage</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment débloquer l'utilisateur <strong><?php echo htmlspecialchars($users['username']); ?></strong> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="processing.php?action=unblock&id=<?php echo $users['id_utilisateur']; ?>" class="btn btn-success">Confirmer le déblocage</a>
            </div>
        </div>
    </div>
</div>

            <div class="modal fade" id="modalDelete<?php echo $users['id_utilisateur']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Confirmer la suppression</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Etes vous sur de vouloir supprimer <strong><?php echo htmlspecialchars($users['username']); ?></strong> ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <a href="processing.php?action=delete&id=<?php echo $users['id_utilisateur']; ?>" class="btn btn-danger">Supprimer définitivement</a>
                        </div>
                    </div>
                </div>
            </div>
            
            </td>
    </tr>
                <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

  </div>

                   
<!-- Candidatures -->
<div class="tab-pane fade" id="nav-candidat" role="tabpanel">
    <table class="table table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>Candidat (Email)</th>
                <th>Commentaire</th>
                <th>CV</th>
                <th>Lettre de motivation</th>
            </tr>
        </thead>
        <tbody>

        <?php
          $requete = $pdo->query("SELECT candidatures.*, utilisateur.email 
                        FROM candidatures 
                        LEFT JOIN utilisateur ON candidatures.id_utilisateur = utilisateur.id_utilisateur");
                                    
            while($ligne = $requete->fetch()) { 
            ?>
            <tr>
                <td>
                    <strong>
                        <?= $ligne['email'] ?? 'Visiteur'; ?>
                    </strong>
                </td>

                <td><?= htmlspecialchars($ligne['commentaire']); ?></td>

                <td>
                    <a href="uploads/<?= htmlspecialchars($ligne['cv_path']); ?>"
                       target="_blank"
                       class="btn btn-sm btn-outline-danger">
                        Voir CV
                    </a>
                </td>

                <td>
                    <a href="uploads/<?= htmlspecialchars($ligne['lm_path']); ?>"
                       target="_blank"
                       class="btn btn-sm btn-outline-primary">
                        Voir LM
                    </a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>


</main>

    
</body>
</html>