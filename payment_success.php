<?php
include 'db.php';

$order_id = intval($_GET['order_id']);
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$order = $stmt->fetch();

if ($order && $order['payment_status'] == 'Completed') {
    echo "<h1>Thank you for your purchase!</h1>";
    echo "<p>Your order for a mystery vinyl record has been successfully processed.</p>";
} else {
    echo "<h1>Payment Pending</h1>";
    echo "<p>Your payment is still being processed. Please check back later.</p>";
}
?>
