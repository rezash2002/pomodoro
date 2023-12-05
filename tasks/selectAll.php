<?php

require_once '../ConnectToDB.php';
require_once '../checkCookie.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: POST');

$requestMethod = $_SERVER["REQUEST_METHOD"];


if ($requestMethod == "POST") {

    echo SelectAllTask();

}


function SelectAllTask()
{


    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $tasks = array();
        $query = "SELECT task.*  ,GROUP_CONCAT(category.id,' ', category.color) as flag
                from task
                LEFT JOIN category_items on task.id = category_items.task_id
                LEFT JOIN category ON category_items.category_id = category.id
                where task.is_delete = 'false' AND task.user_id = $user_id
                GROUP BY task.id";

        $query_run = mysqli_query($conn, $query);

        while ($row = $query_run->fetch_assoc()) {
            $flags = [];
            foreach (explode(",", $row['flag']) as $flag) {
                $flag = explode(",", str_replace(" ", ',', $flag));
                if (isset($flag[0]) && isset($flag[1])) {
                    $flags[] = array(

                        'id' => $flag[0],
                        'color' => $flag[1]
                    );
                }
            }
            $data[] = array(
                'id' => $row['id'],
                'title' => $row['name'],
                'parentId' => $row['parent_id'],
                'done' => $row['done'],
                'flag' => $flags
            );
        }
        return json_encode($data);
    }
}

?>