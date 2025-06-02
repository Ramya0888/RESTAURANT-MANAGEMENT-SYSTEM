<?php
include 'db.php';
$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];

$conn->query("UPDATE menu SET stock = stock - $quantity WHERE id = $item_id");
echo json_encode(["success" => true]);
?>