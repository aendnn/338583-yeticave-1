<?php
session_start();

if (!$con) {
    print('Ошибка подключения:' . mysqli_connect_error());
}
?>
