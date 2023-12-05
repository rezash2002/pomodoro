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
        $pomodoro = $_POST;
    } else {
        $pomodoro = $inputData;
    }
    echo pomodoro($pomodoro);
}

function pomodoro($pomodoro)
{
    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $message = "";
        $pomodoroID = null;
        $time = null;
        $taskID = null;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        if (isset($pomodoro['id']))
            $pomodoroID = $pomodoro['id'];
        if (isset($pomodoro['time']))
            $time = $pomodoro['time'];
        if (isset($pomodoro['task_id']))
            $taskID = $pomodoro['task_id'];


        if ($pomodoroID != null) {

            //Change task_id from table of pomodoro_time
            if ($taskID != null) {
                $sql = "UPDATE pomodoro_time SET task_id = $taskID WHERE id = $pomodoroID";

                if ($conn->query($sql) === TRUE) {
                    $message = "changed";
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }

            } //Update Pomodoro Time -- need pomodoro_id from client
            else if ($time == null) {
                $sql = "UPDATE pomodoro_time SET time_spent = 1+ (SELECT time_spent WHERE id = $pomodoroID) WHERE id = $pomodoroID";

                if ($conn->query($sql) === TRUE) {
                    $message = "updated";
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }

            } //Finish the Pomodoro  -- need pomodoro_id and spent_time from client
            else {
                $endTime = str_replace($persian, $english, jdate("Y-m-d H:i:s"));
                $sql = "UPDATE pomodoro_time SET end_time = '$endTime' ,
                time_spent = $time WHERE id = $pomodoroID";

                if ($conn->query($sql) === TRUE) {
                    $message = "finished";
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }


            }
        } else {
            //Add New Pomodoro -- need task_id from client


            //Pomodoro review in progress
            $query = "SELECT pomodoro_time.* FROM pomodoro_time
                LEFT JOIN task ON task.id = pomodoro_time.task_id
                WHERE task.user_id = $user_id AND end_time IS NULL ";

            $query_run = mysqli_query($conn, $query);

            if ($query_run->num_rows > 0) {
                $message = "running";
            } else {
                $startTime = str_replace($persian, $english, jdate("Y-m-d H:i:s"));

                $query = "INSERT INTO pomodoro_time (task_id, time_spent, start_time)
                VALUES ( $taskID, 0 , '$startTime' )";

                if ($conn->query($query) === TRUE) {
                    $message = $conn->insert_id;
                } else {
                    $message = "Error: " . $query . "<br>" . $conn->error;
                }

            }
        }

        return $message;

    }
}


