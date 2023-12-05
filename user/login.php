<?php
require_once '../ConnectToDB.php';
require_once '../ReturnMessage.php';
include_once '../CheckMethod.php';


checkMethod('POST');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    $data = $inputData;


    global $conn;
    $email = $data['email'];
    $pass = $data['pass'];

    $query = "SELECT * from user where email = '$email' and password = '$pass' and is_delete = 'false'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run->num_rows > 0) {

        $id = null;
        $fullName = null;

        if ($row = $query_run->fetch_assoc()) {
            $id = $row['id'];
            $fullName = $row['first_name'] . ' ' . $row['last_name'];
        }

        //get user token from token table
        $query = "SELECT token from token where user_id = $id";
        $query_run = mysqli_query($conn, $query);
        if ($query_run->num_rows > 0) {

            if ($row = $query_run->fetch_assoc()) {
                $token = $row['token'];
            }
            $jwt = $token;

            //set cookie option
            $arr_cookie_options = array(
                'expires' => time() + 60 * 60 * 24 * 30,
                'path' => '/',
                'domain' => 'localhost', // leading dot for compatibility or use subdomain
                'secure' => true,     // or false
                'httponly' => true,    // or false
                'samesite' => 'None' // None || Lax  || Strict
            );

            //set cookie
            setcookie('token', $jwt, $arr_cookie_options);
            ReturnMessage($fullName , "ورود موفقیت آمیز", true,200);
        }

    } else {
        // Wrong email & password
        ReturnMessage(null, "ایمیل یا کلمه عبور نادرست است", false,400);

    }
}

