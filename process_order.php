<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$conn->query("INSERT INTO orders (status) VALUES ('Pending')");
$order_id = $conn->insert_id;

$total = 0;
foreach ($data as $item) {
    $conn->query("INSERT INTO order_items (order_id, item_id, quantity) VALUES ('$order_id', '{$item['id']}', '{$item['quantity']}')");
    $total += $item['price'] * $item['quantity'];
}

$conn->query("INSERT INTO bills (order_id, total) VALUES ('$order_id', '$total')");

echo json_encode(["success" => true]);
?>