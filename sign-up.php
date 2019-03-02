<?php
require_once("db.php");
require_once("data.php");
require_once("functions.php");

$errors = [];
$dict = [];
$registration = [];

if (!$con) {
    print('Ошибка подключения:' . mysqli_connect_error());
}
else {
    $categories_query = 'SELECT `id`, `name` FROM `categories` ORDER BY `id` ASC';
    $result_categories = mysqli_query($con, $categories_query);

    if ($result_categories) {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($con);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $registration = $_POST['signup'];
    $errors = [];
    $required = ['email', 'password', 'username', 'contacts'];
    $dict = ['email' => 'Введите email', 'password' => 'Введите корректный пароль', 'username' => 'Введите ваше имя', 'contacts' => 'Как вас можно найти?'];
    $_POST['avatar'] = 'Null';

    foreach ($required as $field) {
        if (empty($registration[$field])) {
            $errors[$field] = 'Поле незаполнено';
        }
    }

    if (!filter_var($registration['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Укажите корректный email';
    }

    if (isset($_FILES['avatar']['tmp_name']) && $_FILES['avatar']['tmp_name'] !== 'Null') {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $file_name = $_FILES['avatar']['name'];

        if (!empty($_FILES['avatar']['name'])) {
            // узнаем MIME-тип файла
            $file_open = finfo_open(FILEINFO_MIME_TYPE);
            $file_info = finfo_file($file_open, $tmp_name);

            // сравниваем с нужными форматами изображений, если форматы не сходятся, записываем ошибку
            if ($file_info !== 'image/png' && $file_info !== 'image/jpeg') {
                $errors['avatar'] = 'Загрузите фотографию в формате PNG/JPG';
            } // если проверка прошла успешно, перемещаем файл из временной папки
            else {
                move_uploaded_file($tmp_name, 'img/' . $file_name);
                $_POST['avatar'] = 'img/' . $file_name;
            }
        }
    }


    if (empty($errors)) {
        $email = $registration['email'];
        $sql_email = "SELECT `id` FROM `users` WHERE `email` = ?";
        $email_query = db_get_prepare_stmt($con, $sql_email, [$email]);
        $email_object = mysqli_stmt_execute($email_query);
        $email_result = mysqli_stmt_get_result($email_query);

        if (mysqli_num_rows($email_result) > 0) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        }
        $password = password_hash($registration['password'], PASSWORD_DEFAULT);
        $sql_registration = "INSERT INTO `users` (`email`, `name`, `password`, `contacts`, `avatar`, `dt_add`) 
                                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = db_get_prepare_stmt($con, $sql_registration, [$registration['email'], $registration['username'], $password, $registration['contacts'], $_POST['avatar']]);
        $registration_result = mysqli_stmt_execute($stmt);

        if ($registration_result && empty($errors)) {
            header("Location: /login.php");
            exit();
        }
    }
}

$page_content = include_template('sign-up.php', ['registration' => $registration, 'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Регистрация нового аккаунта',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'categories' => $categories
]);

print($layout_content);

?>
