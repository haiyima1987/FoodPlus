<?php
session_start();
require_once("component/dbhelper.php");
require_once('component/appvars.php');
require_once('component/connectvars.php');

if (isset($_GET['page'])) {
//    $pages = array("home", "flyer", "camping", "vote", "login", "signup", "logout");
    $pages = array("home", "flyer", "camping.v1", "vote", "login", "signup", "logout");
    if (in_array($_GET['page'], $pages)) {
        $_page = $_GET['page'];
    } else {
        $_page = "home";
    }
} else {
    $_page = "home";
}

if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['votes_left'] = $_COOKIE['votes_left'];
        $_SESSION['ticket'] = $_COOKIE['ticket'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>FoodPlus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="bower_components/components-font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/stylesheet.css">
    <script src="js/event.js"></script>
</head>

<body>
<nav>
    <div class="col-sm-12 col-md-10 col-md-offset-1">
        <ul class="leftNav col-xs-8">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?page=flyer">Who we are</a></li>
            <!--            <li><a href="index.php?page=camping">Reservation</a></li>-->
            <li><a href="index.php?page=camping.v1">Reservation</a></li>
            <li><a href="index.php?page=vote">Vote</a></li>
        </ul>
        <ul class="rightNav col-xs-4">
            <?php
            if (isset($_SESSION['user_id'])) {
//            $votes_left = isset($_SESSION['votes_left']) ? $_SESSION['votes_left'] : "";
                echo '<h4></h4>';
                echo '<li><a href="index.php?page=logout">Log Out</a></li>';
            } else {
                ?>
                <li><a href="index.php?page=login">Log In</a></li>
                <li><a href="index.php?page=signup">JOIN US</a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>

<?php
if (isset($_POST['ticketSubmit'])) {
    $dbc = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $account = $_SESSION['user_id'];
    $query_ticket = "UPDATE user SET ticket = 1 WHERE user_id = $account";
    $result = $dbc->selectUpdateData($query_ticket);

    if ($result) {
        $_SESSION['ticket'] = 1;
        setcookie('ticket', 1, time() + (60 * 60 * 24 * 30));
        ?>
        <div class="alert alert-success text-center paymentSuccess">
            <h4>You have bought your ticket</h4>
            <h4>Thank you so much for joining us!</h4>
        </div>
        <?php
    }
}

if (isset($_POST['subscribe'])) {
    $dbc = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $email = $_POST['emailSub'];
    $date = date('Y-m-d');

    if (!empty($email)) {
        $query_sub = "INSERT INTO subscription VALUES ('$email', '$date')";
        $result = $dbc->selectUpdateData($query_sub);
        if ($result) {
            ?>
            <div class="alert alert-success text-center subscribeInfo">
                <h4>Thanks for subscribing, your email is saved, you will receive our newsletters!</h4>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-danger text-center subscribeInfo">
                <h4>Error saving data</h4>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="alert alert-danger text-center subscribeInfo">
            <h4>Email is not filled yet</h4>
        </div>
        <?php
    }
}
?>

<!--<form method="post" action="index.php" class="form-inline">-->
<!--    <div class="form-group">-->
<!--        <input type="email" class="form-control" name="emailSub">-->
<!--    </div>-->
<!--    <button type="submit" name="subscribe" class="btn btn-default">Subscribe</button>-->
<!--</form>-->

<!--page container for all pages-->
<div id="pageContainer" class="bg container-fluid">
    <?php require("include/" . $_page . ".php") ?>
</div>

<!--error message box to show all invalid data evaluated by JavaScript-->
<div class="errorMsg alert alert-danger">
    <i class="fa fa-times" aria-hidden="true"></i>
    <h3></h3>
</div>

<!--buy ticket-->
<?php
if (!isset($_SESSION['user_id'])) {
    echo '<a href="index.php?page=signup" class="btnBuyTicket btn btn-success">Join to Buy Ticket</a>';
} else if ($_SESSION['ticket'] == 0) {
    echo '<button id="btnPayBoxShow" class="btnBuyTicket btn btn-success">Buy Ticket</button>';
}
?>

<!--container for ticket payment overview-->
<div class="payBoxContainer">
    <?php require("component/overview.php") ?>
</div>

<!--footer starts-->
<div class="footerAbove">
    <div class="about col-sm-4"><h4>About FoodPlus</h4>
        <p>Email: info@foodplus.com</p>
        <p>Phone: +31 (0)6 123 456 78</p>
        <p>Fax: +31 (0)6 876 543 21</p>
        <p>Location: Kerkstraat 105, Eindhoven</p>
    </div>
    <div class="followUs col-sm-4"><h4>Follow Us</h4>
        <a href="https://www.instagram.com/foodplusprop/" target="_blank">
            <div class="followLogo"><i class="fa fa-instagram" aria-hidden="true"></i></div>
        </a>
        <a href="https://twitter.com/foodplusprop" target="_blank">
            <div class="followLogo"><i class="fa fa-twitter" aria-hidden="true"></i></div>
        </a>
        <a href="https://www.facebook.com/foodPlusPROP/" target="_blank">
            <div class="followLogo"><i class="fa fa-facebook" aria-hidden="true"></i></div>
        </a>
    </div>
    <div class="news col-sm-4">
        <h4>Newsletter</h4>
        <form method="post" action="index.php" class="form-inline">
            <div class="form-group">
                <input type="email" class="form-control" name="emailSub">
            </div>
            <button type="submit" name="subscribe" class="btn btn-default">Subscribe</button>
        </form>
    </div>
</div>
<div class="footer col-xs-12">
    <div class="copyright col-sm-5 col-md-6">
        <p>© 2016 FoodPlus. All Rights Reserved.</p>
    </div>
    <div class="footerMenu col-sm-7 col-md-6">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?page=login">JOIN US!</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <li><a href="index.php?page=camping">Reservation</a></li>
        </ul>
    </div>
</div>

<div class="btnUp">
    <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
</div>

<div class="btnMenu">
    <i class="fa fa-bars" aria-hidden="true"></i>
</div>

<script src="js/main.js"></script>

</body>
</html>