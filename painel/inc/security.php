<?php
session_start();
if(!isset($_COOKIE["data"]) and !isset($_SESSION["data"])){
    header("Location: login.php");
}
?>