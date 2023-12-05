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
    echo getCategory($data);
}

function getCategory($data)
{
    $id = null;
    global $conn;
    $categories = array();
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;

        $taskID = $data['task_id'];

        $query = "SELECT category.id, category.name , category.color from category 
            INNER JOIN category_items ON category_items.category_id = category.id
            where category_items.task_id = $taskID";

        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = array(
                    'title' => $row['name'],
                    'color' => $row['color'],
                    'id' => $row['id']
                );
            }
        }

        return json_encode($categories);
    }

}
