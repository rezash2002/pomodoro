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
    echo getSetting($data);
}

function getSetting($data)
{
    $id = null;
    global $conn;

    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $config = 0;
        if (isset($data['id'])) {
            $id = $data['id'];
        }

        $settings = null;

        if ($id == null) {
            $query = "SELECT * from setting where user_id = $user_id order by id DESC";

            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                $settings[] = array(
                    'config' => json_decode($row["config"]),
                    'id' => $row['id']
                );
            }
        } else {
            $query = "SELECT * from setting where id = $id order by id DESC";
            $result = $conn->query($query);


            if ($row = $result->fetch_assoc()) {
                $settings = array(
                    'config' => json_decode($row["config"]),
                    'id' => $row['id']
                );
            }
        }
        return json_encode($settings);
    }

}
