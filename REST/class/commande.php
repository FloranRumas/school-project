<?php

class commande {

    private $connection;
    
    public $idComm;
    public $numCommande;
    public $addr_destinatire;
    public $dept_destination;
    public $statut;
    public $dateValidERP;
    public $dateDebutPrepa;
    public $dateprete;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function readCommande() {
        $query = "SELECT idComm, numCommande, addr_destinataire, dept_destination, statut, dateValidERP, dateDebutPrepa, dateprete FROM commande ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }
}
