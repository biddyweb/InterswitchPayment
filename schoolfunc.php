<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/8/14
 * Time: 3:48 PM
 */
include 'paycredentials.php';
$userSql = "SELECT userId, username, email, school FROM users WHERE email= ?
   ";
if ($stmt = $con->prepare($userSql)) {
    if ($stmt === false) {
        trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $con->errno . ' ' . $con->error, E_USER_ERROR);
    }
    /* Bind parameters
       s - string, b - blob, i - int, etc */
    $stmt->bind_param("s", $_SESSION['email']);

    /* Execute it */
    $stmt->execute();

    /* Bind results */
    $stmt->bind_result($userId, $fullname, $emailAddress, $school);

    /* Fetch the value */
    $stmt->fetch();
    /* Close statement */
    $stmt->close();
}


/* Close connection */
$paymentList = array();
$paymentSql = "SELECT classlist.classname, terms.term, payment.paid, payment.due, payment.date FROM payment
INNER JOIN userpayment
 ON userpayment.paymentId = payment.paymentId
 INNER JOIN terms
 ON terms.termcode = userpayment.term
 INNER JOIN classlist
 ON classlist.classcode = userpayment.class
  WHERE payment.userId = ?
  GROUP BY payment.txnref
   ORDER BY  payment.date DESC ";
if ($stmt = $con->prepare($paymentSql)) {
    if ($stmt === false) {
        trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $con->errno . ' ' . $con->error, E_USER_ERROR);
    }


    $stmt->bind_param("i", $_SESSION['userId']);
    /* Execute it */
    $stmt->execute();
    /* Bind results */
    $stmt->bind_result($class, $term, $paid, $due, $date);
    while ($stmt->fetch()) {
        array_push($paymentList, array("payclassname" => $class, "termpay" => $term, "paidamount" => $paid, "duepay" => $due, "paydate" => $date));
    }
    /* free results */
    $stmt->free_result();
    /* Close statement */
    $stmt->close();
} else {
    echo $con->error;
}

$termlist = array();
$termSql = "SELECT term, termcode FROM terms ORDER BY termcode ASC ";
if ($stmt = $con->prepare($termSql)) {
    if ($stmt === false) {
        trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $con->errno . ' ' . $con->error, E_USER_ERROR);
    }

    $stmt->execute();
    /* Bind results */
    $stmt->bind_result($term, $termcode);
    while ($stmt->fetch()) {
        array_push($termlist, array("term" => $term, "termcode" => $termcode));
    }
    /* free results */
    $stmt->free_result();
    /* Close statement */
    $stmt->close();
}

$classlist = array();
$classSql = "SELECT classname, classcode FROM classlist ORDER BY classcode ASC ";
if ($stmt = $con->prepare($classSql)) {

    if ($stmt === false) {
        trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $con->errno . ' ' . $con->error, E_USER_ERROR);
    }
    /* Execute it */
    $stmt->execute();
    /* Bind results */
    $stmt->bind_result($classname, $classcode);

    while ($stmt->fetch()) {

        array_push($classlist, array("classname" => $classname, "classcode" => $classcode));
    }

    /* free results */
    $stmt->free_result();
    /* Close statement */
    $stmt->close();
}

$totalfee = $selectedclass = $selectedterm = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["selectedterm"])) {
        $termErr = "You need to select a term";
    } else {
        $selectedterm = test_input($_POST["selectedterm"]);
    }

    if (empty($_POST["selectedclass"])) {
        $classErr = "Select your current class";
    } else {
        $selectedclass = test_input($_POST["selectedclass"]);
    }

    if (!empty($selectedterm) && !empty($selectedclass)) {
        $_SESSION['class'] = $selectedclass;
        $_SESSION['term'] = $selectedterm;

        $feeSql = "SELECT fee, others FROM fee WHERE classcode = ? AND  termcode  = ?";
        if ($stmt = $con->prepare($feeSql)) {
            if ($stmt === false) {
                trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $con->errno . ' ' . $con->error, E_USER_ERROR);
            }


            $stmt->bind_param("ii", $selectedclass, $selectedterm);
            /* Execute it */
            $stmt->execute();
            /* Bind results */
            $stmt->bind_result($schoolfee, $otherfee);
            $stmt->fetch();
            /* free results */

            $stmt->close();
            $totalfee = $schoolfee + $otherfee;

            setParams($totalfee);


        }

    }
}
$con->close();