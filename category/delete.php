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
    echo delete($data);
}

function delete($data)
{
    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $id = $data['id'];

        $query = "DELETE FROM category_items where category_id = $id";
        $conn->query($query);
        $query = "DELETE FROM category where id = $id";

        if ($conn->query($query) === TRUE) {
            return "deleted";
        } else {
            return "Error: " . $query . "<br>" . $conn->error;
        }
    }


}
