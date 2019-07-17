<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../functions/route.php';

// route de test pour tester si le serveur REST fonctionne ou non
route('GET', '^/test', function() {
    echo 'API works';
});

route('GET', '^/articlesCommandes/(?<id>\d+)', function($params) {
    require('../connection/connection.php');
    require('../class/art_comm.php');
    try {
        //------------------------------------------------------------------------------
        // Connexion à la BDD
        $dbclass = new DBClass();
        $connection = $dbclass->getConnection();
        //------------------------------------------------------------------------------
        // On instansie l'objet Art_Comm
        $art_comm = new art_comm($connection);
        // Execution de la fonction ReadArtCommSelonEmplacement qui nous renvoie le résultat de notre requête
        $resultat = $art_comm->readArtCommandeSelonEmplacement($params['id']);
        $compteur = $resultat->rowCount(); // On récupère le nombre de résultat

        if ($compteur > 0) { // On vérifie que le nombre de résultat soit supérieur à 0
            $art_comm = array(); // On crée un tableau
            $art_comm["articles_commande"] = array(); // On fait un tableau de vecteur qui contient les résultats
            $art_comm["count"] = $compteur; // On fait un tableau de vecteur qui contient le nombre de résultat

            while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) { // Tant que l'on peut extraire les enregistrements reçus par l'execution de notre requête SQL
                extract($row); // On extrait le contenu de la ligne

                $p = array(// On crée un vecteur avec ces propriétés ci chaque propriété correspond aux noms de colonnes
                    "idArtComm" => $idArtComm,
                    "designation" => $designation,
                    "quantite" => $quantite,
                    "quantitePrelevee" =>$quantitePrelevee,
                    "code" => $code,
                );
                array_push($art_comm["articles_commande"], $p); // On ajoute la ligne dans notre tableau articles_commande
            }
            echo json_encode($art_comm); // On encode le résultat pour renvoyer sous le format JSON
        } else {
            echo json_encode(
                    array("articles_commande" => array(), "count" => 0)
            );
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode($e);
    }
});

route('GET', '^/postePrelevement', function() {
    try {
        require('../connection/connection.php');
        require('../class/posteprelevement.php');
        //------------------------------------------------------------------------------
        // Connexion à la BDD
        $dbclass = new DBClass();
        $connection = $dbclass->getConnection();
        //------------------------------------------------------------------------------
        // Posteprelevement
        $posteprelevement = new posteprelevement($connection);
        // Fonction ReadPrelevement
        $resultat = $posteprelevement->readPrelevement();
        $compteur = $resultat->rowCount();

        if ($compteur > 0) {

            $posteprelevement = array();
            $posteprelevement["postePrelevement"] = array();
            $posteprelevement["count"] = $compteur;

            while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $p = array(
                    "idPPrel" => $idPPrel,
                    "nomRayonnage" => $nomRayonnage,
                );
                array_push($posteprelevement["postePrelevement"], $p);
            }
            echo json_encode($posteprelevement);
        } else {
            echo json_encode(
                    array("body" => array(), "count" => 0)
            );
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode($e);
    }
});

    route('POST', '^/updateQuantite', function(){
        require('../connection/connection.php');
        require('../class/art_comm.php');
        $code = (int)$_POST['code'];
        if($code == 0) {
            header("HTTP/1.0 500 Internal Server Error");
            echo "The code pass on parameter isn't a numeric value";
        } else {
            try {
                //------------------------------------------------------------------------------
                // Connexion à la BDD
                $dbclass = new DBClass();
                $connection = $dbclass->getConnection();
                //------------------------------------------------------------------------------
                // On instansie l'objet Art_Comm
                $art_comm = new art_comm($connection);
                // Execution de la fonction updateQuantite
                $resultat = $art_comm->updateQuantite($code);
                echo json_encode($resultat) ;
                echo json_encode("Quantity updated");
            } catch (Exception $e) {
                http_response_code(500); 
                echo json_encode($e);
            }
        }
    });


header("HTTP/1.0 404 Not Found");
echo '404 Not Found';
?>