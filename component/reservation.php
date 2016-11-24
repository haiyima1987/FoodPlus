<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/22/2016
 * Time: 11:22 PM
 */
session_start();

require_once("dbhelper.php");
require_once("connectvars.php");
require_once("appvars.php");

if (isset($_POST["reserve_account"])) {
    $reserve_account = $_POST["reserve_account"];
    $_SESSION["reserve_account"] = $reserve_account;
    $reserve_name = $_POST["reserve_name"];
    $tent_num = $_POST["tent_num"];
    echo $_POST["reserve_account"];
    echo "i love you";
} else {
    echo "i hate you";
}
?>