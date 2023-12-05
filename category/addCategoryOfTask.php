<?php

require_once '../ConnectToDB.php';
require_once '../jdf.php';
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
    echo Add($data);
}

function Add($data)
{

    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $task_id = null;
        $category_id = null;
        $mess = null;

        if (isset($data['task_id']) && isset($data['category_id'])) {
            $task_id = $data['task_id'];
            $category_id = $data['category_id'];

        }

        foreach ($category_id as $id) {

            $query = "INSERT INTO category_items( category_id, task_id)
            VALUES ( $id, $task_id )";
            if ($conn->query($query) === TRUE) {
                $mess[] = $conn->insert_id;
            } else {
                $mess[] = "Error: " . $query . "<br>" . $conn->error;
            }
        }

        return json_encode($mess);
    }

}
