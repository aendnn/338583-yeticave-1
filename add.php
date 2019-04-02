<?php
require_once("db.php");
require_once("init.php");
require_once("functions.php");

$lot = [];
$errors = [];
$dict = [];
$add_lot = [];
$categories = get_categories($connect);


// проверка отправки формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // здесь будут храниться значения из полей
        $add_lot = $_POST;

        // обязательные для заполнения поля
        $required = ['title', 'category', 'desc', 'price', 'step', 'date',];
        // описательные названия для полей с ошибками
        $dict = ['title' => 'Укажите название', 'category' => 'Укажите категорию лота', 'desc' => 'Заполните описание', 'lot_img' => 'Загрузите изображение', 'price' => 'Укажите начальную цену', 'step' => 'Укажите шаг ставки', 'date' => 'Укажите дату завершения лота'];
        // массив для хранения ошибок
        $errors = [];
        $_POST['lot_img'] = 'Null';


        // обход массива с обязательными полями
        foreach ($required as $field) {
            // если поле пустое, добавить ошибку
            if (empty($add_lot[$field])) {
                $errors[$field] = 'Поле незаполнено';
            }
        }

        if (isset($_FILES['lot_img']['name']) && $_FILES['lot_img']['name']) {
            $_POST['lot_img'] = upload_file($_FILES['lot_img']['tmp_name'], $_FILES['lot_img']['name'], $_POST['lot_img']);
        }
        else {
            $errors['lot_img'] = 'Выберите изображение';
        }

        if (!is_numeric($add_lot['price']) || $add_lot['price'] <= 0) {
            $errors['price'] = 'Заполните поле корректными данными';
        }

        if (!is_numeric($add_lot['step']) || $add_lot['step'] <= 0) {
            $errors['step'] = 'Заполните поле корректными данными';
        }

        $categories_id = array_column($categories, 'id');

        if ($add_lot['category'] === '0' || !in_array($add_lot['category'], $categories_id)) {
            $errors['category'] = 'Выберите категорию';
        }

        // проверка даты
        if (strtotime($add_lot['date']) <= strtotime('now')) {
            $errors['date'] = 'Дата завершения должна быть больше текущей хотя бы на один день';
        }

        // проверяем есть ли ошибки, если да выводим их вместе с формой
        if (!count($errors)) {
            $add_lot = add_lot($connect, $add_lot);

            if ($add_lot) {
                $add_lot_id = mysqli_insert_id($connect);
                header("Location: lot.php?id=" . $add_lot_id);
                return $add_lot_id;
            }
        }
        // если ошибок нет, добавляем лот в БД и перенаправляем пользователя на страницу с новым лотом
    } // если метод не POST, значит пользователь перешел по ссылке - показываем просто форму для заполнения


if (!isset($_SESSION['user'])) {
    $error_403 = http_response_code(403);
}
$page_content = include_template('add.php', ['categories' => $categories, 'errors' => $errors,
    'dict' => $dict,
    'add_lot' => $add_lot]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Добавление лота',
    'categories' => $categories
]);

print($layout_content);
