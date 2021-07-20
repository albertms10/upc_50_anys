<?php
require_once "../classes/Esdeveniment.php";
Esdeveniment::ordenar($_GET["any"], $_GET["old_ordre"], $_GET["new_ordre"]);
