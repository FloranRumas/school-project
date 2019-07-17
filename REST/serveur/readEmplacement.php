<?php
header("Access-Control-Allow-Origin: *");
//------------------------------------------------------------------------------
require('../connection/connection.php');
require('../class/emplacement.php');
//------------------------------------------------------------------------------
// Connexion à la BDD
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
//------------------------------------------------------------------------------
// Emplacement
$emplacement = new emplacement($connection);
// Fonction readEmplacement
$resultat = $emplacement->readEmplacement();
$compteur = $resultat->rowCount();

if ($compteur > 0) {

    $emplacement = array();
    $emplacement["emplacement"] = array();
    $emplacement["count"] = $compteur;

    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $p = array(
            "idEmpl" => $idEmpl,
            "nom" => $nom,
            "idPPrel" => $idPPrel,
        );
        array_push($emplacement["emplacement"], $p);
    }
    echo json_encode($emplacement);
} else {
    echo json_encode(
    array("emplacement" => array(), "count" => 0)
    );
}
?>
