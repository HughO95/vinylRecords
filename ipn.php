<?php
//paypal integration file


include 'db.php';

$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2) {
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
}

$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}

$paypal_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
//$paypal_url = 'https://ipnpb.paypal.com/cgi-bin/webscr'; // Uncomment this for live environment

$ch = curl_init($paypal_url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);
curl_close($ch);

if (strcmp($res, "VERIFIED") == 0) {
    $payment_status = $_POST['payment_status'];
    $order_id = $_POST['custom'];

    if ($payment_status == "Completed") {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'Completed' WHERE id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = :payment_status WHERE id = :order_id");
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
    }
}
?>
