<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/7/14
 * Time: 8:50 PM
 */
if (!session_id()) session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$con = new mysqli("localhost", "root", "plato007", "pay");

//
//function createPasswordHash($strPlainText) {
//    if (CRYPT_SHA512 != 1) {
//        throw new Exception('Hashing mechanism not supported.');
//    }
//    return crypt($strPlainText, '$6$rounds=4567$abcdefghijklmnop$');
//}

if (mysqli_connect_errno()) {
    echo "Connection Failed: " . mysqli_connect_errno();
    exit();
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

