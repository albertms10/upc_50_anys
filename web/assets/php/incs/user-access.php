<?php
session_start();

if (!isset($_SESSION["username"]) && empty($_SESSION["username"])) {
    header("location:/~upc_50_anys/edit/sign-in/");
}
