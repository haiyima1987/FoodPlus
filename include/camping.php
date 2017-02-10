<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/18/2016
 * Time: 11:44 AM
 */
if (isset($_POST["reserve_account"])) {
    session_start();
}

$reservation_complete = false;
$path_head = isset($_POST["reserve_account"]) ? "../" : "";
require_once($path_head . "component/dbhelper.php");
require_once($path_head . "component/connectvars.php");
require_once($path_head . "component/appvars.php");
require_once($path_head . "component/functions.php");
//require_once("component/dbhelper.php");
//require_once("component/connectvars.php");
//require_once("component/appvars.php");
//require_once("component/functions.php");

if (isset($_POST["reserve_account"])) {
    $post_count = count($_POST); // count how many elements in post variable
    $person_count = 1; // total person count
    $person_array = []; // array containing reserve account and companion accounts
    $valid_array = []; // array containing valid account numbers
    $msg = "";

    $reserve_account = $_POST["reserve_account"];
    $reserve_name = $_POST["reserve_name"];
    $spot_id = $_POST["spot_id"];
    array_push($person_array, $reserve_account);

    $db = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query_check_accounts = "SELECT user_id, last_name FROM user WHERE user_id in (" . $reserve_account;

    if ($post_count > 3) {
        $account_one = $_POST["account1"];
        $name_one = $_POST["name1"];
        $query_check_accounts .= ", " . $account_one;

        $person_count++;
        array_push($person_array, $account_one);

        if ($post_count > 5) {
            $account_two = $_POST["account2"];
            $name_two = $_POST["name2"];
            $query_check_accounts .= ", " . $account_two;

            $person_count++;
            array_push($person_array, $account_two);

            if ($post_count > 7) {
                $account_three = $_POST["account3"];
                $name_three = $_POST["name3"];
                $query_check_accounts .= ", " . $account_three;

                $person_count++;
                array_push($person_array, $account_three);
            }
        }
    }
    $query_check_accounts .= ")";
//    echo $query_check_accounts;
    $person_list = $db->selectUpdateData($query_check_accounts)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($person_list as $key => $value) {
        array_push($valid_array, $value["user_id"]);
    }
//    echo(count($person_list));

    if (count($person_list) == $person_count) {
        $reserve_num = generateRandomNumber();
        $query_reservation = "INSERT INTO reservation (res_number, date_time, num_of_people, user_id, spot_id) VALUES ($reserve_num, '" .
            date("Y-m-d h:i") . "', $person_count, '$reserve_account', $spot_id)";
//        $query_reserved_account = "INSERT INTO reserved_tent (res_number, tent_number) VALUES ($reserve_num, $spot_id)";
        $result_one = $db->insertDeleteData($query_reservation);
//        $result_two = $db->insertDeleteData($query_reserved_account);
        $query_reserve_spot = "UPDATE camping_spot SET status = 1 WHERE spot_id = " . $spot_id;
        $result_tent = $db->selectUpdateData($query_reserve_spot);

        if ($result_one && $result_tent) {
            $reservation_complete = true;
            echo '<div class="alert alert-success conformation">';
            echo '<p><strong>Reservation Succeeded</strong></p>';
            echo '<p><strong>Your Reservation Number: ' . $reserve_num . '</strong></p><br><br>';
            foreach ($person_list as $key => $value) {
                echo '<p>Companion Account: ' . $value["user_id"] . '</p>';
                echo '<p>Companion Name: ' . $value["last_name"] . '</p><br><br>';
            }
            echo '<a href="index.php" class="btn btn-success">Back To Home Page</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="alert alert-danger col-sm-6 col-sm-offset-3">';
        foreach ($person_array as $key => $value) {
            if (!in_array($value, $valid_array)) {
//                echo '<p>Invalid Account Number: ' . $value . '</p>';
                $msg .= "Invalid Account Number: $value <br>";
            }
        }
        echo '</div>';
    }
}

if (!$reservation_complete) {
// if the reservation is not complete, show the sections below
    ?>
    <img class="bgImg" src="img/bg3.png" alt="">
    <div class="heading">
        <div class="col-md-8 col-md-offset-2">
            <br><br>
            <h2>FoodPlus Food & Cooking Festival!</h2>
            <h2>Reserve your spot!</h2>
        </div>
    </div>
    <div class="content col-sm-8 col-md-7 col-md-offset-1">
        <?php
        // query to find unique sites
        $db = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query_sites = "SELECT DISTINCT location FROM camping_spot";
        $data_sites = $db->selectUpdateData($query_sites)->fetchAll(PDO::FETCH_ASSOC);
        $index = 0;

        // loop through each site to get contained tents
        foreach ($data_sites as $key => $value) {
            // query to find tents for each tent
            $query_single_site = "SELECT * FROM camping_spot WHERE location = '" . $value["location"] . "'";
            $data_single_site = $db->selectUpdateData($query_single_site)->fetchAll(PDO::FETCH_ASSOC);

            echo $index % 2 == 0 ? '<div class="campingSiteEven">' : '<div class="campingSiteOdd">';
            echo '<h5>Site: ' . strtoupper($value["location"]) . '</h5>';
            echo '<div class="row">';

            // loop through each tent and load different images according to availability
            foreach ($data_single_site as $id => $val) {
                $img_src = $val["status"] == 1 ? 'tent_reserved.png' : 'tent_available.png';
                $img_class = $val["status"] == 1 ? 'reserved' : 'available';
                echo '<div class="tentUnit ' . $value["location"] . ' col-xs-1">';
                echo '<img class="' . $img_class . '" src="' . FP_UPLOADPATH . $img_src . '" alt="' . $val["spot_id"] . '">';
                echo '<p>' . ($id + 1) . '</p>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            $index++;
        }
        ?>
    </div>
    <div class="content col-sm-4 col-md-3">
        <form class="reservationForm form-group">
            <fieldset id="userFieldset">
                <legend>User Information:</legend>
                <div class="form-group"><label for="reserveAccount">Account:</label>
                    <input type="text" class="form-control" id="reserveAccount"
                           value="<?php if (isset($reserve_account)) echo $reserve_account; ?>"
                           required/>
                </div>
                <div class="form-group"><label for="reserveName">Last Name:</label>
                    <input type="text" class="form-control" id="reserveName"
                           value="<?php if (isset($reserve_name)) echo $reserve_name; ?>"
                           required/>
                </div>
            </fieldset>
            <br>
            <fieldset id="companionFieldset">
                <legend>Companions:</legend>
                <div>
                    <div class="form-group"><label for="account">Account: </label>
                        <input type="text" class="form-control companionAccount" id="account"
                               value="<?php if (isset($account_one)) echo $account_one; ?>"
                               required/>
                    </div>
                    <div class="form-group"><label for="name">Last Name: </label>
                        <input type="text" class="form-control companionName" id="name"
                               value="<?php if (isset($name_one)) echo $name_one; ?>"
                               required/>
                    </div>
                </div>
                <div>
                    <div class="form-group"><label for="account">Account: </label>
                        <input type="text" class="form-control companionAccount" id="account"
                               value="<?php if (isset($account_two)) echo $account_two; ?>"
                               required/>
                    </div>
                    <div class="form-group"><label for="name">Last Name: </label>
                        <input type="text" class="form-control companionName" id="name"
                               value="<?php if (isset($name_two)) echo $name_two; ?>"
                               required/>
                    </div>
                </div>
                <div>
                    <div class="form-group"><label for="account">Account: </label>
                        <input type="text" class="form-control companionAccount" id="account"
                               value="<?php if (isset($account_two)) echo $account_two; ?>"
                               required/>
                    </div>
                    <div class="form-group"><label for="name">Last Name: </label>
                        <input type="text" class="form-control companionName" id="name"
                               value="<?php if (isset($name_three)) echo $name_three; ?>"
                               required/>
                    </div>
                </div>
            </fieldset>
            <?php
            if (isset($_SESSION['user_id'])) {
                ?>
                <br>
                <button id="btnRemove" type="button" class="btn btn-danger col-xs-6">Remove</button>
                <button id="btnAdd" type="button" class="btn btn-success col-xs-6">Add</button>
                <br>
                <button id="btnSubmit" type="button" class="btn btn-block btn-primary pull-right">Confirm</button>
                <?php
            } else {
                echo '<br>';
                echo '<a href="index.php?page=login" class="btn btn-danger btn-block">Login To Reserve Spots</a>';
            }
            ?>
        </form>
    </div>
    <?php
    if (!empty($msg)) {
        echo '<div class="alert alert-danger col-sm-6 reject">';
        echo '<i class="fa fa-times" aria-hidden="true"></i>';
        echo '<p>' . $msg . '</p>';
        echo '</div>';
    }
}
?>

<script id="inputField" type="text/template">
    <div>
        <div class="form-group"><label for="account">Account: </label>
            <input type="text" class="form-control companionAccount" required>
        </div>
        <div class="form-group"><label for="name">Name: </label>
            <input type="text" class="form-control companionName" required>
        </div>
    </div>
</script>

<script src="js/reservation.js"></script>