<?php
require_once('functions.php');
require_once('db.php');
require_once('data.php');

$id = $_GET['id'] ?? '';

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
    }

    $lot_query = 'SELECT `lots`.`title`, `price`, `pic`, `desc`, `step_bid`, `users`.`name`, `categories`.`name` AS `cat_name` FROM `lots`
    INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
    INNER JOIN `users` ON `lots`.`user_id` = `users`.`id`
    WHERE `lots`.`id` =  ?';
    $lot_prepared = db_get_prepare_stmt($con, $lot_query, [$id]);
    $lot_execute = mysqli_stmt_execute($lot_prepared);
    $lot_object = mysqli_stmt_get_result($lot_prepared);

    if (mysqli_num_rows($lot_object)) {
        $lot = mysqli_fetch_all($lot_object, MYSQLI_ASSOC);
        $page_content = include_template('lot.php', [
            'lot' => $lot,
            'categories' => $categories
        ]);
    }
    else {
        $error = http_response_code(404);
        $page_content = include_template("404.php", ['categories' => $categories]);
    };

}

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Лот',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'categories' => $categories
]);

print($layout_content);
?>
