<?php
if (isset($_POST["username"]) && isset($_POST["contrasenya"])) {
    define("ROOT", "/home/upc_50_anys/public_html/");
    require_once ROOT . "assets/php/classes/Usuari.php";

    $signin = Usuari::checkLogin($_POST["username"], $_POST["contrasenya"]);

    if ($signin == 1) header("location:/~upc_50_anys/edit/");
    else header("location:/~upc_50_anys/edit/sign-in/?message=" . $signin);
}
