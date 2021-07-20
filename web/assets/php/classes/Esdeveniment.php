<?php
require_once "PDOconnexion.php";

class Esdeveniment
{
    private $data;
    private $info;
    private $ambits;
    private $has_thumbnail;
    private $link_media;
    private $id;

    public function __construct($data, $info, $ambits, $has_thumbnail, $link_media, $id = null)
    {
        $this->data = $data;
        $this->info = $info;
        $this->ambits = $ambits;
        $this->has_thumbnail = $has_thumbnail;
        $this->link_media = $link_media;
        $this->id = $id;
    }

    public static function getEsdeveniments()
    {
        require "../incs/lang.php";

        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT *
        FROM esdeveniments
            INNER JOIN traduccions_esdeveniments te ON esdeveniments.id_esdeveniment = te.id_esdeveniment
        WHERE id_idioma = IF(id_idioma = :lang, :lang, '" . DEFAULT_LANG . "')
        ORDER BY any, titular, ordre, mes, dia;
        ");
        $result->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEsdevenimentsAny($any)
    {
        require "../incs/lang.php";

        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT *
        FROM esdeveniments
            INNER JOIN traduccions_esdeveniments te ON esdeveniments.id_esdeveniment = te.id_esdeveniment
        WHERE id_idioma = IF(id_idioma = :lang, :lang, '" . DEFAULT_LANG . "')
          AND any = :a
        ORDER BY any, titular, ordre, mes, dia;
        ");
        $result->bindParam(":a", $any, PDO::PARAM_INT);
        $result->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
        $result->execute();
        $connexion = null;
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function infoEsdeveniment($id)
    {
        require "../incs/lang.php";

        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT esdeveniments.id_esdeveniment,
               CONCAT(
                   '{\"dia\":', IFNULL(dia, 'null'),
                   ',\"mes\":', IFNULL(mes, 'null'),
                   ',\"any\":', IFNULL(any, 'null'),
                   '}') AS data,
               te.*,
               (
                   SELECT CONCAT('[', IFNULL(GROUP_CONCAT(DISTINCT CONCAT(
                       '{\"id\":', ambits.id_ambit,
                       ',\"ambit\":\"', nom_ambit,
                       '\"}')
                       ), ''), ']')
                   FROM ambits
                       INNER JOIN esdeveniments_ambits ea ON (SELECT esdeveniments.id_esdeveniment) = ea.id_esdeveniment
                       INNER JOIN traduccions_ambits ta ON ambits.id_ambit = ta.id_ambit
                   WHERE ta.id_idioma = IF(ta.id_idioma = :lang, :lang, '" . DEFAULT_LANG . "')
               ) AS ambits,
               has_thumbnail, link_media
        FROM esdeveniments
            INNER JOIN traduccions_esdeveniments te ON esdeveniments.id_esdeveniment = te.id_esdeveniment
        WHERE te.id_idioma = IF(te.id_idioma = :lang, :lang, '" . DEFAULT_LANG . "')
          AND esdeveniments.id_esdeveniment = :id;
        ");
        $result->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
        $result->bindParam(":id", $id, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public static function infoEsdevenimentAll($id)
    {
        require "../incs/lang.php";

        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT esdeveniments.id_esdeveniment,
               CONCAT(
                   '{\"dia\":', IFNULL(dia, 'null'),
                   ',\"mes\":', IFNULL(mes, 'null'),
                   ',\"any\":', IFNULL(any, 'null'),
                   '}') AS data,
               (
                   SELECT CONCAT('{', IFNULL(GROUP_CONCAT(DISTINCT CONCAT('\"', id_idioma, '\":{', info, '}')), ''), '}')
                   FROM (
                       SELECT DISTINCT i.id_idioma, id_esdeveniment, 
                                       CONCAT(
                                           '\"nom_idioma\":\"', nom_idioma, '\",'
                                           '\"titular\":', IFNULL(CONCAT('\"', titular, '\"'), 'null'),
                                           ',\"descripcio\":', IFNULL(CONCAT('\"', descripcio, '\"'), 'null'),
                                           ',\"mes_info\":', IFNULL(CONCAT('\"', mes_info, '\"'), 'null')
                                           ) AS info
                       FROM traduccions_esdeveniments
                       INNER JOIN idiomes i ON traduccions_esdeveniments.id_idioma = i.id_idioma
                   ) t
                   WHERE t.id_esdeveniment = (SELECT esdeveniments.id_esdeveniment)
               ) AS info,
               (
                   SELECT CONCAT('[', IFNULL(GROUP_CONCAT(DISTINCT CONCAT(
                       '{\"name\":\"', nom_ambit, 
                       '\",\"value\":\"', id_ambit,
                       '\",\"selected\":', selected, '}'
                       )), ''), ']')
                   FROM (
                       SELECT ambits.id_ambit, nom_ambit, IFNULL((
                           SELECT IF(id_esdeveniment IS NOT NULL, 'true', 'false')
                           FROM esdeveniments_ambits
                           WHERE id_esdeveniment = :id
                             AND id_ambit = (SELECT ambits.id_ambit)
                           GROUP BY id_esdeveniment
                       ), 'false') COLLATE utf8_general_ci AS selected
                       FROM ambits
                           LEFT JOIN traduccions_ambits ta ON ambits.id_ambit = ta.id_ambit
                       WHERE id_idioma = IF(id_idioma = :lang, :lang, '" . DEFAULT_LANG . "')
                       GROUP BY ambits.id_ambit, nom_ambit
                       ORDER BY nom_ambit
                   ) t
               ) AS ambits, link_media
        FROM esdeveniments
        WHERE esdeveniments.id_esdeveniment = :id;
        ");
        $result->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
        $result->bindParam(":id", $id, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public static function ordenar($any, $old_ordre, $new_ordre)
    {
        if ($old_ordre != $new_ordre) {
            self::reiniciarOrdre($any, $old_ordre);
            self::moure($any, $old_ordre, $new_ordre);
            self::assignarOrdre($any, $new_ordre);
        }
    }

    private static function reiniciarOrdre($any, $old_ordre)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("UPDATE esdeveniments SET ordre = 0 WHERE any = :a AND ordre = :o;");
        $result->bindParam(":a", $any, PDO::PARAM_INT);
        $result->bindParam(":o", $old_ordre, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
    }

    private static function moure($any, $old_ordre, $new_ordre)
    {
        $connexion = new Connexion();
        if ($old_ordre < $new_ordre) {
            $q = "UPDATE esdeveniments SET ordre = ordre - 1 WHERE any = :a AND ordre >= :o AND ordre <= :n;";
        } else {
            $q = "UPDATE esdeveniments SET ordre = ordre + 1 WHERE any = :a AND ordre >= :n AND ordre <= :o;";
        }
        $result = $connexion->prepare($q);
        $result->bindParam(":a", $any, PDO::PARAM_INT);
        $result->bindParam(":o", $old_ordre, PDO::PARAM_INT);
        $result->bindParam(":n", $new_ordre, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
    }

    private static function assignarOrdre($any, $new_ordre)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("UPDATE esdeveniments SET ordre = :n WHERE any = :a AND ordre = 0;");
        $result->bindParam(":a", $any, PDO::PARAM_INT);
        $result->bindParam(":n", $new_ordre, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
    }

    public static function eliminarEsdeveniment($id_esdeveniment)
    {
        $connexion = new Connexion();

        $result = $connexion->prepare("DELETE FROM esdeveniments_ambits WHERE id_esdeveniment = :i;");
        $result->bindParam(":i", $id_esdeveniment, PDO::PARAM_INT);
        $result->execute();

        $result = $connexion->prepare("DELETE FROM traduccions_esdeveniments WHERE id_esdeveniment = :i;");
        $result->bindParam(":i", $id_esdeveniment, PDO::PARAM_INT);
        $result->execute();

        $result = $connexion->prepare("
        UPDATE esdeveniments
        SET ordre = ordre - 1
        WHERE any = (SELECT any FROM (SELECT * FROM esdeveniments) e WHERE e.id_esdeveniment = :i)
          AND ordre > (SELECT ordre FROM (SELECT * FROM esdeveniments) e WHERE id_esdeveniment = :i);
        ");
        $result->bindParam(":i", $id_esdeveniment, PDO::PARAM_INT);
        $result->execute();

        $result = $connexion->prepare("DELETE FROM esdeveniments WHERE id_esdeveniment = :i;");
        $result->bindParam(":i", $id_esdeveniment, PDO::PARAM_INT);
        $result->execute();

        $connexion = null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function afegirEsdeveniment()
    {
        $connexion = new Connexion();

        $dia = $this->data["dia"] ? (int)$this->data["dia"] : null;
        $mes = $this->data["mes"] ? (int)$this->data["mes"] : null;
        $any = $this->data["any"] ? (int)$this->data["any"] : null;
        $has_thumbnail = $this->has_thumbnail ? true : false;
        $link_media = $this->link_media ? $this->link_media : null;

        $result = $connexion->prepare("
        INSERT INTO esdeveniments (dia, mes, any, has_thumbnail, link_media, ordre)
        VALUES (:d, :m, :a, :h, :l, (SELECT IFNULL(MAX(ordre), 0) FROM (SELECT * FROM esdeveniments) e WHERE any = :a) + 1);
        ");
        $result->bindParam(":d", $dia, PDO::PARAM_INT);
        $result->bindParam(":m", $mes, PDO::PARAM_INT);
        $result->bindParam(":a", $any, PDO::PARAM_INT);
        $result->bindParam(":h", $has_thumbnail, PDO::PARAM_BOOL);
        $result->bindParam(":l", $link_media, PDO::PARAM_STR);
        $result->execute();
        $id_esdeveniment = $connexion->lastInsertId();

        foreach ($this->info as $lang => $traduccio) {
            $titular = $traduccio["titular"] ? $traduccio["titular"] : null;
            $descripcio = $traduccio["descripcio"] ? $traduccio["descripcio"] : null;
            $mes_info = $traduccio["mes_info"] ? $traduccio["mes_info"] : null;

            if ($titular == null && $descripcio == null && $mes_info == null) {
                continue;
            } else {
                $result = $connexion->prepare("
                INSERT INTO traduccions_esdeveniments(id_idioma, id_esdeveniment, titular, descripcio, mes_info)
                VALUES (:lang, :id, :t, :d, :m);
                ");
                $result->bindParam(":lang", $lang, PDO::PARAM_STR);
                $result->bindParam(":id", $id_esdeveniment, PDO::PARAM_INT);
                $result->bindParam(":t", $titular, PDO::PARAM_STR);
                $result->bindParam(":d", $descripcio, PDO::PARAM_STR);
                $result->bindParam(":m", $mes_info, PDO::PARAM_STR);
                $result->execute();
            }
        }

        if ($this->ambits != null && count($this->ambits) > 0) {
            $result = $connexion->prepare("INSERT INTO esdeveniments_ambits VALUES(:e, :a);");

            foreach ($this->ambits as $ambit) {
                $result->bindParam(":e", $id_esdeveniment, PDO::PARAM_INT);
                $result->bindParam(":a", $ambit, PDO::PARAM_INT);
                $result->execute();
            }
        }

        $connexion = null;
        return $id_esdeveniment;
    }

    public function actualitzarEsdeveniment()
    {
        $connexion = new Connexion();

        $dia = $this->data["dia"] ? (int)$this->data["dia"] : null;
        $mes = $this->data["mes"] ? (int)$this->data["mes"] : null;
        $any = $this->data["any"] ? (int)$this->data["any"] : null;
        $has_thumbnail = $this->has_thumbnail ? true : false;
        $link_media = $this->link_media ? $this->link_media : null;

        $result = $connexion->prepare("
        UPDATE esdeveniments
        SET dia = :d,
            mes = :m,
            any = :a,
            has_thumbnail = :h,
            link_media = :l
        WHERE id_esdeveniment = :i;
        ");
        $result->bindParam(":i", $this->id, PDO::PARAM_INT);
        $result->bindParam(":d", $dia, PDO::PARAM_INT);
        $result->bindParam(":m", $mes, PDO::PARAM_INT);
        $result->bindParam(":a", $any, PDO::PARAM_INT);
        $result->bindParam(":h", $has_thumbnail, PDO::PARAM_BOOL);
        $result->bindParam(":l", $link_media, PDO::PARAM_STR);
        $result->execute();

        $result = $connexion->prepare("
        SELECT DISTINCT id_idioma
        FROM traduccions_esdeveniments
        WHERE id_esdeveniment = :i;
        ");
        $result->bindParam(":i", $this->id, PDO::PARAM_INT);
        $result->execute();
        $langs = $result->fetchAll(PDO::FETCH_ASSOC)[0];

        foreach ($this->info as $lang => $traduccio) {
            $titular = $traduccio["titular"] ? $traduccio["titular"] : null;
            $descripcio = $traduccio["descripcio"] ? $traduccio["descripcio"] : null;
            $mes_info = $traduccio["mes_info"] ? $traduccio["mes_info"] : null;

            if ($titular == null && $descripcio == null && $mes_info == null) {
                $result = $connexion->prepare("
                DELETE FROM traduccions_esdeveniments
                WHERE id_esdeveniment = :i
                  AND id_idioma = :lang;
                ");
                $result->bindParam(":lang", $lang, PDO::PARAM_STR);
                $result->bindParam(":i", $this->id, PDO::PARAM_INT);
                $result->execute();
            } else {
                if (in_array($lang, $langs)) {
                    $result = $connexion->prepare("
                    UPDATE traduccions_esdeveniments
                    SET titular = :t,
                        descripcio = :d,
                        mes_info = :m
                    WHERE id_esdeveniment = :i
                      AND id_idioma = :lang;
                    ");
                } else {
                    $result = $connexion->prepare("
                    INSERT INTO traduccions_esdeveniments(id_idioma, id_esdeveniment, titular, descripcio, mes_info)
                    VALUES (:lang, :i, :t, :d, :m);
                    ");
                }

                $result->bindParam(":lang", $lang, PDO::PARAM_STR);
                $result->bindParam(":i", $this->id, PDO::PARAM_INT);
                $result->bindParam(":t", $titular, PDO::PARAM_STR);
                $result->bindParam(":d", $descripcio, PDO::PARAM_STR);
                $result->bindParam(":m", $mes_info, PDO::PARAM_STR);
                $result->execute();
            }
        }

        $result = $connexion->prepare("
        SELECT DISTINCT id_ambit
        FROM esdeveniments_ambits
        WHERE id_esdeveniment = :i;
        ");
        $result->bindParam(":i", $this->id, PDO::PARAM_INT);
        $result->execute();
        $ambits = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($this->ambits as $ambit) {
            if (!in_array($ambit, $ambits)) {
                $result = $connexion->prepare("INSERT INTO esdeveniments_ambits VALUES(:e, :a);");
                $result->bindParam(":e", $this->id, PDO::PARAM_INT);
                $result->bindParam(":a", $ambit, PDO::PARAM_INT);
                $result->execute();
            }
        }

        foreach ($ambits as $ambit) {
            if (!in_array($ambit["id_ambit"], $this->ambits)) {
                $result = $connexion->prepare("
                DELETE FROM esdeveniments_ambits
                WHERE id_esdeveniment = :e
                  AND id_ambit = :a;");
                $result->bindParam(":e", $this->id, PDO::PARAM_INT);
                $result->bindParam(":a", $ambit["id_ambit"], PDO::PARAM_INT);
                $result->execute();
            }
        }

        $connexion = null;
    }
}
