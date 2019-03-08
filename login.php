<?php
require_once("db.php");
require_once("init.php");
require_once("functions.php");

$errors = [];
$login = [];

$categories = get_categories($connect);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $required = ['email', 'password'];
    $errors = [];

    foreach ($required as $field) {
        if (empty($login[$field])) {
            $errors[$field] = 'Поле незаполнено';
        }
    }

    if (!filter_var($login['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Укажите корректный email';
    }
    else {
        $email = $login['email'];
        $sql_login = "SELECT * FROM `users` WHERE `email` = ?";
        $user_query = db_get_prepare_stmt($connect, $sql_login, [$email]);
        $user_object = mysqli_stmt_execute($user_query);
        $user_result = mysqli_stmt_get_result($user_query);

        $user = $user_object ? mysqli_fetch_array($user_result, MYSQLI_ASSOC) : null;
        if (!count($errors) && $user) {
            if (password_verify($login['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        }

        if (!$user) {
            $errors['email'] = 'Пользователь не найден';
        }

        if (!count($errors)) {
            header("Location: /index.php");
            exit();
        }
        else {
            $page_content = include_template('login.php', ['errors' => $errors, 'login' => $login, 'categories' => $categories]);
        }
    }
}

$page_content = include_template('login.php', ['categories' => $categories, 'errors' => $errors, 'login' => $login]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Вход на сайт',
    'categories' => $categories
]);

print($layout_content);
