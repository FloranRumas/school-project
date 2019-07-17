<?php

class emplacement {

    private $connection;
    
    public $idEmpl;
    public $nom;
    public $idPPrel;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function readEmplacement() {
        $query = "SELECT idEmpl, nom, idPPrel FROM emplacement ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }
}
