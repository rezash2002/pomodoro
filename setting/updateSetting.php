<?php

include '../setting.php';


$config = '{"name":"number1" ,"time":12}';

$sql = "UPDATE setting SET config = '$config' WHERE id = 4";

if ($conn->query($sql) === TRUE) {
  
    echo "updated successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();



?>