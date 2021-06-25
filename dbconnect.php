<?php

class DbConnect {

    private $host = 'localhost';
    private $dbName = 'worldcities';
    private $user = 'worldcities';
    private $pass = 'worldcities';

    public function connect() {
        try {
            $conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass ); 
//cache and reuse connections with , array(    PDO::ATTR_PERSISTENT => true)

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->query('SET NAMES utf8');
            return $conn;
        } catch (PDOException $e) {
            echo 'Database connection error: ' . $e->getMessage();
        }
    }

  

}

?>