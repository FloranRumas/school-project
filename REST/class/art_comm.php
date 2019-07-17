<?php

class art_comm {

    private $connection;
    private $table_name = "art_comm";
    public $idArtComm;
    public $quantite;
    public $quantitePrelevee;
    public $idArticle;
    public $idComm;
    public $code ;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function readArtComm() {
        $query = "SELECT idArtComm, quantite, quantitePrelevee, idArticle, idComm FROM art_comm ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }

    public function readArtCommandeSelonEmplacement($prmEmplacement) {
        $query = "SELECT art_comm.idArtComm, article.designation, art_comm.quantite, art_comm.quantitePrelevee , article.code  "
                . "FROM art_comm, article "
                . "WHERE art_comm.idArticle = article.idArticle "
                . "AND art_comm.quantite <> art_comm.quantitePrelevee "
                . "AND article.idEmpl = $prmEmplacement";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }

    public function updateQuantite($prmCode) {
        $query = "UPDATE art_comm, article SET quantitePrelevee = quantitePrelevee + 1"
                . " WHERE art_comm.idArticle = article.idArticle"
                . " AND article.code = $prmCode";

        $results = $this->connection->prepare($query);

        $results->execute();
        
        return $results ;
    }

}
