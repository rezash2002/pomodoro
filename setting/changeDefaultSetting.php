<?php

require_once '../ConnectToDB.php';
require_once '../checkCookie.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: POST');


$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (empty($inputData)) {
        $data = $_POST;
    } else {
        $data = $inputData;
    }
    echo changeSettingID($data);
}

function changeSettingID($data)
{
    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $id = $data['id'];


        $query = "UPDATE user set setting_id = $id where id = $user_id";

        $result = $conn->query($query);
        if ($conn->query($query) === TRUE) {
            return "updated";
        } else {
            return "Error: " . $query . "<br>" . $conn->error;
        }
    }

}
