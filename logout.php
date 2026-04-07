<?php

session_start();


session_destroy();


setcookie('login_username', '', time() - 3600, "/");
setcookie('login_key', '', time() - 3600, "/");


header("Location: login.php");
exit;
?>