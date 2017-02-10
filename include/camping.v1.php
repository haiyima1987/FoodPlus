<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/18/2016
 * Time: 11:44 AM
 */
if (isset($_POST["person_count"])) {
    session_start();
}

$reservation_complete = false;
$path_head = isset($_POST["person_count"]) ? "../" : "";
require_once($path_head . "component/dbhelper.php");
require_once($path_head . "component/connectvars.php");
require_once($path_head . "component/appvars.php");
require_once($path_head . "component/functions.php");
//require_once("component/dbhelper.php");
//require_once("component/connectvars.php");
//require_once("component/appvars.php");
//require_once("component/functions.php");

if (isset($_POST["person_count"])) {
    $msg = "";
    $reserve_account = $_SESSION["user_id"];
    $person_count = $_POST["person_count"];
    $spot_id = $_POST["spot_id"];
    $ticket = isset($_POST["ticket"]) ? $_POST["ticket"] : 0;

    $db = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $reserve_num = generateRandomNumber();
    $query_reservation = "INSERT INTO reservation (res_number, date_time, num_of_people, user_id, spot_id) VALUES ($reserve_num, '" .
        date("Y-m-d h:i") . "', $person_count, '$reserve_account', $spot_id)";
    $result_reservation = $db->insertDeleteData($query_reservation);

    $query_reserve_spot = "UPDATE camping_spot SET status = 1 WHERE spot_id = " . $spot_id;
    $result_tent = $db->selectUpdateData($query_reserve_spot);

    $result_ticket = 0;
    if ($ticket == 1) {
        $query_ticket = "UPDATE user SET ticket = 1 WHERE user_id = $reserve_account";
        $result_tent = $db->selectUpdateData($query_ticket);
    }

    if ($result_reservation && $result_tent) {
        $reservation_complete = true;
        echo '<div class="alert alert-success confirmation">';
        echo '<p><strong>Reservation Succeeded</strong></p>';
        echo '<p><strong>Your Reservation Number: ' . $reserve_num . '</strong></p>';
        echo '<p><strong>Totally ' . $person_count . ' Companions</strong></p><br><br>';
        echo '<a href="index.php" class="btn btn-success">Back To Home Page</a>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger col-sm-6 col-sm-offset-3">';
        $msg .= "Error Saving Data";
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
            <fieldset>
                <legend>Reservation</legend>
                <?php
                if (!isset($_SESSION['user_id']) || $_SESSION['ticket'] == 0) {
                    ?>
                    <div class="form-group">
                        <label for="ticket">Please Buy a Ticket First</label>
                        <input id="ticket" class="form-control" type="text" value="1" disabled>
                        <h5 class="text-right">Price: € 50.00</h5>
                    </div>
                    <?php
                }
                ?>
                <br>
                <div class="form-group">
                    <label for="personCount">Number of Campers</label>
                    <input class="form-control" id="personCount" type="number" min="1" max="6" value="3">
                    <h5 class="text-right">€ 30 for host, € 20 per guest</h5>
                    <h5 id="subtotal" class="text-right">Subtotal: € 70</h5>
                </div>
                <br>
                <div class="form-group">
                    <h5 id="total" class="text-right">Total: € 120</h5>
                </div>
            </fieldset>
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<button id="btnSubmit" type="button" class="btn btn-block btn-success pull-right">Ready</button>';
            } else {
                echo '<br><br>';
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

<script src="js/reservation.js"></script>