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
    echo Delete($data);
}

function Delete($data)
{

    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $task_id = null;
        $category_id = null;

        if (isset($data['task_id']) && isset($data['category_id'])) {
            $task_id = $data['task_id'];
            $category_id = $data['category_id'];
            $IDs = implode(',', $task_id);
        }


        $query = "DELETE FROM category_items where category_id = $category_id AND category_items.task_id in ($IDs)";
        if ($conn->query($query) === TRUE) {
            return 'deleted';
        } else {
            return "Error: " . $query . "<br>" . $conn->error;
        }


    }

}
