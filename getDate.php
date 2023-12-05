<?php
require_once 'jdf.php';

function GetJDate($format){
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    return str_replace($persian, $english, jdate($format));
}
