<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/7/14
 * Time: 8:17 PM
 *
 */
include 'res.php';
include 'schoolfunc.php';
include 'header.html';
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="tabs-left">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#status" role="tab" data-toggle="tab">Status</a></li>
                    <li><a href="#payment" role="tab" data-toggle="tab">Payment History</a></li>
                    <li><a href="#contact" role="tab" data-toggle="tab">Contact</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="status">
                        <h3> <?php echo $fullname ?></h3>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <h3>Pay fees</h3>

                                <form method="post" class="panel-body"
                                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                      role="form">
                                    <div class="form">
                                        <div class="form-group">
                                            <h6>Select Current Class</h6>
                                            <select name="selectedclass" class="form-control">
                                                <?php foreach ($classlist as $row): ?>
                                                    <option
                                                        value="<?php echo $row['classcode'] ?>" <?php if ($selectedclass == $row['classcode']): ?> selected="selected"<?php endif; ?> >
                                                        <?php echo $row['classname'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <h6>Select Term</h6>
                                            <select name="selectedterm" class="form-control">
                                                <?php foreach ($termlist as $row): ?>
                                                    <option
                                                        value="<?php echo $row['termcode'] ?>"<?php if ($selectedterm == $row['termcode']): ?> selected="selected"<?php endif; ?>>
                                                        <?php echo $row['term'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Calculate Fees</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php if ($totalfee > 0): ?>
                                <div class="col-xs-12 well well-sm col-md-offset-1 col-sm-4 col-md-4">
                                    <h3>Fee Details</h3>

                                    <table class="table table-responsive table-striped table-bordered">
                                        <tbody>
                                        <tr>
                                            <td><strong>Fees</strong></td>
                                            <td>&#8358;<?php echo $schoolfee; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Others</strong></td>
                                            <td>&#8358;<?php echo $otherfee; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td>&#8358;<?php echo $totalfee; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <form name="form1" action="prepayment.php"
                                          method="post">
                                        <button class="btn btn-success" type="submit">Pay Now</button>
                                    </form>


                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="payment">
                        <div class="row">
                            <div class="col-md-8">
                                <h3>Payments
                                </h3>
                                <hr>
                                <table class="table table-responsive table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Class</th>
                                        <th>Term</th>
                                        <th>Due</th>
                                        <th>Paid</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($paymentList as $row): ?>
                                        <tr>
                                            <td><?php echo $row['payclassname']; ?></td>
                                            <td><?php echo $row['termpay']; ?></td>
                                            <td><?php echo $row['paidamount'] / 100; ?></td>
                                            <td><?php echo $row['duepay'] / 100; ?></td>
                                            <td><?php echo date("d/m/Y", strtotime($row['paydate'])); ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contact">...</div>

                </div>
            </div>
        </div>
    </div>
</div>





<?php
include 'footer.html';
?>

