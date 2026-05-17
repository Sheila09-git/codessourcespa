<?php
session_start();
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->exec("DELETE FROM reservation");
    http_response_code(200);
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
