<?php

class Connexion extends PDO
{
    private $servername = "localhost";
    private $dbname = "upc_50_anys";
    private $user = "upc_50_anys";
    private $password = "rEC7czetGdCP";

    public function __construct()
    {
        try {
            parent::__construct(
                "mysql:host=$this->servername; dbname=$this->dbname",
                $this->user,
                $this->password,
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
