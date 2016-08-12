<?php include 'inc/fonctions.php'; ?>
<!-- Modal Crédits et mentions légales -->
<?php
// Récupération des films
$req = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM series WHERE id = ?');
$req->execute(array($_GET['id']));
$donnees = $req->fetch();
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?php echo htmlspecialchars($donnees['nom']); ?></h3>
      </div>
      <div class="modal-body">
        <img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" />
        <div class="description">
          <p><strong>Année :</strong> <?php echo htmlspecialchars($donnees['annee']); ?> / <strong>Réalisateur :</strong> <?php echo htmlspecialchars($donnees['realisateur']); ?> / <strong>Acteurs :</strong> <?php echo htmlspecialchars($donnees['actors']); ?></p>
          <p><?php echo htmlspecialchars($donnees['description']); ?></p>
          <a class="bandeAnnonce" href="<?php echo htmlspecialchars($donnees['bandeAnnonce']); ?>" target="_blank">Voir la bande annonce</a>
        </div>
<?php
$req->closeCursor(); // IMPORTANT : on libère le curseur pour la prochaine requête
?>
