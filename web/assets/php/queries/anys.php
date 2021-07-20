<?php
require_once "../classes/Any.php";
header('Content-Type: application/json');

if (isset($_GET["dropdown"])) {
    echo json_encode(Any::getAnys(), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
} else {
    echo json_encode(Any::getAnysInfo(), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
}
