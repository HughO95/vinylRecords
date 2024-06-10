<?php
include 'db.php';

$order_id = intval($_GET['order_id']);
$stmt = $conn->prepare("DELETE FROM orders WHERE id = :order_id");
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();

echo "<h1>Payment Cancelled</h1>";
echo "<p>Your payment was cancelled. The order has been removed.</p>";
?>
