<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 12/1/2016
 * Time: 9:34 PM
 */
if (count($_POST) > 0) {
    session_start();
}

// ???? cookie becomes minus ????
//echo '<div id="votesLeft">' . $_SESSION['votes_left'] . '</div>';
//echo '<div>' . $_COOKIE['votes_left'] . '</div>';

$path_head = count($_POST) > 0 ? "../" : "";
require_once($path_head . "component/dbhelper.php");
require_once($path_head . "component/connectvars.php");
require_once($path_head . "component/appvars.php");
require_once($path_head . "component/functions.php");

?>

<?php
$voteComplete = false;
if (count($_POST) > 0) {
    $dbc = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query_vote = "UPDATE competitor_test SET vote = CASE id";
    foreach ($_POST as $key => $value) {
        $value++;
        $query_vote .= " WHEN $key THEN $value";
    }
    $query_vote .= " END WHERE id IN (";
    foreach ($_POST as $key => $value) {
        $query_vote .= $key . ",";
    }
    $query_vote = substr($query_vote, 0, -1) . ")";
    $result_vote = $dbc->selectUpdateData($query_vote);
//    echo $_SESSION['votes_left'];
//    echo $_COOKIE['votes_left'];
//    echo count($_POST);
    $votes_left = $_SESSION['votes_left'] - count($_POST);
//    echo $votes_left;
    $query_total_vote = "UPDATE user SET votes_left = " . $votes_left . " WHERE user_id = " . $_COOKIE['user_id'];
    $result_total_vote = $dbc->selectUpdateData($query_total_vote);
    if ($result_vote && $result_total_vote) {
        $voteComplete = true;
        $_SESSION['votes_left'] = $votes_left;
        setcookie('votes_left', $votes_left, time() + (60 * 60 * 24 * 30));
        echo '<div class="alert alert-success voteSuccess">';
        echo '<p><strong>Thanks For Supporting Your Favorite Chefs</strong></p>';
        echo '<p><strong>You Votes Have Been Successfully Submitted</strong></p>';
        echo '<br><br>';
        echo '<a href="index.php" class="btn btn-success">Back To Home Page</a>';
        echo '</div>';
    }
}

if (!$voteComplete) {
    ?>
    <img class="bgImg" src="img/bg3.png" alt="">
    <div class="heading">
        <div class="col-md-8 col-md-offset-2">
            <br><br>
            <h2>FoodPlus Food & Cooking Festival!</h2>
            <h2>Vote for your best foodie!</h2>
        </div>
    </div>
    <div class="content col-sm-12 col-md-10 col-md-offset-1">
        <?php
        // query to find unique sites
        $db = new DbcHelper(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query_competitors = "SELECT * FROM competitor_test";
        $data = $db->selectUpdateData($query_competitors)->fetchAll(PDO::FETCH_ASSOC);

        $names = array();
        foreach ($data as $key => $value) {
            $names[$key] = $value['name'];
        }
        array_multisort($names, SORT_ASC, $data);

        foreach ($data as $key => $value) {
            echo '<div class="competitor col-xs-4 col-sm-3 col-md-2">';
            echo '<div class="frame unselected">';
            echo '<img src="' . FP_COMPETITORPATH . $value['img_src'] . '" alt="' . $value['id'] . '" vote=' . $value['vote'] . '>';
            echo '<div class="vote">';
            echo '<h3>' . $value['name'] . '</h3>';
            echo '<h5>Current Votes:' . $value['vote'] . '</h5>';
            echo '</div></div></div>';
        }
        ?>
        <div class="btnVotes text-center col-xs-12">
            <?php
            if (isset($_SESSION["user_id"])) {
                if ($_SESSION['votes_left'] > 0) {
                    ?>
                    <h4 class="alert alert-success">You Have <?php echo $_SESSION['votes_left'];
                        echo $_SESSION['votes_left'] > 1 ? ' Votes' : ' Vote'; ?> Left</h4>
                    <button id="btnVote" class="btn btn-success btn-lg text-center">Submit My Votes!</button>
                    <?php
                } else {
                    echo '<h4 class="alert alert-danger">You Have No Vote Left</h4>';
                }
            } else {
                ?>
                <a href="index.php?page=login" class="btn btn-danger btn-lg text-center">Login To Vote</a>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>

<div id="warningBox" class="alert alert-warning">
    <h4>Are you sure? Since you cannot change your votes.</h4>
    <button id="warningYes" class="btn btn-success pull-left">Yes</button>
    <button id="warningNo" class="btn btn-danger pull-right">No</button>
</div>

<!--for JavaScript to retrieve the left votes-->
<div id="votesLeft"><?php echo isset($_SESSION['votes_left']) ? $_SESSION['votes_left'] : -1 ?></div>
<!---->
<!--<div class="errorMsg alert alert-danger">-->
<!--    <i class="fa fa-times" aria-hidden="true"></i>-->
<!--    <h3></h3>-->
<!--</div>-->

<script src="js/vote.js"></script>