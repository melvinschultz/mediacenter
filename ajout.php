<?php
include 'inc/header.php';

ajouter();
?>

<h3>Ajouter un film ou une série</h3>

<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <div class="radio">
      <input name="optionsRadios" id="optionsRadios1" value="film" checked="" type="radio">
      <label>Film</label>
    </div>
    <div class="radio">
      <input name="optionsRadios" id="optionsRadios2" value="serie" type="radio">
      <label>Serie</label>
    </div>
    Affiche du film ou de la série :
    <input type="file" name="affiche" id="affiche">
    <input type="text" name="nom" class="form-control" placeholder="Titre du film ou de la série">
    <input type="text" name="annee" class="form-control" placeholder="Année">
    <input type="text" name="realisateur" class="form-control" placeholder="Réalisateur(s)">
    <input type="text" name="actors" class="form-control" placeholder="Acteur(s)">
    <input type="text" name="description" class="form-control" placeholder="Description">
    <input type="text" name="bandeAnnonce" class="form-control" placeholder="URL de la bande annonce">
  </div>
  <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php include 'inc/footer.php' ?>
