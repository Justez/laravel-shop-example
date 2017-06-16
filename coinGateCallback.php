<?php
/**
 * Created by PhpStorm.
 * User: Justina
 * Date: 08/06/2017
 * Time: 13:11
 */
include_once ('controllers/DBController.php');
$DBObj = new DBController();
// Your custom order_id is defined when you creating new order: https://developer.coingate.com/docs/create-order
// Also don't forget to prevent SQL injection
$order = $DBObj -> runQuery("SELECT * FROM orders WHERE id = " . $_POST['order_id']);
// token is your random secure string (for example: 5d02161be9bfb6192a33) for each order
if ($_POST['token'] == $order['token']) {
    // Handle CoinGate order status: https://developer.coingate.com/docs/order-statuses
    $status = NULL;
    if ($_POST['status'] == 'paid') {
        if ($_POST['price'] >= $order['amount']) {
            $status = 'paid';
        }
    }
    else {
        $status = $_POST['status'];
    }

    if (!is_null($status)) {
        $DBObj->runQuery("UPDATE orders SET status = '".$status."' WHERE id = ".$_POST['order_id']);
    }

}
