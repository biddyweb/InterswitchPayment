<?php
/**
 * Created by PhpStorm.
 * User: ttola
 * Date: 10/7/14
 * Time: 4:40 PM
 */

include 'res.php';

function login($u, $p)
{
    global $con;
    $sql = "SELECT userid, email FROM users WHERE email= ?
   AND password= ? LIMIT 1";
    if ($stmt = $con->prepare($sql)) {
        if ($stmt === false) {
            trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $con->errno . ' ' . $con->error, E_USER_ERROR);
        }
        /* Bind parameters
           s - string, b - blob, i - int, etc */
        $stmt->bind_param("ss", $u, $p);

        /* Execute it */
        $stmt->execute();

        /* Bind results */
        $stmt->bind_result($userId, $result);

        /* Fetch the value */
        $stmt->fetch();
        /* Close statement */
        $stmt->close();
    }
    /* Close connection */
    $con->close();
    $_SESSION['userId'] = $userId;
    return $result;

}

function register($username, $password, $name)
{
    global $con;
    if (mysqli_query($con, "INSERT INTO users (username, password, name) VALUES('$username', '$password', '$name')")) {
        die('Error: ' . mysqli_error($con));
    }
    return $username;
}

$emailErr = $passErr = $email = $password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["password"])) {
        $passErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $passErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (!empty($email) && !empty($password)) {

        $result = login($email, $password);
        if (empty($result)) {
            $err = "Username or Password incorrent";
        } else {
            if (!isset($_SESSION['email'])) {
                $_SESSION['email'] = $result;
            }
            header("Location: index.php");
        }
    }
}
?>
<html>

<head>
    <title>Taxman App</title>
    <!--
 <link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
     -->
    <link rel="stylesheet" type="text/css" href="/schools/resource/css/bootstrap.css">
    <link href="/schools/resource/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

<div class="container-fluid">
    <div class="col-sm-12 col-xs-12 ">
        <div class="row">
            <div class="col-sm-4 col-md-4 col-sm-offset-4  col-md-offset-4 whiteText">
                <!--  <div class="hidden-xs" style="height: 20px"></div>
                 <div class="visible-xs" style="height: 20px"></div> -->
                <div class="text-center">
                    <h1 class="bigLogoLead" style="color: #2F4F4F">School<sup style="color: #F0E68C">Pay</sup>
                    </h1>
                    <h5><?php $err ?></h5>

                </div>
            </div>
        </div>
        <div class="row zeroBorder">
            <div class="col-sm-4 col-md-4   col-sm-offset-4 col-md-offset-4">
                <!--  <div class="hidden-xs" style="height: 30px"></div>
                 <div class="visible-xs" style="height: 20px"></div> -->

                <div data-tabset data-justified="true">
                    <div data-tab data-tab-heading="Login">
                        <div class="row">
                            <div
                                class="col-sm-10 col-md-10 col-lg-8 col-sm-offset-1 col-md-offset-1 col-lg-offset-2 col-xs-12">
                                <div style="height: 15px"></div>
                                <form name="form" method="post"
                                      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                      role="form">
                                    <div class="form-group">
                                        <input name="email" class="form-control input-lg" value="<?php echo $email; ?>"
                                               type="email" placeholder="Email address">
                                        <span class="error">* <?php echo $emailErr; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input name="password" class="form-control input-lg" min="6"
                                               value="<?php $password; ?>" type="password"
                                               placeholder="Password">
                                        <span class="error">* <?php echo $passErr; ?></span>
                                    </div>

                                    <button class="btn  btn-info btn-block" type="submit" name="submit">Login</button>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!--                    <div data-tab data-heading="Individual">-->
                    <!--                        <div class="row">-->
                    <!--                            <div class="col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2 col-xs-12">-->
                    <!---->
                    <!--                                <form autocomplete="off" role="form">-->
                    <!--                                    <div class="panel-height"></div>-->
                    <!--                                    <div class="form-group">-->
                    <!--                                        <h6>Full Name</h6>-->
                    <!--                                        <input type="text" class="form-control input-lg "/>-->
                    <!--                                    </div>-->
                    <!--                                    <div class="form-group">-->
                    <!--                                        <h6>Email Address</h6>-->
                    <!--                                        <input type="email" class="form-control input-lg "/>-->
                    <!--                                    </div>-->
                    <!--                                    <div class="form-group ">-->
                    <!--                                        <h6>Password</h6>-->
                    <!--                                        <input placeholder="min 8 characters, and one uppercase"-->
                    <!--                                               class="form-control input-lg"/>-->
                    <!--                                    </div>-->
                    <!--                                    <div class="form-group ">-->
                    <!--                                        <button class="btn btn-info btn-block" ng-click="register(account, 1)"-->
                    <!--                                                type="button">Register-->
                    <!--                                        </button>-->
                    <!---->
                    <!--                                    </div>-->
                    <!---->
                    <!--                                </form>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include 'footer.html';
?>

