<?php
header("Access-Control-Allow-Origin: *");
//------------------------------------------------------------------------------
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
?>
