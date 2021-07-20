<?php
define("DEFAULT_LANG", "ca");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION["lang"] = DEFAULT_LANG;
}
