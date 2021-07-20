<?php
require_once "PDOconnexion.php";

class Idioma
{
    public static function getIdiomes($exclude_preferred)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT id_idioma, nom_idioma
        FROM idiomes
            ". ($exclude_preferred ? "WHERE preferit = 0;" : ";")
        );
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getIdiomesEsdeveniment($id_esdeveniment, $exclude_preferred)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT idiomes.id_idioma, nom_idioma
        FROM idiomes
            LEFT JOIN traduccions_esdeveniments te ON idiomes.id_idioma = te.id_idioma
        WHERE id_esdeveniment = :i
            ". ($exclude_preferred ? "AND preferit = 0;" : ";")
        );
        $result->bindParam(":i", $id_esdeveniment, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMissingIdiomesEsdeveniment($id_esdeveniment)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT id_idioma, nom_idioma
        FROM idiomes
        WHERE id_idioma NOT IN
              (
                  SELECT idiomes.id_idioma
                  FROM idiomes
                      LEFT JOIN traduccions_esdeveniments te ON idiomes.id_idioma = te.id_idioma
                  WHERE id_esdeveniment = :i
              );
        ");
        $result->bindParam(":i", $id_esdeveniment, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}