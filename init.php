<?php
session_start();

if (!$connect) {
    print('Ошибка подключения:' . mysqli_connect_error());
}
