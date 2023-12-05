<?php
require_once '../CheckMethod.php';
require_once '../ConnectToDB.php';
require_once '../ReturnMessage.php';
require_once '../checkCookie.php';
checkMethod('POST');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST') {
//    $inputData = json_decode(file_get_contents("php://input"), true);
//    $data = $inputData;
    global $conn;
    $m = checkCookie($conn);
    $user_id = $m;
    $query = "SELECT * from user where id = $user_id and is_delete = 'false'";
    $query_run = mysqli_query($conn, $query);
    if ($query_run->num_rows > 0) {
        if ($row = $query_run->fetch_assoc()) {
            ReturnMessage($row['first_name'] . ' ' . $row['last_name'],
                "", true, 200);
        }
    } else {
        ReturnMessage(null, "خطا در احراز هویت", false, 403);
    }

}