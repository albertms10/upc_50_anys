<?php
require_once "../classes/Esdeveniment.php";
header('Content-Type: application/json');

if (isset($_GET["any"])) {
    $esdeveniments = Esdeveniment::getEsdevenimentsAny($_GET["any"]);
} else {
    $esdeveniments = Esdeveniment::getEsdeveniments();
}

foreach ($esdeveniments as $key => $esdeveniment) {
    $esdeveniments[$key]["has_thumbnail"] = (bool)$esdeveniment["has_thumbnail"];
}

echo json_encode($esdeveniments, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
