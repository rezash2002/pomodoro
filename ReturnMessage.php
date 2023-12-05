<?php
function ReturnMessage($data , $message , $success, $statusCode){

    echo json_encode(array(
        "message" => $message,
        "success" => $success,
        'data' => $data
    ));
    http_response_code($statusCode);
    exit();
}