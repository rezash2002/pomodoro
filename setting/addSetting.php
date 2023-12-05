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
    echo addSetting($data);
}

function addSetting($data)
{
    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $id = null;
        if (isset($data['id'])) {
            $id = $data['id'];
        }
        $config = $data['config'];
        $config = json_encode($config, JSON_UNESCAPED_UNICODE);

        if ($id == null) {
            $query = "INSERT INTO setting (user_id , config) VALUES ($user_id , '$config' )";

            if ($conn->query($query) === TRUE) {
                return $conn->insert_id;
            } else {
                return "Error: " . $query . "<br>" . $conn->error;
            }
        } else {
            $sql = "UPDATE setting SET config = '$config' WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                return "updated";
            } else {
                return "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }


}
