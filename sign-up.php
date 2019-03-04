<?php
require_once("db.php");
require_once("init.php");
require_once("functions.php");

$errors = [];
$dict = [];
$registration = [];

$categories = get_categories($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $email = $registration['email'];
    $email_result = get_user_email($con, $email);

    if (mysqli_num_rows($email_result) > 0) {
        $errors['email'] = 'Пользователь с таким email уже существует';
    }

    if (empty($errors)) {
        $password = password_hash($registration['password'], PASSWORD_DEFAULT);
        $registration_result = reg_user($con, $registration, $password);

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
    'categories' => $categories
]);

print($layout_content);

?>
