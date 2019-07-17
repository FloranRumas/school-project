<?php

class article {
    
    private $connection;
    public $idArticle;
    public $designation;
    public $code;
    public $quantiteStock;
    public $idEmpl;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function readArticles() {
        $query = "SELECT idArticle, designation, code, quantitéStock, idEmpl FROM article ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }

    
    public function readUnArticle($prmArticle) {
        $query = "SELECT idArticle, designation, code, quantitéStock, idEmpl FROM article WHERE idArticle = $prmArticle ";

        $results = $this->connection->prepare($query);

        $results->execute();

        return $results;
    }

}
