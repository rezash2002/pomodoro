<?php


require_once '../ConnectToDB.php';

require_once '../checkCookie.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: PUT');


$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'PUT') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (empty($inputData)) {
        $task = $_POST;
    } else {
        $task = $inputData;
    }
    echo ChangeParent($task);
}

function ChangeParent($POST)
{
    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $taskID = $POST['taskID'];
        $parentID = $POST['parentID'];


        $sql = "UPDATE task SET parent_id = '$parentID' WHERE id = $taskID";

        if ($conn->query($sql) === TRUE) {
            return "changed";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>