<?php


$showDateMode = "MONTHNAME";
$start_date = "2023-01-14 00:00:00";
$end_date = date("Y-m-d H:i:s");


//select times of task from user joined

// SELECT SUM(p.time_spent) as time , MONTHNAME(p.end_time) as month FROM pomodoro_time as p 
// WHERE p.task_id = 4 AND p.end_time BETWEEN 
// (SELECT user.date from task
//  INNER JOIN user on task.user_id = user.id
//  WHERE task.id = p.task_id
// )
//  AND NOW()
// GROUP BY month ;

$sql = "SELECT $showDateMode(p.end_time) as date, SUM(p.time_spent) as time FROM pomodoro_time as p 
WHERE p.task_id = (SELECT task.id FROM task WHERE task.url = '$url') AND p.end_time BETWEEN '$start_date' AND '$end_date'
GROUP BY date ;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "date: " . $row["date"]. "<br> time: " . $row["time"]."<hr>";
  }
} else {
  echo "0 results";
}

$conn->close();





?>

