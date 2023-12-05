<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET');

    date_default_timezone_set("Asia/Tehran");
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "pomodoro";

    $conn = new mysqli($servername, $username, $password, $dbName);

    if ($conn->connect_error) {
        http_response_code(500);
    }

    mysqli_set_charset($conn, "utf8");


