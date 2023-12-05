<?php

require_once '../ConnectToDB.php';
require_once '../checkCookie.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: POST');

$requestMethod = $_SERVER["REQUEST_METHOD"];


if ($requestMethod == "POST") {

    echo SelectAll();

}


function SelectAll()
{


    global $conn;
    $m = checkCookie($conn);
    if ($m == 'access denied') {
        return $m;
    } else {
        $user_id = $m;
        $tasks = array();
        $query = "SELECT id, CONCAT(first_name,' ',last_name) as name , email from user where id != $user_id LIMIT 5";

        $query_run = mysqli_query($conn, $query);

        while ($row = $query_run->fetch_assoc()) {
            $data[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
            );
        }
        return json_encode($data);
    }
}

?>