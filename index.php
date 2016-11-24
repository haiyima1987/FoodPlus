<?php
session_start();

if (isset($_GET['page'])) {
    $pages = array("home", "flyer", "contact", "camping", "login");
    if (in_array($_GET['page'], $pages)) {
        $_page = $_GET['page'];
    } else {
        $_page = "home";
    }
} else {
    $_page = "home";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>FoodPlus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylesheet.css">
</head>

<body>
<nav>
    <ul class="leftNav">
        <li><a href="index.php">Home</a></li>
        <li><a href="index.php?page=flyer">Who we are</a></li>
        <li><a href="index.php?page=camping">Reservation</a></li>
    </ul>
    <ul class="rightNav">
        <li><a href="#">Log In</a></li>
        <li><a href="#">Sign Up</a></li>
    </ul>
</nav>

<div id="pageContainer" class="bg container-fluid">
    <?php require("include/" . $_page . ".php") ?>
</div>


<div class="footerAbove">
    <div class="about col-md-4"><h4>About FoodPlus</h4>
        <p></p></div>
    <div class="followUs col-md-4"><h4>Follow Us</h4>
        <div class="followLogo"><i class="fa fa-instagram" aria-hidden="true"></i></div>
        <div class="followLogo"><i class="fa fa-twitter" aria-hidden="true"></i></div>
        <div class="followLogo"><i class="fa fa-facebook" aria-hidden="true"></i></div>
    </div>
    <div class="news col-md-4">
        <h4>Newsletter</h4>
        <div class="form-group col-md-10 col-md-offset-1">
            <input type="email" class="form-control" id="email">
            <button type="submit" class="btnSection pull-right">Sign UP</button>
        </div>
    </div>
</div>
<div class="footer">
    <div class="copyright col-md-6">
        <p>© 2016 FoodPlus. All Rights Reserved.</p>
    </div>
    <div class="footerMenu col-md-6">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?page=login">JOIN US!</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <li><a href="index.php?page=camping">Reservation</a></li>
        </ul>
    </div>
</div>

<script src="js/main.js"></script>

</body>
</html>