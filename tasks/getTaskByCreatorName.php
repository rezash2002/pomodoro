<?php

include '../setting.php';


$sql = "SELECT task.* , SUM(pomodoro_time.time_spent) as time ,CONCAT(user.first_name, ' ', user.last_name) as user_name 
from task
LEFT JOIN pomodoro_time ON pomodoro_time.task_id = task.id
INNER JOIN user on task.user_id = user.id
WHERE CONCAT(user.first_name, user.last_name) LIKE '%ali%' AND task.is_delete = false
GROUP BY task.id";


$result = $conn->query($sql);

if ($result->num_rows > 0) {
//   output data of each row


    while($row = $result->fetch_assoc()) {
        $row["time"] = $row["time"]? $row["time"] : 0;
        echo "id: " . $row["id"]. "<br> name: " . $row["name"]. "<br> parent_id: "
        . $row["parent_id"]. "<br> create Date: " . $row["date"].  "<br> time: " . $row["time"] ." min" .
        "<br> url: " . $row["url"]. "<br> done: " . $row["done"].
        "<hr>";
    }
} else {
  echo "0 results";
}

$conn->close();



?>