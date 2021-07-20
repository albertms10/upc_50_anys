<?php
require "../classes/Esdeveniment.php";
define("ROOT", "/home/upc_50_anys/public_html/");

$has_thumbnail = $_FILES != null;

$esdeveniment = new Esdeveniment($_POST["data"], $_POST["info"], $_POST["ambits"], $has_thumbnail, $_POST["link_media"]);
if (is_numeric($_POST["id_esdeveniment"])) {
    $esdeveniment->setId((int)$_POST["id_esdeveniment"]);
    $esdeveniment->actualitzarEsdeveniment();
} else {
    $esdeveniment->afegirEsdeveniment();
}

$esdeveniment = null;
echo $_POST["data"]["any"];
