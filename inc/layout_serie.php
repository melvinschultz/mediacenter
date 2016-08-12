<h3>Mes séries</h3>

<form action="series.php" method="post">
  <div class="form-group">
    <input type="text" id="search" name="search" class="form-control" placeholder="Rechercher une série">
  </div>
  <button type="submit" class="btn btn-primary">Rechercher</button>
</form>

<div class="jumbotron">
  <div class="alphabet">
    <a href="series.php"><strong>TOUS</strong></a>
    <?php
    $i = 0;
    $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z', '0-9');
    for($i = 0; $i < count($alphabet); $i++)
    {
    ?>
    <a href="filter_series.php?carac=<?php echo $alphabet[$i]; ?>"><strong><?php echo $alphabet[$i]; ?></strong></a>
    <?php
    }
    ?>
  </div>
  <div id="liste" class="liste">
