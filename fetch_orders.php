<?php
include 'db.php';

$stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll();

foreach ($orders as $order) {
    echo "<div class='record'>";
    echo "<h2>Order #" . htmlspecialchars($order['id']) . "</h2>";
    echo "<p>Genre: " . htmlspecialchars($order['genre']) . "</p>";
    echo "<p>Payment Status: " . htmlspecialchars($order['payment_status']) . "</p>";
    echo "</div>";
}
?>
