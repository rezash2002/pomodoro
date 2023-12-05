<?php

include '../setting.php';

//add new task from client database to MySql

//get data
$task_id = 4;
$time_spent = 0;
$start_time = date("Y-m-d H:i:s", mktime(06, 30, 00, 6, 16, 2023));
$end_time = date("Y-m-d H:i:s",mktime(06, 33, 00, 6, 16, 2023));

if($end_time < date("Y-m-d H:i:s") && $end_time > $start_time){
    
    $sql = "SELECT p.id
        from pomodoro_time as p
        INNER JOIN task on task.id = p.task_id
        WHERE task.user_id = 3 AND ('$start_time' BETWEEN p.start_time AND p.end_time OR p.start_time BETWEEN '$start_time' AND '$end_time') 
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        echo"در این زمان وظیفه ای در حال انجام بوده است و زمان مورد نظر اضافه نمیشود";
    
    } else {
        $sql = "INSERT INTO pomodoro_time (`task_id`, `start_time`, `end_time`, `time_spent`) 
            VALUES ($task_id , '$start_time', '$end_time' , $time_spent); ";

        if ($conn->query($sql) === TRUE) {
            // $last_id = $conn->insert_id;
            echo "New record created successfully ";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();

    }

}else {
    echo "Unvalid Time";
}




?>