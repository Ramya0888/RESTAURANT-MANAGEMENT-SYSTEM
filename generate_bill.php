<?php
include 'db.php';
$order_id = $_GET['order_id'];

$bill = $conn->query("SELECT * FROM bills WHERE order_id = $order_id")->fetch_assoc();

echo json_encode(["total" => $bill['total']]);
?>