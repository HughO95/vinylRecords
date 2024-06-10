<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $genre = htmlspecialchars($_POST['genre']);
    $user_id = $_SESSION['user_id'];

    if (!empty($genre) && !empty($user_id)) {
        $stmt = $conn->prepare("SELECT * FROM records WHERE genre = :genre ORDER BY RAND() LIMIT 1");
        $stmt->bindParam(':genre', $genre);
        $stmt->execute();
        $record = $stmt->fetch();

        if ($record) {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, record_id, genre, payment_status) VALUES (:user_id, :record_id, :genre, 'Pending')");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':record_id', $record['id']);
            $stmt->bindParam(':genre', $genre);

            if ($stmt->execute()) {
                $order_id = $conn->lastInsertId();
                $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
                $business_email = "your-paypal-business-email@example.com";
                $item_name = "Mystery Vinyl Record (" . $genre . ")";
                $amount = $record['price'];

                echo "
                <form action='$paypal_url' method='post' id='paypal-form'>
                    <input type='hidden' name='business' value='$business_email'>
                    <input type='hidden' name='cmd' value='_xclick'>
                    <input type='hidden' name='item_name' value='$item_name'>
                    <input type='hidden' name='amount' value='$amount'>
                    <input type='hidden' name='currency_code' value='USD'>
                    <input type='hidden' name='custom' value='$order_id'>
                    <input type='hidden' name='return' value='http://your-website.com/payment_success.php?order_id=$order_id'>
                    <input type='hidden' name='cancel_return' value='http://your-website.com/payment_cancel.php?order_id=$order_id'>
                    <input type='hidden' name='notify_url' value='http://your-website.com/ipn.php'>
                </form>
                <script>
                    document.getElementById('paypal-form').submit();
                </script>
                ";
            } else {
                echo "Error: Could not create order.";
            }
        } else {
            echo "Error: No records available for the selected genre.";
        }
    } else {
        echo "Error: Invalid input.";
    }
}
?>
