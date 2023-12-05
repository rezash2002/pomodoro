<?php


require_once '../ConnectToDB.php';
require_once '../checkCookie.php';

    global $conn;
    $m = checkCookie($conn);
    if ($m == "access denied") {
        http_response_code(401);
    } else {

        $arr_cookie_options = array(
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => 'localhost', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            'httponly' => true,    // or false
            'samesite' => 'None' // None || Lax  || Strict
        );
        setcookie('token', 'expired', $arr_cookie_options);
        http_response_code(200);

}