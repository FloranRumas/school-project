<?php

class posteprelevement{

    private $connection;
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function readPrelevement() {
        $query = "SELECT idPPrel, nomRayonnage FROM  posteprelevement ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }
}
