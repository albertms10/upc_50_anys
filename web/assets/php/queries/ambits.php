<?php
require_once "../classes/Ambit.php";
header('Content-Type: application/json');

echo json_encode(Ambit::getAmbits(), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
