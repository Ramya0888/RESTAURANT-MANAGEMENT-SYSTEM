<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$total = array_reduce($data['cart'], fn($sum, $item) => $sum + ($item['price'] * $item['qty']), 0);

$conn->query("INSERT INTO bills (total, status) VALUES ($total, 'unpaid')");
$bill_id = $conn->insert_id;

foreach ($data['cart'] as $item) {
    $conn->query("INSERT INTO order_items (bill_id, item_id, qty) VALUES ($bill_id, {$item['id']}, {$item['qty']})");
}

echo json_encode(["bill_id" => $bill_id]);
?>
