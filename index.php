<?php
require_once("functions.php");

$is_auth = rand(0, 1);

$user_name = 'Анна'; // укажите здесь ваше имя

$db = ['host' => 'localhost', 'user' => 'root', 'password' => '', 'database' => 'yeticave'];
$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($con, "utf8");

if (!$con) {
    print('Ошибка подключения:' . mysqli_connect_error());
}
else {
    $categories_query = 'SELECT `id`, `name` FROM `categories` ORDER BY `id` ASC';
    $result_categories = mysqli_query($con, $categories_query);

    if ($result_categories) {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    }
    else {
        $error = mysqli_error($con);
    };

    $lots_query = 'SELECT `lots`.`id`, `lots`.`title`, `primary_price`, `pic`, `categories`.`name` AS `cat_name` FROM `lots`
  INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
  WHERE `lots`.`date_end` != ?
        ORDER BY `lots`.`date_create` DESC LIMIT 6';
    $ts_lot = 'CURRENT_DATE()';
    $lots = db_get_data($con, $lots_query, [$ts_lot]);

    if ($lots) {
        $page_content = include_template('index.php', [
            'lots' => $lots,
            'categories' => $categories
        ]);
    }
    else {
        $error = mysqli_error($con);
        $page_content = include_template('404.php', ['categories' => $categories]);
    }
};

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lots]);
$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'page_content' => $page_content
]);
print($layout_content);
?>
