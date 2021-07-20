<?php
require_once "../classes/Idioma.php";
header('Content-Type: application/json');
echo "{\"success\": true,\"results\":[";
$object_attrs = [];

$exclude_preferred = isset($_GET["exclude_preferred"]) ? true : false;

if (isset($_GET["id"])) {
    if (isset($_GET["missing_only"])) {
        $idiomes = Idioma::getMissingIdiomesEsdeveniment($_GET["id"]);
    } else {
        $idiomes = Idioma::getIdiomesEsdeveniment($_GET["id"], $exclude_preferred);
    }
} else {
    $idiomes = Idioma::getIdiomes($exclude_preferred);
}

foreach ($idiomes as $idioma) {
    array_push(
        $object_attrs,
        "{\"name\":\"" . $idioma["nom_idioma"] . "\","
        . "\"value\":\"" . $idioma["id_idioma"] . "\"}"
    );
}

echo join(",", $object_attrs) . "]}";
