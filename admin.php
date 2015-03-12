<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/8/14
 * Time: 7:36 PM
 */
include 'adminfunc.php';
include 'header.html';
?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 well well-sm  col-sm-12 col-md-12">
                <h3>Fee Details
                    <a class="btn btn-success pull-right" href='admin.php?reconcile=true'>Reconcile Payments</a>
                </h3>
                <hr>
                <table class="table table-responsive table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Class Fee</th>
                        <th>Total Paid</th>
                        <th>Payment Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($paylist as $row): ?>
                        <tr>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['classname']; ?></td>
                            <td><?php echo $row['term']; ?></td>
                            <td><?php echo $row['due'] / 100; ?></td>
                            <td><?php echo $row['amount'] / 100; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($row['date'])); ?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
include 'footer.html';
?>