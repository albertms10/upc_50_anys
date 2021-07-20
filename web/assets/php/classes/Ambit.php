<?php
require_once "PDOconnexion.php";

class Ambit
{
    public static function getAmbits()
    {
        include "../incs/lang.php";

        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT nom_ambit as name, ta.id_ambit as value
        FROM ambits
            INNER JOIN traduccions_ambits ta ON ambits.id_ambit = ta.id_ambit
        WHERE id_idioma = IF(id_idioma = :lang, :lang, '" . DEFAULT_LANG .  "')
        ORDER BY nom_ambit
        ");
        $result->execute([":lang" => $_SESSION["lang"]]);
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
