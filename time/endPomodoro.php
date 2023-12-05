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
    echo endPomodoro($data);
}

function endPomodoro($data)
{
    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $id = null;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $endTime = str_replace($persian, $english, jdate("Y-m-d H:i:s"));

        $query = "SELECT pomodoro_time.id FROM pomodoro_time
                LEFT JOIN task ON task.id = pomodoro_time.task_id
                WHERE task.user_id = $user_id AND end_time IS NULL ";

        $query_run = mysqli_query($conn, $query);

        if ($query_run->num_rows > 0) {
            if ($row = $query_run->fetch_assoc())
                $id = $row['id'];

            $query = "UPDATE pomodoro_time set end_time = '$endTime' where id = $id";

            if ($conn->query($query) === TRUE) {
                return "ended";
            } else {
                return "Error: " . $query . "<br>" . $conn->error;
            }
        }


    }

}
