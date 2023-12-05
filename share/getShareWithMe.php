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
    echo Get($data);
}

function Get($data)
{
    $id = null;
    global $conn;
    $categories = array();
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $query = "SELECT share.id, category.name ,(category.id) as categoryID , CONCAT(user.first_name,' ',user.last_name)as fullName from user 
            INNER JOIN share ON share.user_id = user.id
			INNER JOIN visitor ON visitor.share_id = share.id
            INNER JOIN category ON category.id = share.category_id
            WHERE visitor.user_id = $user_id";

        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = array(
                    'id' => $row['id'],
                    'categoryID' => $row['categoryID'],
                    'fullName' => $row['fullName'],
                    'name' => $row['name']
                );
            }
        }
        return json_encode($categories);
    }

}
