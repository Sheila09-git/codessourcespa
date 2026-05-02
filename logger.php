<?php
date_default_timezone_set('Europe/Paris');
function writeLog($action, $user)
{
    $date = date("Y-m-d H:i:s");

    if (empty($user)) {
        $user = "inconnu";
    }

    $message = "$date|$user|$action" . PHP_EOL;

    file_put_contents(__DIR__ . "/logs.txt", $message, FILE_APPEND);
}
