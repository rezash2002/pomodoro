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
    echo DeleteTask($data);
}

function DeleteTask($task)
{

    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        if (isset($task['id']) && $task['id'] != null) {
            $id = $task['id'];
            $query = "UPDATE task SET is_delete='1' WHERE id=$id";

            if ($conn->query($query) === TRUE) {
                return 'deleted';
            } else {
                return "Error Deleted record: " . $conn->error;
            }
        }
    }
}


?>