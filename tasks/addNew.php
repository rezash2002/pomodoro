<?php

require_once '../ConnectToDB.php';
require_once '../getDate.php';
require_once '../checkCookie.php';


header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: POST');


$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (empty($inputData)) {
        $newTask = $_POST;
    } else {
        $newTask = $inputData;
    }
    echo AddNewTask($newTask);
}

function AddNewTask($newTask)
{

    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        http_response_code(401);
    } else {
        $user_id = $m;
        $title = $newTask['title'];
        $parentID = $newTask['parentId'];


        if ($parentID == null) {
            $query = "SELECT id FROM task WHERE  parent_id IS NULL AND user_id = $user_id AND is_delete = 'false' ";
            $query_run = mysqli_query($conn, $query);
            if ($query_run->num_rows > 0) {
                if ($row = $query_run->fetch_assoc()) {
                    $parentID = $row['id'];
                }
            }
        }
        $date = GetJDate("Y-m-d H:i:s");
        $query = "INSERT INTO task (user_id, name, parent_id, date)
        VALUES ( $user_id , '$title' , $parentID ,  '$date')";


        if ($conn->query($query) === TRUE) {
            return $conn->insert_id;
        } else {
            return "Error: " . $query . "<br>" . $conn->error;
        }
    }


}
