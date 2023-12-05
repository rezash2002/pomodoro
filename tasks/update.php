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
        $task = $_POST;
    } else {
        $task = $inputData;
    }
    echo UpdateTask($task);
}

function UpdateTask($task)
{
    global $conn;

    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $title = $task['title'];
        $id = $task['id'];


        $sql = "UPDATE task SET name = '$title' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            return "updated";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>