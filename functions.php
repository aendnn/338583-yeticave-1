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

function db_get_prepare_stmt($con, $sql, $data = []) {
    $stmt = mysqli_prepare($con, $sql);

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
function db_get_data($con, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
};
// функция на добавление записей
function db_insert_data($con, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $result = mysqli_insert_id($con);
    }
    return $result;
};


?>
