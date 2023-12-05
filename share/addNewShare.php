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
    echo Add($data);
}

function Add($data)
{

    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $category_id = $data['category_id'];
        $users_id = $data['users_id'];
        $mess = null;

        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $date = str_replace($persian, $english, jdate("Y-m-d H:i:s"));


        $query = "INSERT INTO share( user_id, category_id, date)
            VALUES ( $user_id, $category_id ,'$date')";
        if ($conn->query($query) === TRUE) {
            $id = $conn->insert_id;
        }
        foreach ($users_id as $uID) {
            $query = "INSERT INTO visitor(user_id, share_id)
            VALUES ( $uID, $id )";
            if ($conn->query($query) === TRUE) {
                $mess[] = $conn->insert_id;
            } else {
                $mess[] = "Error: " . $query . "<br>" . $conn->error;
            }
        }

        return json_encode($mess);
    }

}
