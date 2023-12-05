<?php

require_once '../CheckMethod.php';
require_once '../ConnectToDB.php';
require_once '../ReturnMessage.php';
require_once '../checkCookie.php';
require_once '../getDate.php';
checkMethod('POST');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST') {

    global $conn;
    $m = checkCookie($conn);
    $user_id = $m;

    //get active task and mode from user table
    $query = "SELECT active_pomodoro,mode from user where id = $user_id and is_delete = 'false'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run->num_rows > 0) {
        if ($row = $query_run->fetch_assoc()) {

            $active_pomodoro = $row['active_pomodoro'];
            $mode = $row['mode'];
            //check active task
            if ($active_pomodoro !== null){
                try {

                    $conn->begin_transaction();
                    $conn->autocommit(true);

                    //get some fields from pomodoro_time table
                    $query = "SELECT time_spent,task_id,last_update from pomodoro_time where id = $active_pomodoro and is_delete = 'false'";
                    $query_run = mysqli_query($conn, $query);
                    if ($query_run->num_rows > 0) {
                        if ($row = $query_run->fetch_assoc()) {
                            //set some variable
                            $task_id = $row['task_id'];
                            $time_spent = $row['time_spent'];
                            $last_update = date_create(['last_update']);
                            $now = date_create(GetJDate("Y-m-d H:i:s"));

                            //calculate time of between of two dates
                            $date_diff = date_diff($now, $last_update);
                            $s_diff = $date_diff->format("%s");
                            $m_diff = $date_diff->format("%i");

                            if ($mode == 'continue') {
                                if ($s_diff < 35) {
                                    //update last_update field
                                    $query = "UPDATE pomodoro_time SET last_update = '$now' WHERE id = $active_pomodoro";
                                    if ($conn->query($query) === TRUE) {
                                        //get time of pomodoro
                                        ReturnMessage(json_encode(array("time_spent" => $time_spent, "mode" => 'continue')),
                                            "", true, 200);
                                    } else {
                                        ReturnMessage(null,
                                            "خطای سرور", false, 500);
                                    }
                                } else if ($s_diff > 35) {
                                    //set mode to pause and get time of pomodoro
                                    $query = "UPDATE user SET mode = 'pause' WHERE id = $user_id";
                                    if ($conn->query($query) === TRUE) {
                                        ReturnMessage(json_encode(array("time_spent" => $time_spent, "mode" => 'pause')),
                                            "", true, 200);
                                    } else {
                                        ReturnMessage(null,
                                            "خطای سرور", false, 500);
                                    }
                                }
                            }
                            //if mode = pause And (last update > 20 minute Or the beginning of a new day)
                            else if ($mode == 'pause' &&
                                ($m_diff > 20 || ($now->format("d") > $last_update->format("d")))) {
                                //ending pomodoro
                                $query = "UPDATE user SET active_pomodoro = null WHERE id = $user_id";
                                if ($conn->query($query) === TRUE) {
                                    ReturnMessage(null,
                                        "کاربر تسک فعالی ندارد", true, 200);
                                } else {
                                    ReturnMessage(null,
                                        "خطای سرور", false, 500);
                                }
                            } else {
                                //get time of pomodoro
                                ReturnMessage(json_encode(array("time_spent" => $time_spent, "mode" => 'pause')),
                                    "", true, 200);
                            }
                        }
                    }
                }catch (Exception $e){
                    $conn->rollback();
                    ReturnMessage(null ,  $e->getMessage()."خطای سرور: ", false,500);
                }
            }else{
                ReturnMessage(null,
                    "کاربر تسک فعالی ندارد", true, 200);
            }
        }
    }
}