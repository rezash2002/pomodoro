<?php

include '../ConnectToDB.php';

$id = 3;
$end_time = date("Y-m-d H:i:s");

$sql = "UPDATE pomodoro_time SET end_time = '$end_time' , time_spent = 1+ (SELECT time_spent WHERE id = $id) WHERE id = $id";

if ($conn->query($sql) === TRUE) {
  echo "Record Update successfully";
} else {
  echo "Error Update record: " . $conn->error;
}

$conn->close();



?>