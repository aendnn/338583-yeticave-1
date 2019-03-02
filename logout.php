<?php
require_once("db.php");
require_once("data.php");
unset($_SESSION['user']);
header("Location: /index.php");

?>
