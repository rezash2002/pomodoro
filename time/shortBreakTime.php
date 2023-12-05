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
        $shortBreak = $_POST;
    } else {
        $shortBreak = $inputData;
    }
    echo shortBreak($shortBreak);
}

function shortBreak($shortBreak)
{
    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $message = "";
        $pomodoroID = null;
        $time = null;
        $shortBreakID = null;
        if (isset($shortBreak['pomodoro_id']))
            $pomodoroID = $shortBreak['pomodoro_id'];
        if (isset($shortBreak['time']))
            $time = $shortBreak['time'];
        if (isset($shortBreak['id']))
            $shortBreakID = $shortBreak['id'];


        if ($shortBreakID != null) {

            //Finish the ShortBreak  -- need short break ID and short break time from client
            if ($time != null) {
                $sql = "UPDATE rest_time SET 
                time_spent = $time WHERE pomodoro_id = $shortBreakID";

                if ($conn->query($sql) === TRUE) {
                    $message = "finished";
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }

            }
        } else if ($pomodoroID != null) {
            //Add New ShortBreak -- need pomodoro_id from client
            $query = "INSERT INTO rest_time (pomodoro_id, time_spent)
            VALUES ( $pomodoroID, 0 )";

            if ($conn->query($query) === TRUE) {
                $message = $pomodoroID;
            } else {
                $message = "Error: " . $query . "<br>" . $conn->error;
            }
        }

        return $message;

    }
}


