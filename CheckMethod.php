<?php

function checkMethod($method)
{
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: '.$method);
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');
    header('Access-Control-Allow-Credentials: true');

}
