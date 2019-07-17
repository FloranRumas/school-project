<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//------------------------------------------------------------------------------
require('../connection/connection.php');
require('../class/article.php');
//------------------------------------------------------------------------------
// Connexion à la BDD
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
//------------------------------------------------------------------------------
// On instansie l'objet Article
$article = new Article($connection);
// Execution de la fonction ReadArticle qui nous renvoie l'article que l'on souhaite

$resultat = $article->readUnArticle($_GET['idArticle']);
$compteur = $resultat->rowCount();

if ($compteur > 0) { // On vérifie que le nombre de résultat soit supérieur à 0


    $article = array(); // On crée un tableau
    $article["article"] = array(); // On fait un tableau de vecteur qui contient les résultats
    $article["count"] = $compteur; // On stock le nombre de résultat

    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) { // Tant que l'on peut extraire les enregistrements reçus par l'execution de notre requête SQL

        extract($row);

        $p = array( // On crée un vecteur avec ces propriétés ci, elles correspondent au noms de colonnes
            "idArticle" => $idArticle,
            "designation" => $designation,
            "code" => $code,
            "quantitestock" => $quantitéStock,
            "idEmpl" => $idEmpl,
        );
        array_push($article["article"], $p); // On ajoute la ligne dans notre tableau article
    }
    echo json_encode($article); // On encode le résultat pour renvoyer sous le format 
} else {
    echo json_encode(
        array("article" => array(), "count" => 0)
    );
}
?>
