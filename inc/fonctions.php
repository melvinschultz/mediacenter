<?php
// Connexion à la base de données
try
{
  $bdd = new PDO('mysql:host=localhost;dbname=mediacenter;charset=utf8', 'root', 'firefighter9', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
  die('Erreur : '.$e->getMessage());
}

function search($type)
{
  global $bdd;

  // Recherche sur la page movies.php ou series.php
  if($type == 'films' || $type == 'series')
  {
    if(!$_POST || empty($_POST['search']))
    {
      $req = $bdd->query('SELECT id, img, nom FROM '.$type.' ORDER BY nom');
      while($donnees = $req->fetch())
      {
      ?>
      <a data-toggle="modal" data-target="#descModal" href="<?php echo $type; ?>_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
      <?php
      } // Fin de la boucle pour les affiches
      $req->closeCursor();
    }

    if(!empty($_POST['search']) && !preg_match('#^[a-zâäéèëêïîôöùüûA-ZÂÄÉÈËÊÏÎÔÖÙÜÛ0-9-_.\:]*$i#', $_POST['search'])) // !!!!!! pbm avec accent lettre majuscule à corriger !!!!!!
    {
      $_POST['search'] = ucwords(strtolower($_POST['search']));
      $search = '[[:<:]]'.$_POST['search'].'[[:>:]]'; // on affecte à la variable $search les mots contenu dans la recherche
      $req = $bdd->prepare('SELECT id, img, nom FROM '.$type.' WHERE nom REGEXP :search ORDER BY nom'); // LIKE pour vérifier présence d'un mot
      $req->bindParam(':search', $search, PDO::PARAM_STR);
      $req->execute();

      if($req->rowCount() > 0) // si la requête retourne au moins un des mots
      {
        while($donnees = $req->fetch())
        {
        ?>
        <a data-toggle="modal" data-target="#descModal" href="<?php echo $type; ?>_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
        <?php
        }
      } // Fin de la boucle pour les affiches
      $req->closeCursor();
    }
  }

  // Recherche sur la page home.php
  if($type == 'tous')
  {
    if(!$_POST || empty($_POST['search']))
    {
      $req1 = $bdd->query('SELECT id, img, nom FROM films ORDER BY nom');
      while($donnees = $req1->fetch())
      {
      ?>
      <a data-toggle="modal" data-target="#descModal" href="films_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
      <?php
      } // Fin de la boucle pour les affiches
      $req1->closeCursor();

      $req2 = $bdd->query('SELECT id, img, nom FROM series ORDER BY nom');
      while($donnees = $req2->fetch())
      {
      ?>
      <a data-toggle="modal" data-target="#descModal" href="series_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
      <?php
      } // Fin de la boucle pour les affiches
      $req2->closeCursor();
    }

    if(!empty($_POST['search']) && !preg_match('#^[a-zâäéèëêïîôöùüûA-ZÂÄÉÈËÊÏÎÔÖÙÜÛ0-9-_.\:]*$i#', $_POST['search'])) // !!!!!! pbm avec accent lettre majuscule à corriger !!!!!!
    {
      $_POST['search'] = ucwords(strtolower($_POST['search']));
      $search = '[[:<:]]'.$_POST['search'].'[[:>:]]'; // on affecte à la variable $search les mots contenu dans la recherche

      $req1 = $bdd->prepare('SELECT id, img, nom FROM films WHERE nom REGEXP :search ORDER BY nom'); // LIKE pour vérifier présence d'un mot
      $req1->bindParam(':search', $search, PDO::PARAM_STR);
      $req1->execute();

      $req2 = $bdd->prepare('SELECT id, img, nom FROM series WHERE nom REGEXP :search ORDER BY nom'); // LIKE pour vérifier présence d'un mot
      $req2->bindParam(':search', $search, PDO::PARAM_STR);
      $req2->execute();

      if($req1->rowCount() > 0 || $req2->rowCount() > 0) // si la requête retourne au moins un des mots
      {
        while($donnees = $req1->fetch())
        {
        ?>
        <a data-toggle="modal" data-target="#descModal" href="films_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
        <?php
        }

        while($donnees = $req2->fetch())
        {
        ?>
        <a data-toggle="modal" data-target="#descModal" href="series_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
        <?php
        }
      } // Fin de la boucle pour les affiches
      $req1->closeCursor();
      $req2->closeCursor();
    }
  }
}

// Recherche par caractère (lettre ou chiffre) en fonction de la page
function searchCarac($type)
{
  global $bdd;

  // Recherche sur la page filter_movies.php ou filter_series.php
  if($type == 'films' || $type == 'series')
  {
    if(isset($_GET['carac']))
    {
      $alpha = $_GET['carac']."%";

      // Recherche par chiffres
      if($alpha == '0-9%')
      {
        $req = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM '.$type.' WHERE nom REGEXP "^[0-9]" ORDER BY nom');
        $req->execute();

        if($req->rowCount() > 0)
        {
          while($donnees = $req->fetch())
          {
            ?>
            <a data-toggle="modal" data-target="#descModal" href="<?php echo $type; ?>_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
            <?php
          }
        }
        $req->closeCursor();
      }
      else
      {
        $req = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM '.$type.' WHERE nom LIKE :carac ORDER BY nom');
        $req->bindParam(':carac', $alpha, PDO::PARAM_STR);
        $req->execute();

        if($req->rowCount() > 0)
        {
          while($donnees = $req->fetch())
          {
            ?>
            <a data-toggle="modal" data-target="#descModal" href="<?php echo $type; ?>_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
            <?php
          }
        }
        $req->closeCursor();
      }
    }
  }

  // Recherche sur la page filter_all.php
  if($type == 'tous')
  {
    if(isset($_GET['carac']))
    {
      $alpha = $_GET['carac']."%";

      // Recherche par chiffres
      if($alpha == '0-9%')
      {
        $req1 = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM films WHERE nom REGEXP "^[0-9]" ORDER BY nom');
        $req1->execute();

        $req2 = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM series WHERE nom REGEXP "^[0-9]" ORDER BY nom');
        $req2->execute();

        if($req1->rowCount() > 0 || $req2->rowCount() > 0)
        {
          while($donnees = $req1->fetch())
          {
            ?>
            <a data-toggle="modal" data-target="#descModal" href="films_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
            <?php
          }

          while($donnees = $req2->fetch())
          {
            ?>
            <a data-toggle="modal" data-target="#descModal" href="series_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
            <?php
          }
        }
        $req1->closeCursor();
        $req2->closeCursor();
      }
      else
      {
        $req1 = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM films WHERE nom LIKE :carac ORDER BY nom');
        $req1->bindParam(':carac', $alpha, PDO::PARAM_STR);
        $req1->execute();

        $req2 = $bdd->prepare('SELECT id, nom, img, realisateur, actors, description, bandeAnnonce, annee FROM series WHERE nom LIKE :carac ORDER BY nom');
        $req2->bindParam(':carac', $alpha, PDO::PARAM_STR);
        $req2->execute();

        if($req1->rowCount() > 0 || $req2->rowCount() > 0)
        {
          while($donnees = $req1->fetch())
          {
            ?>
            <a data-toggle="modal" data-target="#descModal" href="films_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
            <?php
          }

          while($donnees = $req2->fetch())
          {
            ?>
            <a data-toggle="modal" data-target="#descModal" href="series_infos.php?id=<?php echo $donnees['id']; ?>" class="affiche"><img src="img/affiches/<?php echo $donnees['img']; ?>" alt="<?php echo htmlspecialchars($donnees['nom']); ?>" title="<?php echo htmlspecialchars($donnees['nom']); ?>" /></a>
            <?php
          }
        }
        $req1->closeCursor();
        $req2->closeCursor();
      }
    }
  }
}

// Pour uploader une affiche dans le formulaire d'ajout
function upload($index, $destination)
{
  // on déplace l'affiche du dossier temporaire à celui défini pour les affiches du site
  return move_uploaded_file($_FILES[$index]['tmp_name'], $destination.$_FILES[$index]['name']);
}

// Ajouter un film ou une série
function ajouter()
{
  global $bdd;
  if(!empty($_POST))
  {
    $errors = array();
    $errorsUpload = array(); // tableau de message d'erreur pour l'affiche
    $success = array();
    $successUpload = array(); // tableau de message de succès pour l'affiche

    if(empty($_POST['nom']))
    {
      $errors['nom'] = "Vous n'avez pas renseigné le titre correctement";
    }

    if($_FILES['affiche']['error'] === UPLOAD_ERR_NO_FILE)
    {
      $errorsUpload[''] = "Vous n'avez pas ajouter d'affiche";
    }

    if(empty($_POST['annee']) || !preg_match('#^[0-9]{4}$#', $_POST['annee']))
    {
      $errors['annee'] = "Vous n'avez pas renseigné l'année correctement";
    }

    if(empty($_POST['realisateur']))
    {
      $errors['realisateur'] = "Vous n'avez pas renseigné le nom du/des réalisateur(s) correctement";
    }

    if(empty($_POST['actors']))
    {
      $errors['actors'] = "Vous n'avez pas renseigné le nom des acteurs correctement";
    }

    if(empty($_POST['description']))
    {
      $errors['description'] = "Vous n'avez pas renseigné la description correctement";
    }

    if(empty($_POST['bandeAnnonce']) || !preg_match('#^(https://www\.){1}[a-z0-9-_.]+\.[a-z]{2,4}#', $_POST['bandeAnnonce']))
    {
      $errors['bandeAnnonce'] = "Vous n'avez pas renseigné l'adresse url de la bande annonce correctement";
    }

    else
    {
      // Si la case film est cochée, on met les données dans la table films
      if(isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == 'film')
      {
        // Si la fonction pour uploader l'affiche a bien eu lieu
        if(upload('affiche', 'img/affiches/'))
        {
          $successUpload[''] = "L'affiche a bien été uploadée !";
        }

        $_POST['nom'] = ucwords($_POST['nom']);
        $req = $bdd->prepare('INSERT INTO films SET img = ?, nom = ?, annee = ?, realisateur = ?, actors = ?, description = ?, bandeAnnonce = ?');
        $req->execute(array(htmlspecialchars($_FILES['affiche']['name']), htmlspecialchars($_POST['nom']), htmlspecialchars($_POST['annee']), htmlspecialchars($_POST['realisateur']), htmlspecialchars($_POST['actors']), htmlspecialchars($_POST['description']), htmlspecialchars($_POST['bandeAnnonce'])));

        $success[''] = "Votre ajout a bien été pris en compte !";
      }

      // Si la case série est cochée, on met les données dans la table series
      if(isset($_POST['optionsRadios']) && $_POST['optionsRadios'] == 'serie')
      {
        // Si la fonction pour uploader l'affiche a bien eu lieu
        if(upload('affiche', 'img/affiches/'))
        {
          $successUpload[''] = "L'affiche a bien été uploadée !";
        }

        $_POST['nom'] = ucwords($_POST['nom']);
        $req = $bdd->prepare('INSERT INTO series SET img = ?, nom = ?, annee = ?, realisateur = ?, actors = ?, description = ?, bandeAnnonce = ?');
        $req->execute(array(htmlspecialchars($_FILES['affiche']['name']), htmlspecialchars($_POST['nom']), htmlspecialchars($_POST['annee']), htmlspecialchars($_POST['realisateur']), htmlspecialchars($_POST['actors']), htmlspecialchars($_POST['description']), htmlspecialchars($_POST['bandeAnnonce'])));

        $success[''] = "Votre ajout a bien été pris en compte !";
      }
    }

    // Affichage des erreurs pour le formulaire
    if(!empty($errors) || !empty($errorsUpload))
    {
    ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach($errors as $error): ?>
            <li><?= $error; ?></li>
          <?php endforeach; ?>
          <?php foreach($errorsUpload as $errorUpload): ?>
            <br><p>Erreur(s) concernant l'upload de l'affiche :</p>
            <li><?= $errorUpload; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php
    }

    // Affichage du succès d'envoie pour le formulaire
    if(!empty($success) || !empty($successUpload))
    {
    ?>
      <div class="alert alert-success">
        <?php foreach($success as $succes): ?>
          <p><?= $succes; ?></p>
        <?php endforeach; ?>
        <?php foreach($successUpload as $succesUpload): ?>
          <p><?= $succesUpload; ?></p>
        <?php endforeach; ?>
      </div>
    <?php
    }
  }
}
?>
