<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$bill_id = $data['bill_id'];

$total = array_reduce($data['cart'], fn($sum, $item) => $sum + ($item['price'] * $item['qty']), 0);
$conn->query("UPDATE bills SET total = total + $total WHERE id = $bill_id");

foreach ($data['cart'] as $item) {
    $conn->query("INSERT INTO order_items (bill_id, item_id, qty) VALUES ($bill_id, {$item['id']}, {$item['qty']})");
}

echo json_encode(["message" => "Order added to existing bill"]);
?>
