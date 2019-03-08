<?php
$db = ['host' => 'localhost', 'user' => 'root', 'password' => '', 'database' => 'yeti'];
$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, "utf8");
