<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/30/2016
 * Time: 1:25 PM
 */

if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    }
}

require_once('component/appvars.php');
require_once('component/connectvars.php');
require_once('component/functions.php');
require_once('component/dbhelper.php');

if (isset($_POST["submit"])) {
    $last_name = $_POST['lastName'];
    $first_name = $_POST['firstName'];
    $user_name = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $nationality = $_POST['nationality'];
    $form_incomplete = false;

    if (!empty($last_name) && !empty($first_name) && !empty($user_name) && !empty($password1) && !empty($password2) && !empty($email) &&
        !empty($age) && !empty($gender) && !empty($mobile) && !empty($address) && !empty($nationality)
    ) {
        if ($password1 == $password2) {
            $query_check_unique = "SELECT COUNT(*) FROM user_account WHERE username = '$user_name'";
            echo $query_check_unique;
            $dbc = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $row = $dbc->selectUpdateData($query_check_unique);

            if ($row->fetchColumn() == 0) {
                $account_num = 0;
                do {
                    $account_num = generateRandomString();
                    $query_info = "INSERT INTO register_account (ID_Number, First_Name, Last_Name, Gender, Age, Mobile, Email, Nationality, Address)" .
                        "VALUES ('$account_num', '$first_name', '$last_name', '$gender', $age, $mobile, '$email', '$nationality', '$address')";
                    echo $query_info;
                    $result = $dbc->insertDeleteData($query_info);
                } while (!$result);
                $query_account = "INSERT INTO user_account (username, password, account_number) VALUES('$user_name', sha('$password1'), '$account_num')";
                $dbc->insertDeleteData($query_account);

                // after registering, automatically sign in
                $_SESSION['user_id'] = $account_num;
                $_SESSION['username'] = $user_name;
                setcookie('user_id', $account_num, time() + (60 * 60 * 24 * 30));
                setcookie('username', $user_name, time() + (60 * 60 * 24 * 30));
                ?>

                <div class="alert alert-success confirm">
                    <div class="col-sm-6 col-sm-offset-3">
                        You have registered as a member of FoodPlus<br>
                        Your username is: <?php echo $user_name ?><br>
                        Your password is: <?php echo $password1 ?><br>
                        Thank you so much for joining us!<br>
                        Now you are logged in and you can<br><br>
                        <a href="index.php?page=ticket" class="btn btn-lg btn-success center-block">Buy Tickets</a><br>
                        <a href="index.php?page=camping" class="btn btn-lg btn-success center-block">Reserve Spots</a>
                    </div>
                </div>

                <?php
            } else {
                $form_incomplete = true;
            }
        } else {
            $form_incomplete = true;
            echo '<div class="alert alert-danger col-md-offset-3 col-md-6">An account with the same name exists, please use another name.</div>';
        }
    } else {
        $form_incomplete = true;
        echo '<div class="alert alert-danger col-md-offset-3 col-md-6">Last Name, username, password, and email address cannot be empty.</div>';
    }
} else {
    $form_incomplete = true;
}

if ($form_incomplete) {
    ?>
    <div class="signUpForm col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
        <!--        <form action="--><?php //echo $_SERVER['PHP_SELF'] ?><!--" method="post" class="form-horizontal">-->
        <form action="index.php?page=signup" method="post" class="form-horizontal">
            <fieldset>
                <legend>Personal Information:</legend>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-user-circle fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="firstName" class="form-control" placeholder="First Name" required
                           value="<?php if (!empty($first_name)) echo $first_name; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-user-circle-o fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="lastName" class="form-control" placeholder="Last Name" required
                           value="<?php if (!empty($last_name)) echo $last_name; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-birthday-cake fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="age" class="form-control" placeholder="Age" required
                           value="<?php if (!empty($age)) echo $age; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <label class="input-group-addon">
                        <i class="fa fa-male fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;
                        <input type="radio" name="gender" value="M" required>
                    </label>
                    <label class="input-group-addon">
                        <i class="fa fa-female fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;
                        <input type="radio" name="gender" value="F">
                    </label>
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-home fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="address" class="form-control" placeholder="Address" required
                           value="<?php if (!empty($address)) echo $address; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-flag fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="nationality" class="form-control" placeholder="Nationality" required
                           value="<?php if (!empty($nationality)) echo $nationality; ?>">
                </div>
            </fieldset>
            <br>
            <fieldset>
                <legend>Contact Information:</legend>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-user fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required
                           value="<?php if (!empty($user_name)) echo $user_name; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></span>
                    <input type="password" name="password1" class="form-control" placeholder="Enter Password" required
                           value="<?php if (!empty($password1)) echo $password1; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></span>
                    <input type="password" name="password2" class="form-control" placeholder="Reenter Password" required
                           value="<?php if (!empty($password2)) echo $password2; ?>">

                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-envelope fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="email" class="form-control" placeholder="Email" required
                           value="<?php if (!empty($email)) echo $email; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-mobile fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile" required
                           value="<?php if (!empty($mobile)) echo $mobile; ?>">
                </div>
            </fieldset>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg center-block" name="submit">Sign Up</button>
            </div>
        </form>
    </div>
    <?php
}
?>