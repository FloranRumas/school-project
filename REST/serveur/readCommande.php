<?php
header("Access-Control-Allow-Origin: *");
//------------------------------------------------------------------------------
require('../connection/connection.php');
require('../class/commande.php');
//------------------------------------------------------------------------------
// Connexion à la BDD
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
//------------------------------------------------------------------------------
// Commande
$commande = new Commande($connection);
// Fonction ReadCommande
$resultat = $commande->readCommande();
$compteur = $resultat->rowCount();

if ($compteur > 0) {

    $commande = array();
    $commande["commande"] = array();
    $commande["count"] = $compteur;

    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {

        extract($row);

        $p = array(
        "idComm" => $idComm,
        "numCommande" => $numCommande,
        "addr_destinataire" => $addr_destinataire,
        "dept_destination" => $dept_destination,
        "statut" => $statut,
        "dateValidERP" => $dateValidERP,
        "dateDebutPrepa" => $dateDebutPrepa,
        "dateprete" => $dateprete             
        );
        array_push($commande["commande"], $p);
    }
    echo json_encode($commande);
} else {
    echo json_encode(
            array("commande" => array(), "count" => 0)
    );
}
?>
