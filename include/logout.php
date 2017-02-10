<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/30/2016
 * Time: 1:31 PM
 */
session_start();

if (isset($_SESSION['user_id'])) {
    $_SESSION = array();

    if (isset($_COOKIE['username'])) {
        setcookie('username', '', time() - 3600);
    }

    session_destroy();
}

$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php?page=camping';
header('Location: ' . $home_url);
?>