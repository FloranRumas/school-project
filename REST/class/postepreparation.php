<?php

class postepreparation {

    private $connection;
    
    public $idPPrep;
    public $macCommSimultanees;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function readPostePreparation() {
        $query = "SELECT idPPrep, maxCommSimultanees FROM postepreparation ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }
}
