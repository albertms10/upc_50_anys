<?php
if (isset($_POST["username"]) && isset($_POST["contrasenya"])) {
    define("ROOT", "/home/upc_50_anys/public_html/");
    require_once ROOT . "assets/php/classes/Usuari.php";

    $usuari = new Usuari(
        $_POST["nom"],
        $_POST["cognoms"],
        $_POST["username"],
        $_POST["contrasenya"]
    );
    $result = $usuari->newUser();
    header("location:/~upc_50_anys/edit/sign-in/?message=" . $result . "&username=" . $_POST["username"]);
}