<?php
session_start();
$_SESSION["data"] = null;
session_destroy();
setcookie("data", "", time()-60*60*24*365);
header("Location: login.php");
?>