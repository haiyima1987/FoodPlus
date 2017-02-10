<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/30/2016
 * Time: 1:25 PM
 */

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
    $competition = isset($_POST['competition']) ? $_POST['competition'] : 0;
    $ticket = isset($_POST['ticket']) ? $_POST['ticket'] : 0;
    $form_incomplete = false;
//    echo $competition;

    if (!empty($last_name) && !empty($first_name) && !empty($user_name) && !empty($password1) && !empty($password2) && !empty($email) &&
        !empty($age) && !empty($gender) && !empty($mobile) && !empty($address) && !empty($nationality) && ($password1 == $password2)
    ) {
        $query_check_unique = "SELECT COUNT(*) FROM user WHERE username = '$user_name'";
//            echo $query_check_unique;
        $dbc = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $row = $dbc->selectUpdateData($query_check_unique);

        if ($row->fetchColumn() == 0) {
            $account_num = 0;
            do {
                $account_num = generateRandomNumber();
                $query_info = "INSERT INTO user (user_id, username, password, first_name, last_name, gender, age, mobile, email, nationality, address, votes_left)" .
                    "VALUES ('$account_num', '$user_name', sha1('$password1'), '$first_name', '$last_name', '$gender', $age, $mobile, '$email', '$nationality', '$address', 5)";
//                echo $query_info;
                $result = $dbc->insertDeleteData($query_info);
            } while (!$result);

            $result_competitor = 0;
            if ($competition == 1) {
                $query_competitor = "INSERT INTO competitor(user_id, first_name) VALUES('$account_num', '$first_name')";
                $result_competitor = $dbc->insertDeleteData($query_competitor);
            }

//            if ($result) {
//                $query_account = "INSERT INTO user_account (username, password, account_number) VALUES('$user_name', sha('$password1'), '$account_num')";
//                $dbc->insertDeleteData($query_account);

            // after registering, automatically sign in
            $_SESSION['user_id'] = $account_num;
            $_SESSION['username'] = $user_name;
            $_SESSION['votes_left'] = 5;
            $_SESSION['ticket'] = 0;
            setcookie('user_id', $account_num, time() + (60 * 60 * 24 * 30));
            setcookie('username', $user_name, time() + (60 * 60 * 24 * 30));
            setcookie('votes_left', 5, time() + (60 * 60 * 24 * 30));
            setcookie('ticket', 0, time() + (60 * 60 * 24 * 30));
            ?>

            <div class="alert alert-success text-center confirm">
                <h2>You have registered as a member of FoodPlus</h2><br>
                <h2><?php echo $competition ? 'As a foodie, you can also join competitions' : ''; ?></h2>
                Your account is: <?php echo $account_num ?><br>
                Your username is: <?php echo $user_name ?><br>
                Your password is: <?php echo $password1 ?><br>
                Thank you so much for joining us!<br>
                Now you are logged in and you can<br><br>
                <a href="index.php?page=camping" class="btn btn-lg btn-success">Reserve Spots</a>
            </div>
            <?php
//            } else {
//                $form_incomplete = true;
//                echo '<div class="alert alert-danger col-md-offset-3 col-md-6">Error saving data.</div>';
//            }
        } else {
            $form_incomplete = true;
            echo '<div class="alert alert-danger col-md-offset-3 col-md-6">An account with the same name exists, please use another name.</div>';
        }
    } else {
        $form_incomplete = true;
        echo '<div class="alert alert-danger col-md-offset-3 col-md-6">Please enter the same password twice.</div>';
    }
} else {
    $form_incomplete = true;
}

if ($form_incomplete) {
    ?>
    <div class="signUpForm col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
        <!--        <form action="-->
        <?php //echo $_SERVER['PHP_SELF'] ?><!--" method="post" class="form-horizontal">-->
        <form action="index.php?page=signup" method="post" class="form-horizontal">
            <fieldset>
                <legend>Personal Information:</legend>
                <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-user-circle fa-fw"
                                                           aria-hidden="true"></i></span>
                    <input type="text" name="firstName" class="form-control" placeholder="First Name" required
                           value="<?php if (!empty($first_name)) echo $first_name; ?>">
                </div>
                <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-user-circle-o fa-fw"
                                                           aria-hidden="true"></i></span>
                    <input type="text" name="lastName" class="form-control" placeholder="Last Name" required
                           value="<?php if (!empty($last_name)) echo $last_name; ?>">
                </div>
                <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake fa-fw"
                                                           aria-hidden="true"></i></span>
                    <input type="text" name="age" class="form-control" placeholder="Age" required
                           value="<?php if (!empty($age)) echo $age; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <label class="input-group-addon">
                        <i class="fa fa-mars fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;
                        <input type="radio" name="gender" value="M" required>
                    </label>
                    <label class="input-group-addon">
                        <i class="fa fa-venus fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;
                        <input type="radio" name="gender" value="F">
                    </label>
                    <label class="input-group-addon">
                        <i class="fa fa-genderless fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;
                        <input type="radio" name="gender" value="O">
                    </label>
                </div>
                <div class="signUpType input-group input-group-lg">
                    <label class="input-group-addon">
                        <span>Join Our Competitions?</span>
                        <input type="checkbox" name="competition"
                               value="1" <?php if (isset($_GET['foodie'])) echo 'checked'; ?>>
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
                           value="<?php if (!empty($username)) echo $username; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></span>
                    <input type="password" name="password1" class="form-control" placeholder="Enter Password"
                           required
                           value="<?php if (!empty($password1)) echo $password1; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></span>
                    <input type="password" name="password2" class="form-control" placeholder="Reenter Password"
                           required
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
                <button type="submit" class="btn btn-success btn-lg center-block" name="submit">Join FoodPlus</button>
            </div>
        </form>
    </div>
    <?php
}
?>