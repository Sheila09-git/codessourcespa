<?php

function writeLog($action, $user)
{

    $date = date("Y-m-d H:i:s");

    $message = "[$date] $user : $action" . PHP_EOL;

    file_put_contents("logs.txt", $message, FILE_APPEND);
}
