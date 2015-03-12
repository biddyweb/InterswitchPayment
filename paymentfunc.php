<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/8/14
 * Time: 4:07 PM
 */


include 'res.php';
include 'paycredentials.php';

function verifyPayment($payProductId, $payTransRef, $payMac, $payHash, $payDue, $payref, $retRef, $redir)
{
    $con = new mysqli("localhost", "root", "plato007", "pay");
    $hash_string = $payProductId . $payTransRef . $payMac;
    $hash_json = hash('sha512', $hash_string);
    /*
    setup a curl get request to the validation url

     */
    $url = "https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json?productid=" . $payProductId . "&transactionreference=" . $payTransRef . "&amount=" . $payDue;


    $session = curl_init($url);
    curl_setopt($session, CURLOPT_HTTPHEADER, array("Hash:" . $hash_json, "UserAgent: Mozilla/4.0 compatible; MSIE 6.0; MS Web Services Client Protocol 4.0.30319.239"));
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($session);
    curl_close($session);

    if ($response) {
        $decodedJson = json_decode($response, true);

        if ($decodedJson['ResponseCode'] == "00") {
            var_dump($decodedJson);

            if ($decodedJson['Amount'] == $_SESSION['total']) {
//                    Insert validated parameteres into database
                $paySql = "UPDATE  payment SET payref = ?, retRef  = ?, hash = ?, paid = ?, MerchantReference = ?,PaymentReference = ?,RetrievalReferenceNumber = ?,LeadBankCbnCode = ?,LeadBankName = ?,TransactionDate = ?,ResponseCode = ?,ResponseDescription = ?, pending  = ? WHERE txnref = ? ";
                if ($stmt = $con->prepare($paySql)) {
                    if ($stmt === false) {
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $redir = false;
                    }
                    $pending = "false";

                    if (!$stmt->bind_param("sssdssssssssss", $payref, $retRef, $payHash, $decodedJson['Amount'], $decodedJson['MerchantReference'], $decodedJson['PaymentReference'], $decodedJson['RetrievalReferenceNumber'], $decodedJson['LeadBankCbnCode'], $decodedJson['LeadBankName'], $decodedJson['TransactionDate'], $decodedJson['ResponseCode'], $decodedJson['ResponseDescription'], $pending, $payTransRef)) {
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $redir = false;
                    }
                    if (!$stmt->execute()) {
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $redir = false;
                    }
                    $stmt->close();
                    $con->close();
                    if ($redir) {
                        header("Location: index.php");
                    }
                } else {
                    echo $con->error;
                }
            }
        } else {
            var_dump($decodedJson);

        }
    }
}


if ($_SERVER["REQUEST_METHOD"] = "POST") {
    if ($_SESSION["txnRef"] === $_POST['txnref']) {
        $connection = $con;
        verifyPayment($_SESSION['productId'], $_SESSION["txnRef"], $macKey, $_SESSION["hash"], $_SESSION["total"], $_POST["payRef"], $_POST['retRef'], true);
        /*compute hash of productid, transactionref and mac key to
        send with the get request to validate the payment.
        */

    }
}


