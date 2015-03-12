<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/8/14
 * Time: 1:31 AM
 */
include 'res.php';
include 'paycredentials.php';
include 'header.html';

$paymentId = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_SESSION['hash']) && !empty($_SESSION['total'])) {
        $pendingSql = "INSERT INTO payment(userId, txnRef, hash, due, productId, itemId, currency, pending) VALUES(?,?,?,?,?,?,?,?) ";
        $pending = true;
        if ($stmt = $con->prepare($pendingSql)) {
            if (!$stmt->bind_param("issdddis", $_SESSION["userId"], $_SESSION["txnRef"], $_SESSION["hash"], $_SESSION['total'], $_SESSION["productId"], $itemId, $currency, $pending)) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            };

            if (!$stmt->execute())
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $paymentId = $stmt->insert_id;
            /* Close statement */
            $stmt->close();

        } else {
            echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        }

        $userpaymentSql = "INSERT INTO userpayment(userId, paymentId, class, term) VALUES(?,?,?,?) ";
        $pending = true;
        if ($stmt = $con->prepare($userpaymentSql)) {
            if (!$stmt->bind_param("iiss", $_SESSION["userId"], $paymentId, $_SESSION["class"], $_SESSION['term'])) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            };

            if (!$stmt->execute())
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;

            $stmt->close();

        } else {
            echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        }
        $con->close();
    }

}
?>
<div class="col-xs-12 well well-sm col-md-offset-1 col-sm-4 col-md-4">
    <h3>Payment Summary</h3>

    <table class="table table-responsive table-striped table-bordered">
        <tbody>
        <tr>
            <td><strong>Total</strong></td>
            <td>&#8358;<?php echo $_SESSION['total']; ?></td>
        </tr>
        </tbody>
    </table>

    <form name="form1" action="https://stageserv.interswitchng.com/test_paydirect/pay"
          method="post">
        <input name="product_id" type="hidden" value="<?php echo $productId; ?>"/>
        <input name="pay_item_id" type="hidden" value="<?php echo $itemId; ?>"/>
        <input name="amount" type="hidden" value="<?php echo $_SESSION['total']; ?>"/>
        <input name="currency" type="hidden" value="<?php echo $currency; ?>"/>
        <input name="site_redirect_url" type="hidden"
               value="<?php echo $redirectUrl; ?>"/>
        <input name="txn_ref" type="hidden"
               value="<?php echo $_SESSION["txnRef"]; ?>"/>

        <input name="hash" type="hidden" value="<?php echo $_SESSION['hash']; ?>"/>
        <button class="btn btn-success" type="submit">Pay With Interswitch</button>
    </form>
</div>
<?php
include 'footer.html';
?>

