<?php
require_once "../classes/Esdeveniment.php";
header('Content-Type: application/json');

if (isset($_GET["show_all"])) {
    $json = Esdeveniment::infoEsdevenimentAll($_GET["id"]);
    $json["info"] = json_decode($json["info"]);
} else {
    $json = Esdeveniment::infoEsdeveniment($_GET["id"]);
}
$json["data"] = json_decode($json["data"]);
$json["ambits"] = json_decode($json["ambits"]);
echo json_encode($json, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
