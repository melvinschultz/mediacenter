<?php

include 'fonctions.php';

if(empty($_POST['search']))
{
    search($type); // ne marche pas (à revoir)
}
else
{
    // s'il y a quelque chose dans la barre de recherche et dans la variable $type
    if(isset($_POST['search']) && isset($_POST['type']))
    {
        $search = $_POST['search'];
        $type = $_POST['type'];
        $data = array(); // on crée un tableau vide qui recevra les données

        //$data[] = array(
        //    'id' => 222,
        //    'img' => "NULL",
        //    'nom' => "TEST"
        //);

        // si le type est 'films' ou 'series'
        if($type == 'films' || $type == 'series')
        {
            $search = ucwords(strtolower($search));
            $search = '[[:<:]]' . $search; // on affecte à la variable $search les mots contenu dans la recherche

            // on fait une requete préparée qui va chercher les données correspondant à la recherche
            $req = $bdd->prepare('SELECT id, img, nom FROM '.$type.' WHERE nom REGEXP :search ORDER BY nom');
            $req->bindParam(':search', $search, PDO::PARAM_STR);
            $req->execute();

            //$data[0]['img'] = $req->rowCount();

            if ($req->rowCount() > 0) // si la requête retourne au moins un des mots
            {
                // tant qu'il y a de la donnée
                while($donnees = $req->fetch())
                {
                    // on met dans le tableau $data[]
                    $data[] = array(
                        'id' => $donnees['id'],
                        'img' => $donnees['img'],
                        'nom' => $donnees['nom']
                    );
                } // Fin de la boucle pour les affiches
            }
            $req->closeCursor();
        }

        // si le type est 'films' et 'series'
        if($type == 'tous')
        {
            $search = ucwords(strtolower($search));
            $search = '[[:<:]]' . $search; // on affecte à la variable $search les mots contenu dans la recherche

            // on fait une requete préparée qui va chercher les données correspondant à la recherche
            $req1 = $bdd->prepare('SELECT id, img, nom FROM films WHERE nom REGEXP :search ORDER BY nom');
            $req1->bindParam(':search', $search, PDO::PARAM_STR);
            $req1->execute();

            //$data[0]['img'] = $req1->rowCount();

            // on fait une requete préparée qui va chercher les données correspondant à la recherche
            $req2 = $bdd->prepare('SELECT id, img, nom FROM series WHERE nom REGEXP :search ORDER BY nom');
            $req2->bindParam(':search', $search, PDO::PARAM_STR);
            $req2->execute();

            //$data[0]['img'] = $req2->rowCount();

            if ($req1->rowCount() > 0 || $req2->rowCount() > 0) // si la requête retourne au moins un des mots
            {
                // tant qu'il y a de la donnée
                while($donnees = $req1->fetch())
                {
                    // on met dans le tableau $data[]
                    $data[] = array(
                        'id' => $donnees['id'],
                        'img' => $donnees['img'],
                        'nom' => $donnees['nom']
                    );
                } // Fin de la boucle pour les affiches

                // tant qu'il y a de la donnée
                while($donnees = $req2->fetch())
                {
                    // on met dans le tableau $data[]
                    $data[] = array(
                        'id' => $donnees['id'],
                        'img' => $donnees['img'],
                        'nom' => $donnees['nom']
                    );
                } // Fin de la boucle pour les affiches
            }
            $req1->closeCursor();
            $req2->closeCursor();
        }

        echo json_encode($data);
    }
}
?>
