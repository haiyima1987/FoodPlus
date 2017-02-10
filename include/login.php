<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 12/1/2016
 * Time: 6:02 PM
 */
require_once('component/appvars.php');
require_once('component/connectvars.php');
require_once('component/functions.php');
require_once('component/dbhelper.php');
$error_msg = "";
$incomplete = true;

//if (!isset($_SESSION['username'])) {
if (isset($_POST['submit'])) {
    $dbc_helper = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $user_name = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (!empty($user_name) && !empty($password)) {
        $query = "SELECT user_id, username, password, votes_left, ticket FROM user WHERE username = '$user_name' AND password = sha('$password')";
        $result = $dbc_helper->selectUpdateData($query);

        if ($result->rowCount() == 1) {

            $incomplete = false;
            $row = $result->fetch();
            $_SESSION['user_id'] = $row['user_id'];
//                echo $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['votes_left'] = $row['votes_left'];
            $_SESSION['ticket'] = $row['ticket'];
//                echo $row['username'];
            setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));
            setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));
            setcookie('votes_left', $row['votes_left'], time() + (60 * 60 * 24 * 30));
            setcookie('ticket', $row['ticket'], time() + (60 * 60 * 24 * 30));
            $menu_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php?page=camping';
            header('Location: ' . $menu_url);

        } else {
            $error_msg = 'Sorry, you have entered an invalid username or password';
            echo '<div class="alert alert-danger col-md-offset-3 col-md-6">' . $error_msg . '</div>';
        }
    } else {
        $error_msg = 'You must enter username and password to log in';
        echo '<div class="alert alert-danger col-md-offset-3 col-md-6">' . $error_msg . '</div>';
    }
}
//}

if ($incomplete) {
    ?>
    <div class="logInForm col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
        <!--        <form action="--><?php //echo $_SERVER['PHP_SELF']
        ?><!--" method="post" class="form-horizontal">-->
        <form action="index.php?page=login" method="post" class="form-horizontal">
            <fieldset>
                <legend>Welcome Back!</legend>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-user fa-fw" aria-hidden="true"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required
                           value="<?php if (!empty($username)) echo $username; ?>">
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden="true"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required
                           value="<?php if (!empty($password1)) echo $password1; ?>">
                </div>
            </fieldset>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg center-block" name="submit">Log In</button>
            </div>
        </form>
    </div>
    <?php
}
?>