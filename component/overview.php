<?php
/**
 * Created by PhpStorm.
 * User: Haiyi
 * Date: 11/22/2016
 * Time: 11:22 PM
 */
?>

<div class="payBox">
    <!--    <form method="post" action="--><?php //echo $_SERVER['PHP_SELF'] ?><!--">-->
    <form method="post" action="index.php">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Item</th>
                <th>Date</th>
                <th>Price</th>
                <!--                <th>Subtotal</th>-->
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Ticket</td>
                <td>02-Feb-2017</td>
                <td>€ 50.00</td>
                <!--                <td>€ 50.00</td>-->
            </tr>
            <tr>
                <td class="text-right" colspan="4">Total Price: € 50:00</td>
            </tr>
            </tbody>
        </table>
        <i id="btnPayBoxClose" class="fa fa-times" aria-hidden="true"></i>
        <button id="btnPayment" type="submit" name="ticketSubmit" class="btn btn-success pull-right">Buy</button>
    </form>
</div>