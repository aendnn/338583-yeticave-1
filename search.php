<?php
require_once("db.php");
require_once("init.php");
require_once("functions.php");

$categories = get_categories($connect);
$lots = get_all_lots($connect);
$search = trim($_GET['q']) ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!strlen($search)) {
        $page_content = include_template('search.php', ['lots' => [],'categories' => $categories, 'search' => $search]);
    }
    else {
        $search_sql = 'SELECT `lots`.`id`, `lots`.`pic`, `lots`.`title`, `cat_id`, `user_id`, `desc`, `primary_price`, `price`, 
    `lots`.`date_create`, `lots`.`date_end`, `users`.`name`, `categories`.`name` AS `cat_name` FROM `lots`
    JOIN `users` ON `lots`.`user_id` = `users`.`id`
    JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
    WHERE MATCH (`title`,`desc`) AGAINST (?)';

        $search_prepare = db_get_prepare_stmt($connect, $search_sql, [$search]);
        mysqli_stmt_execute($search_prepare);

        if ($lots = mysqli_stmt_get_result($search_prepare)){
            $lots = mysqli_fetch_all($lots, MYSQLI_ASSOC);
            $page_content = include_template('search.php', ['lots' => $lots,'categories' => $categories, 'search' => $search]);
        }
    }
}
$layout_content = include_template("layout.php", ['page_content' => $page_content,
    'title' => 'Поиск лота',
    'categories' => $categories]);


print($layout_content);
