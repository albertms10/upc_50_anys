<?php
require_once "PDOconnexion.php";

class Any
{
    public static function getAnys()
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("SELECT any AS value, any AS name FROM anys ORDER BY any DESC;");
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAnysInfo()
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("SELECT * FROM anys ORDER BY any;");
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
