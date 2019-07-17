<?php
class DBClass {
    private $host = "172.16.128.110";
    private $username = "Opprev";
    private $password = "opprev";
    private $database = "bddprojet";
    /*private $host = "127.0.0.1";
    private $username = "root";
    private $password = "root";
    private $database = "bddprojet";*/

    public $connection;

    // get the database connection
    public function getConnection(){

        $this->connection = null;

        try{
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->connection->exec("set names utf8");
        }catch(PDOException $exception){
            http_response_code(500); 
            echo json_encode($exception->getMessage());
        }

        return $this->connection;
    }
}
?>