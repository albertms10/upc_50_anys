<?php
require_once "PDOconnexion.php";

class Usuari
{
    private $nom;
    private $cognoms;
    private $username;
    private $contrasenya;
    private $id;

    public function __construct(
        $nom = null,
        $cognoms = null,
        $username = null,
        $contrasenya = null,
        $id = null
    )
    {
        $this->nom = $nom;
        $this->cognoms = $cognoms;
        $this->username = $username;
        $this->contrasenya = $contrasenya;
        $this->id = $id;
    }

    public static function checkLogin($username, $contrasenya)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        SELECT *
        FROM usuaris
            INNER JOIN contrasenyes c2 ON usuaris.id_usuari = c2.id_usuari
        WHERE username = :n
          AND contrasenya = :c;
        ");
        $result->execute([":n" => $username, ":c" => sha1($contrasenya)]);
        $connexion = null;

        if ($result) {
            session_start();
            $usuari = $result->fetchObject();
            $_SESSION["nom_complet"] = $usuari->nom . ($usuari->cognoms ? " " . $usuari->cognoms : "");
            $_SESSION["username"] = $usuari->username;
            $_SESSION["id"] = $usuari->id_usuari;
            return 1;
        } else {
            return 2;
        }
    }

    public static function infoUsuari($id_usuari)
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("SELECT * FROM usuaris WHERE id_usuari = :i");
        $result->bindParam(":i", $id_usuari, PDO::PARAM_INT);
        $result->execute();
        $connexion = null;
        return $result->fetch();
    }

    public function newUser()
    {
        $connexion = new Connexion();
        $result = $connexion->prepare("
        INSERT INTO usuaris (nom, cognoms, username)
        VALUES (:n, :c, :u);
        ");
        $result->execute([":n" => $this->nom, ":c" => $this->cognoms, ":u" => $this->username]);

        $result = $connexion->prepare("
        INSERT INTO contrasenyes (id_usuari, contrasenya)
        VALUES (:i, :c);
        ");
        $result->execute([":i" => $connexion->lastInsertId(), ":c" => sha1($this->contrasenya)]);

        $connexion = null;
        return 3;
    }
}
