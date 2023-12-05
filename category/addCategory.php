<?php

require_once '../ConnectToDB.php';
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
    echo addCategory($data);
}

function addCategory($data)
{
    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $id = null;
        if (isset($data['id'])) {
            $id = $data['id'];
        }
        $name = $data['title'];
        $color = $data['color'];
        if ($id == null) {
            //add new category
            $query = "INSERT INTO category (user_id , name, color) VALUES ($user_id , '$name' , '$color')";

            if ($conn->query($query) === TRUE) {
                return $conn->insert_id;
            } else {
                return "Error: " . $query . "<br>" . $conn->error;
            }
        } else {
            //update the category
            $sql = "UPDATE category SET name = '$name' , color = '$color' WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                return "updated";
            } else {
                return "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }


}
