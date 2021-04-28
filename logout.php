<?php
setcookie("account", $_COOKIE['account'], time()-3600, "/");
header("LOCATION:login.php");
?>