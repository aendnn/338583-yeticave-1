<?php
require_once("functions.php");
require_once("db.php");
require_once('init.php');

$categories = get_categories($con);
$lots = get_all_lots($con);

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lots]);
$layout_content = include_template('layout.php', [
    'title' => 'Главная',
    'categories' => $categories,
    'page_content' => $page_content
]);

print($layout_content);
?>

