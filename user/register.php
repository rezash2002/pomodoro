<?php

require_once '../CheckMethod.php';
require_once '../ReturnMessage.php';
require_once '../ConnectToDB.php';
require_once '../jdf.php';
use Firebase\JWT\JWT;
require '../vendor/autoload.php';

checkMethod('POST');


$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    $data = $inputData;
    global $conn;

    $firstName = $data['fName'];
    $lastName = $data['lName'];
    $fullName = $data['fName'] . ' ' . $data['lName'];
    $email = $data['email'];
    $pass = $data['pass'];
    $id = null;

    //check email
    $query = "SELECT * from user where email = '$email' AND is_delete = 'false'";
    $query_run = mysqli_query($conn, $query);
    if ($query_run->num_rows > 0) {
        ReturnMessage(null , "Email is already use", false,400);
    } else {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $date = str_replace($persian, $english, jdate("Y-m-d H:i:s"));

        //*--start create token for user --*
        $payload = [
            'id' => $id,
            'email' => $email,
            'fullName' => $fullName,
        ];
        $key = 'my-256-bit-jwt-secret';
        $jwt = JWT::encode($payload, $key, 'HS256');
        $arr_cookie_options = array(
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'domain' => 'localhost', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            'httponly' => true,    // or false
            'samesite' => 'None' // None || Lax  || Strict
        );
        //*--end create token for user --*

        //*-- start insert default setting in var
        $setting = array(
            'stageSeconds' =>
                array(
                    'longBreak' => 20,
                    'shortBreak' => 5,
                    'pomodoro' => 25,
                ),

            'longBreakInterval' => 120,
            'autoStartBreakEnabled' => false,
            'alarmSound' =>
                array(
                    'break' => 'bell',
                    'pomodoro' => 'digital',
                ),

            'title' => 'پیشفرض'

        );
        $setting = json_encode($setting, JSON_UNESCAPED_UNICODE);
        //*-- end insert default setting in var
        try{
            $conn->begin_transaction();
            $conn->query(
                "INSERT INTO user (first_name , last_name, email, password, date)
                        VALUES ('$firstName' , '$lastName' , '$email' , '$pass', '$date')"
            );
            $id = $conn->insert_id;

            $conn->query("INSERT INTO token (user_id , token) VALUES ($id , '$jwt')");

            $conn->query("INSERT INTO setting (user_id , config) VALUES ($id , '$setting')");
            $setting_id = $conn->insert_id;

            $conn->query("UPDATE user set setting_id = $setting_id where id = $id");
            $conn->query("INSERT INTO task (name, user_id, parent_id, date)
                                VALUES ('خانه',$id , null , '$date')");

            $conn->commit();
        setcookie('token', $jwt, $arr_cookie_options);
        ReturnMessage(null , "ثبت نام با موفقیت انجام شد", true,201);

        }
        catch (Exception $e){
            $conn->rollback();
            ReturnMessage(null ,  $e->getMessage()."خطای سرور: ", false,500);
        }
    }
}


