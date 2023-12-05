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
        if (isset($data['id'])) {
            $id = $data['id'];
        }

        if ($id == null) {
            $query = "SELECT share.id as shareID, category.* from share RIGHT JOIN
            category ON category.id = share.category_id
            where share.user_id = $user_id order by shareID DESC";

            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = array(
                        'id' => $row['shareID'],
                        'color' => $row['color'],
                        'name' => $row['name']
                    );
                }
            }
        } else {
            $query = "delete from visitor where share_id = $id";
            if ($conn->query($query) === true) {
                $query = "delete from share where id = $id";
                if ($conn->query($query) === true) {
                    return 'deleted';
                } else {
                    return "Error: " . $query . "<br>" . $conn->error;
                }
            } else {
                return "Error: " . $query . "<br>" . $conn->error;
            }


        }
        return json_encode($categories);
    }

}
