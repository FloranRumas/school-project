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
// Article
$article = new Article($connection);
// Fonction ReadArticles
$resultat = $article->readArticles();
$compteur = $resultat->rowCount();

if ($compteur > 0) {

    $article = array();
    $article["articles"] = array();
    $article["count"] = $compteur;

    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $p = array(
            "idArticle" => $idArticle,
            "designation" => $designation,
            "code" => $code,
            "quantitestock" => $quantitéStock,
            "idEmpl" => $idEmpl,
        );
        array_push($article["articles"], $p);
    }
    echo json_encode($article);
} else {
    echo json_encode(
        array("articles" => array(), "count" => 0)
    );
}
?>
