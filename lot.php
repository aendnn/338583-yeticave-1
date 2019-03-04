<?php
require_once('functions.php');
require_once('db.php');
require_once('init.php');
$lot = [];
$id = $_GET['id'] ?? '';
$user_id = [];
$bid = [];
$bids = [];
$errors = [];
$bid_done = false;

$categories = get_categories($con);
$lot = get_lot($con, $id);
$bids = get_bids($con, $id);

if (isset($_SESSION['user']['id'])) {
    $user_id = $_SESSION['user']['id'];
}

    if (isset($_SESSION['user'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bid = $_POST;
            $required = ['cost'];
            $min_cost = $lot[0]['primary_price'] + $lot[0]['step_bid'];

            if (empty($bid['cost'])) {
                $errors['cost'] = 'Заполните поле';
            }
            elseif ($bid['cost'] < $min_cost) {
                $errors['cost'] = 'Введите минимальную цену';
            }
            elseif (!is_numeric($bid['cost']) || $bid['cost'] < 0) {
                $errors['cost'] = 'Введите целое положительное число';
            }

            if (empty($errors)) {
                $bid_result = add_bid($con, $bid, $user_id, $id);
                header("Refresh: 0");
            }
        }
    }

    if (!empty($bids)) {
        foreach ($bids as $bid_user) {
            if ($bid_user['user_id'] === $user_id) {
                $bid_done = true;

                if ($bid_done) {
                    $errors['cost'] = 'Ставка уже сделана';
                }
            }
        }
    }

$page_content = include_template('lot.php', [
    'lot' => $lot,
    'categories' => $categories,
    'errors' => $errors,
    'bid' => $bid,
    'bids' => $bids,
    'bid_done' => $bid_done,
    'user_id' => $user_id,
    'id' => $id
]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'title' => 'Лот',
    'categories' => $categories
]);

print($layout_content);
?>
