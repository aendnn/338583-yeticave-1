<?php
require_once('db.php');
require_once("data.php");
require_once("functions.php");

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
}


$page_content = include_template('404.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => '404',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'categories' => $categories
]);

print($layout_content);
?>
