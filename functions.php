<?php

function price($number) {
    $cost = ceil($number);
    $cost .= " ₽";
    if ($number > 1000) {
        $cost = number_format($number, "0", " ", " ");
        $cost .= " ₽";
    }
    return $cost;
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

?>
