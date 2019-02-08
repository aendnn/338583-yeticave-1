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

?>
