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
    echo CategoryItems($data);
}

function CategoryItems($data)
{
    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $categoryID = null;
        $taskID = null;
        $id = null;
        if (isset($data['id'])) {
            $id = $data['id'];
        } else if (isset($data['taskID']) && isset($data['categoryID'])) {
            $categoryID = $data['categoryID'];
            $taskID = $data['taskID'];
        }
        if ($id == null) {
            //add new category
            $query = "INSERT INTO category_items (task_id , category_id) VALUES ($taskID , $categoryID )";

            if ($conn->query($query) === TRUE) {
                return $conn->insert_id;
            } else {
                return "Error: " . $query . "<br>" . $conn->error;
            }
        } else {
            //update the category
            $sql = "DELETE FROM category_items WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                return "deleted";
            } else {
                return "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }


}
