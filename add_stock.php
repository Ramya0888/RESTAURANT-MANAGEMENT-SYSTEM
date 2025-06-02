<?php
include 'db.php';

if (isset($_POST['item']) && isset($_POST['count']) && isset($_POST['message_id'])) {
    $item = $_POST['item'];
    $count = (int)$_POST['count'];
    $message_id = (int)$_POST['message_id'];

    // Update the stock in menu
    $stmt = $conn->prepare("UPDATE menu SET stock = stock + ? WHERE name = ?");
    $stmt->bind_param("is", $count, $item);

    if ($stmt->execute()) {
        // After stock update, delete the message
        $conn->query("DELETE FROM chat WHERE id = $message_id");
        echo "Stock updated and message removed!";
    } else {
        echo "Failed to update stock.";
    }
} else {
    echo "Invalid request.";
}
?>
