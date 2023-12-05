<?php

function checkCookie($conn)
{

    if (isset($_COOKIE['token'])) {
        $cookie = $_COOKIE['token'];
        $query = "SELECT * FROM token WHERE token = '$cookie'";
        $query_run = mysqli_query($conn, $query);
        if ($query_run->num_rows > 0) {
            if ($row = $query_run->fetch_assoc()) {
                return $row['user_id'];
            }
        }else{
            ReturnMessage(null , "خطا در احراز هویت" ,false, 403);
        }
    }else{
        ReturnMessage(null , "خطا در احراز هویت" ,false, 403);
    }

}

