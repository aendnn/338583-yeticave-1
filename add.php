<?php
require_once("db.php");
require_once("init.php");
require_once("functions.php");

$lot = [];
$errors = [];
$dict = [];
$add_lot = [];

$categories = get_categories($con);

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


        // обход массива с обязательными полями
        foreach ($required as $field) {
            // если поле пустое, добавить ошибку
            if (empty($add_lot[$field])) {
                $errors[$field] = 'Поле незаполнено';
            }
        }
        if (!is_numeric($add_lot['price']) || $add_lot['price'] <= 0) {
            $errors['price'] = 'Заполните поле корректными данными';
        }

        if (!is_numeric($add_lot['step']) || $add_lot['step'] <= 0) {
            $errors['step'] = 'Заполните поле корректными данными';
        }

        // если категория со значением 'выберите категорию', значит добавляем ошибку
        if ($add_lot['category'] === 'Выберите категорию') {
            $errors['category'] = 'Выберите категорию';
        }

        // проверка даты
        if (strtotime($add_lot['date']) <= strtotime('now')) {
            $errors['date'] = 'Дата завершения должна быть больше текущей хотя бы на один день';
        }

        // проверяем наличие изображения по имени поля для загрузки
        if (isset($_FILES['lot_img']['name']) && $_FILES['lot_img']['name']) {
            // записываем временное имя на сервере в переменную
            $tmp_name = $_FILES['lot_img']['tmp_name'];
            // и исходное название изображения
            $file_name = $_FILES['lot_img']['name'];

            if (!empty($_FILES['lot_img']['name'])) {
                // узнаем MIME-тип файла
                $file_open = finfo_open(FILEINFO_MIME_TYPE);
                $file_info = finfo_file($file_open, $tmp_name);

                // сравниваем с нужными форматами изображений, если форматы не сходятся, записываем ошибку
                if ($file_info !== 'image/png' && $file_info !== 'image/jpeg') {
                    $errors['lot_img'] = 'Загрузите фотографию в формате PNG/JPG';
                } // если проверка прошла успешно, перемещаем файл из временной папки
                else {
                    move_uploaded_file($tmp_name, 'img/' . $file_name);
                    $add_lot['lot_img'] = 'img/' . $file_name;
                }
            }
        } // если файл вообще не был загружен, записываем ошибку
        else {
            $errors['lot_img'] = 'Вы не загрузили изображение лота';
        }
        // проверяем есть ли ошибки, если да выводим их вместе с формой
        if (!count($errors)) {
            $add_lot = add_lot($con, $add_lot);
        }
        // если ошибок нет, добавляем лот в БД и перенаправляем пользователя на страницу с новым лотом
    } // если метод не POST, значит пользователь перешел по ссылке - показываем просто форму для заполнения
    else {
        $page_content = include_template('add.php', ['categories' => $categories]);
    };

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
?>
