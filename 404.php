<?php
require_once('db.php');
require_once("init.php");
require_once("functions.php");

$categories = get_categories($con);

$page_content = include_template('404.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => '404',
    'categories' => $categories
]);

print($layout_content);
?>
