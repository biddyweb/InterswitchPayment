<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/8/14
 * Time: 3:41 PM
 */
if (!session_id()) session_start();
$productId = 4220;
$itemId = 101;
$currency = 566;
$redirectUrl = "http://localhost:63342/schools/paymentfunc.php";
$txnRef = uniqid();
$macKey = "199F6031F20C63C18E2DC6F9CBA7689137661A05ADD4114ED10F5AFB64BE625B6A9993A634F590B64887EEB93FCFECB513EF9DE1C0B53FA33D287221D75643AB";

function setParams($total)
{

    global $txnRef, $productId, $itemId, $redirectUrl, $macKey;
    $_SESSION['total'] = $total . "00";
    $_SESSION["txnRef"] = $txnRef;
    $_SESSION['productId'] = $productId;
    $_SESSION['hash'] = hash('sha512', $txnRef . $productId . $itemId . $_SESSION['total'] . $redirectUrl . $macKey);


}

