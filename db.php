<?php
$db = ['host' => 'localhost', 'user' => 'root', 'password' => '', 'database' => 'yeti'];
$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($con, "utf8");
?>
