<?php
header("Access-Control-Allow-Origin: *");
//------------------------------------------------------------------------------
require('../connection/connection.php');
require('../class/art_comm.php');
//------------------------------------------------------------------------------
// Connexion à la BDD
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
//------------------------------------------------------------------------------
// On instansie l'objet Art_Comm
$art_comm = new art_comm($connection);
// Execution de la fonction ReadArtCommSelonEmplacement qui nout renvoie le résultat de notre requête
$resultat = $art_comm->readArtCommandeSelonEmplacement($_GET['emplacement']); 
$compteur = $resultat->rowCount(); // On récupère le nombre de résultat

if ($compteur > 0) { // On vérifie que le nombre de résultat soit supérieur à 0

    $art_comm = array(); // On crée un tableau
    $art_comm["articles_commande"] = array(); // On fait un tableau de vecteur qui contient les résultats
    $art_comm["count"] = $compteur; // On fait un tableau de vecteur qui contient le nombre de résultat

    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) { // Tant que l'on peut extraire les enregistrements reçus par l'execution de notre requête SQL

        extract($row); // On extrait le contenu de la ligne

        $p = array( // On crée un vecteur avec ces propriétés ci chaque propriété correspond aux noms de colonnes
            "idArtComm" => $idArtComm,
            "designation" => $designation,
            "quantite" => $quantite,
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
?>
