<?php
header("Access-Control-Allow-Origin: *");
//------------------------------------------------------------------------------
require('../connection/connection.php');
require('../class/postepreparation.php');
//------------------------------------------------------------------------------
// Connexion à la BDD
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
//------------------------------------------------------------------------------
// PostePreparation
$postepreparation = new postepreparation($connection);
// Fonction ReadPostePreparation
$resultat = $postepreparation->readPostePreparation();
$compteur = $resultat->rowCount();

if ($compteur > 0) {

    $postepreparation = array();
    $postepreparation["postePreparation"] = array();
    $postepreparation["count"] = $compteur;

    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $p = array(
            "idPPrep" => $idPPrep,
            "maxCommSimultanees" => $maxCommSimultanees
        );
        array_push($postepreparation["postePreparation"], $p);
    }
    echo json_encode($postepreparation);
} else {
    echo json_encode(
    array("postePreparation" => array(), "count" => 0)
    );
}
?>
