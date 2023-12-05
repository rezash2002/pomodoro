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
    echo getTaskTimes($data);
}

function getSubTasksID($parentID, $IDs)
{
    global $conn;
    if ($parentID) {
        $query = "SELECT id FROM task where parent_id = $parentID";
        $query_run = mysqli_query($conn, $query);
        while ($row = $query_run->fetch_assoc()) {
            $IDs[] = $row['id'];
            $IDs = getSubTasksID($row['id'], $IDs);
        }
    } else {
        return null;
    }
    return $IDs;
}


function getTaskTimes($data)
{
    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $taskID = $data['id'];
        $IDs[] = $taskID;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $startDate = null;
        $executeSubtasks = $data['executeSubtasks'];
        $dates = null;

        if ($executeSubtasks) {
            $IDs = getSubTasksID($taskID, $IDs);

        }
        $array = $IDs;
        $IDs = implode(',', $array);

        if (isset($data['startDate']) && isset($data['endDate']) && isset($data['timeDisplayAs'])) {
            $startDate = str_replace($persian, $english, $data['startDate']);
            $endDate = str_replace($persian, $english, $data['endDate']);

            switch ($data['timeDisplayAs']) {
                case 'روزانه':
                    $query = "SELECT DATE_FORMAT(pomodoro_time.start_time,'%Y-%m-%d') as date, ROUND(SUM(pomodoro_time.time_spent),2) as ptime
                    ,IFNULL(ROUND(SUM(rest_time.time_spent),2),0) as rtime FROM pomodoro_time
                    LEFT JOIN rest_time ON rest_time.pomodoro_id = pomodoro_time.id
                    WHERE pomodoro_time.start_time BETWEEN '$startDate' and '$endDate'
                    and pomodoro_time.task_id in ($IDs)
                    GROUP BY date";
                    break;
                case 'هفتگی':
                    $query = "SELECT CONCAT(YEAR(pomodoro_time.start_time),'-',WEEK(pomodoro_time.start_time)) as date, ROUND(SUM(pomodoro_time.time_spent),2) as ptime
                    ,IFNULL(ROUND(SUM(rest_time.time_spent),2),0) as rtime FROM pomodoro_time
                    LEFT JOIN rest_time ON rest_time.pomodoro_id = pomodoro_time.id
                    WHERE pomodoro_time.start_time BETWEEN '$startDate' and '$endDate'
                    and pomodoro_time.task_id in ($IDs)
                    GROUP BY WEEK(pomodoro_time.start_time)";

                    break;
                case 'ماهانه':
                    $query = "SELECT DATE_FORMAT(pomodoro_time.start_time,'%Y-%m-%d') as date, ROUND(SUM(pomodoro_time.time_spent),2) as ptime
                    ,IFNULL(ROUND(SUM(rest_time.time_spent),2),0) as rtime FROM pomodoro_time
                    LEFT JOIN rest_time ON rest_time.pomodoro_id = pomodoro_time.id
                    WHERE pomodoro_time.start_time BETWEEN '$startDate' and '$endDate'
                    and pomodoro_time.task_id in ($IDs)
                    GROUP BY MONTH(date)";
                    break;
            }

        } else {

            $query = "SELECT date FROM task WHERE id = $taskID";

            $query_run = mysqli_query($conn, $query);
            if ($row = $query_run->fetch_assoc()) {
                $startDate = $row['date'];
            }
            $endDate = str_replace($persian, $english, jdate("Y-m-d H:i:s"));

            $query = "SELECT DATE_FORMAT(pomodoro_time.start_time,'%Y-%m-%d') as date, ROUND(SUM(pomodoro_time.time_spent),2) as ptime
                    ,IFNULL(ROUND(SUM(rest_time.time_spent),2),0) as rtime FROM pomodoro_time
                    LEFT JOIN rest_time ON rest_time.pomodoro_id = pomodoro_time.id
                    WHERE pomodoro_time.start_time BETWEEN '$startDate' and '$endDate'
                    and pomodoro_time.task_id in ($IDs)
                    GROUP BY MONTH(date)";
        }


        $query_run = mysqli_query($conn, $query);
        $times = null;
        while ($row = $query_run->fetch_assoc()) {

            $restTime = $row['rtime'];
            $a = number_format((float)(($row['rtime'] * 100) / $row['ptime']),
                2, '.', '');

            $a = 100 - $a;

            $times[] = array(
                'time' => $row['ptime'] + $restTime,
                'date' => $row['date'],
                'efficiency' => $a
            );
        }

        $dates['date'] = array(
            'maxDate' => str_replace($persian, $english, jdate("Y-m-d H:i:s"))
        );

        $dates['dataSets'] = $times;
        return json_encode($dates);

    }


}