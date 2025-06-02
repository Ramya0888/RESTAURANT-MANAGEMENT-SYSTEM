<?php
include 'db.php';
$id = $_POST['id'];

$conn->query("UPDATE bills SET paid = 1 WHERE id = $id");
echo json_encode(["success" => true]);
?>