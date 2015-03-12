<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/8/14
 * Time: 6:46 PM
 */

include "paymentfunc.php";

$adminSql = "SELECT payment.productId, payment.txnref, payment.hash, payment.payref, payment.retRef, payment.paid, payment.due, payment.date, users.username, users.email, classlist.classname, terms.term
FROM payment
INNER JOIN users ON payment.userId = users.userId
INNER JOIN userpayment ON payment.paymentId = userpayment.paymentId
INNER JOIN terms ON userpayment.term = terms.termcode
INNER JOIN classlist ON userpayment.class = classlist.classcode
GROUP BY payment.txnref ORDER BY
payment.date DESC
   ";

$paylist = array();


if ($stmt = $con->prepare($adminSql)) {
    $stmt->execute();

    $stmt->bind_result($productIdString, $transRef, $hashString, $payref, $retref, $amount, $due, $date, $username, $email, $classname, $term);

    while ($stmt->fetch()) {
        array_push($paylist, array("productId" => $productIdString, "txnref" => $transRef, "hash" => $hashString, "payref" => $payref, "retRef" => $retref, "amount" => $amount, "term" => $term, "classname" => $classname, "email" => $email, "username" => $username, "due" => $due, "date" => $date));
    }

    $stmt->free_result();
    $stmt->close();
    $con->close();
} else {
    echo $con->error;
}
if (isset($_GET['reconcile'])) {
    foreach ($paylist as $row) {
        if ($row['due'] > $row['amount'])
            verifyPayment($row['productId'], $row['txnref'], $macKey, $row['hash'], $row['due'], $row['payref'], $row['retRef'], $con, false);
    }
    header("Location: admin.php");
}
