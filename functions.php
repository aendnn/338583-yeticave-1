<?php
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

function price($number) {
    $cost = ceil($number);
    $cost .= " ₽";
    if ($number > 1000) {
        $cost = number_format($number, "0", " ", " ");
        $cost .= " ₽";
    }
    return $cost;
};

function esc($str) {
    $text = htmlspecialchars($str);
    return $text;
};

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }
    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
};

function time_counter($cur_date, $end_date) {
    $end_time = date_create($end_date);
    $cur_time = date_create($cur_date);
    $date_diff = date_diff($end_time, $cur_time);
    $time_count = date_interval_format($date_diff, '%H:%I');
    return $time_count;
};

function bid_time ($bid_date) {
    $now = time();
    $int = $now - strtotime($bid_date);

    if ($int > 60 && $int < 3600) {
        $ago = floor($int / 3600) . ' минут назад';
    }

    elseif ($int > 3600 && $int < 86400) {
        $ago = floor($int / 3600) . ' часов назад';
    }
    elseif ($int > 86400) {
        $ago = date('d.m.Y в H:i', strtotime($bid_date));
    }
    else {
        $ago = 'меньше минуты назад';
    }
    return $ago;
}

function db_get_prepare_stmt($connect, $sql, $data = []) {
    $stmt = mysqli_prepare($connect, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
};

// функция на получение записей
function db_get_data($connect, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
};
// функция на добавление записей
function db_insert_data($connect, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $result = mysqli_insert_id($connect);
    }
    return $result;
};

function upload_file ($tmp_name, $file_name, $file_path) {
    if (!empty($file_name)) {
        // узнаем MIME-тип файла
        $file_open = finfo_open(FILEINFO_MIME_TYPE);
        $file_info = finfo_file($file_open, $tmp_name);

        // сравниваем с нужными форматами изображений, если форматы не сходятся, записываем ошибку
        if ($file_info !== 'image/png' && $file_info !== 'image/jpeg') {
            $errors = 'Загрузите фотографию в формате PNG/JPG';
            return $errors;
        } // если проверка прошла успешно, перемещаем файл из временной папки
        else {
            move_uploaded_file($tmp_name, 'img/' . $file_name);
            $file_path = 'img/' . $file_name;
        }
    }
    return $file_path;
}

function get_categories ($connect) {
    $categories_query = 'SELECT `id`, `name` FROM `categories` ORDER BY `id` ASC';
    $result_categories = mysqli_query($connect, $categories_query);

    if ($result_categories) {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
        return $categories;
    }
    else {
        $error = mysqli_error($connect);
        return $error;
    }
};

function get_all_lots ($connect) {
    $lots_query = 'SELECT `lots`.`id`, `lots`.`title`, `primary_price`, `pic`, `categories`.`name` AS `cat_name` FROM `lots`
  INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
  WHERE `lots`.`date_end` != ?
        ORDER BY `lots`.`date_create` DESC LIMIT 6';
    $now = 'CURRENT_DATE()';
    $lots = db_get_data($connect, $lots_query, [$now]);

    if (!$lots) {
        $error = mysqli_error($connect);
        return $error;
    }
    return $lots;
}

function get_lot ($connect, $id) {
    $lot_query = 'SELECT `lots`.`id`, `lots`.`title`, `primary_price`, `price`, `lots`.`date_end`, `lots`.`user_id`, `pic`, `desc`, `step_bid`, `users`.`name`, `categories`.`name` AS `cat_name` FROM `lots`
    INNER JOIN `categories` ON `lots`.`cat_id` = `categories`.`id`
    INNER JOIN `users` ON `lots`.`user_id` = `users`.`id`
    WHERE `lots`.`id` =  ?';
    $lot_prepared = db_get_prepare_stmt($connect, $lot_query, [$id]);
    mysqli_stmt_execute($lot_prepared);
    $lot_object = mysqli_stmt_get_result($lot_prepared);

    if (mysqli_num_rows($lot_object)) {
        $lot = mysqli_fetch_all($lot_object, MYSQLI_ASSOC);
    }
    else {
        $error = http_response_code(404);
        header("Location: /404.php");
    };
    return $lot;
}

function add_lot ($connect, $add_lot) {
    $add_lot_query = 'INSERT INTO `lots` (`date_create`, `user_id`, `title`, `desc`, `pic`, `date_end`, `primary_price`, `step_bid`, `cat_id`)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
    $add_lot_prepare = db_get_prepare_stmt($connect, $add_lot_query, [$_SESSION['user']['id'], $add_lot['title'], $add_lot['desc'], $_POST['lot_img'], $add_lot['date'], $add_lot['price'], $add_lot['step'], $add_lot['category']]);
    $add_lot = mysqli_stmt_execute($add_lot_prepare);

    if ($add_lot) {
        $add_lot_id = mysqli_insert_id($connect);
        header("Location: lot.php?id=" . $add_lot_id);
        return $add_lot_id;
    }
    return $add_lot;
}

function get_bids ($connect, $id) {
    $bids_query = 'SELECT `bids`.`id`, `bids`.`date_bid`, `bids`.`sum_bid`, MAX(`bids`.`sum_bid`) AS `max_bid`, `bids`.`user_id`, `users`.`name` AS `user_name` FROM `bids`
                   INNER JOIN `users` ON `bids`.`user_id` = `users`.`id` 
                   INNER JOIN `lots` ON `bids`.`lot_id` = `lots`.`id`
                   WHERE `bids`.`lot_id` = ?
                   GROUP BY `bids`.`id`
                   ORDER BY `bids`.`date_bid` DESC';

    $bids_prepared = db_get_prepare_stmt($connect, $bids_query, [$id]);
    mysqli_stmt_execute($bids_prepared);
    $bids_object = mysqli_stmt_get_result($bids_prepared);

    if ($bids_prepared) {
        $bids = mysqli_fetch_all($bids_object, MYSQLI_ASSOC);
    }
    return $bids;
}

function get_user_email ($connect, $email) {
    $sql_email = "SELECT `id` FROM `users` WHERE `email` = ?";
    $email_query = db_get_prepare_stmt($connect, $sql_email, [$email]);
    mysqli_stmt_execute($email_query);
    $email_result = mysqli_stmt_get_result($email_query);

    return $email_result;
}

function reg_user ($connect, $registration, $password) {
    $sql_registration = "INSERT INTO `users` (`email`, `name`, `password`, `contacts`, `avatar`, `dt_add`) 
                                VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = db_get_prepare_stmt($connect, $sql_registration, [$registration['email'], $registration['username'], $password, $registration['contacts'], $_POST['avatar']]);
    $registration_result = mysqli_stmt_execute($stmt);
    return $registration_result;
}

function add_bid ($connect, $bid, $user_id, $id){
    $bid_query = 'INSERT INTO `bids` (`date_bid`, `sum_bid`, `user_id`, `lot_id`) VALUES (NOW(), ?, ?, ?)';
    $bid_stmt = db_get_prepare_stmt($connect, $bid_query, [$bid['cost'], $user_id, $id]);
    $bid_result = mysqli_stmt_execute($bid_stmt);

    return $bid_result;
}
