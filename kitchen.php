<?php
include 'db.php';

// Get all pending or cooking orders
$orders = $conn->query("SELECT * FROM orders WHERE status = 'Pending' OR status = 'Cooking'");

// Fetch order items for each order
$order_items = [];
$result = $conn->query("SELECT order_items.order_id, menu.name, order_items.quantity 
                        FROM order_items 
                        JOIN menu ON order_items.item_id = menu.id");

while ($row = $result->fetch_assoc()) {
    $order_items[$row['order_id']][] = $row; // Group items by order ID
}

// Fetch chat messages
$messages = $conn->query("SELECT * FROM chat ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kitchen | Restaurant</title>
    <link rel="stylesheet" href="assets/kitchen.css">
    <style>
    #kitchen {
        font-size: 3em;
        text-align: center;
    }

    #main {
        display: flex;
        flex-direction: row;
        gap: 20px;
        padding: 20px;
    }

    /* Left side (Orders section) */
    #left {
        flex: 2;
        background-color: wheat;
        padding: 15px;
        border-radius: 10px;
    }

    /* Right side (Messages section) */
    #right {
        flex: 1;
        background-color: wheat;
        padding: 15px;
        border-radius: 10px;
        max-height: 80vh;
        overflow-y: auto;
    }

    /* Grid layout for orders */
    .orders-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    /* Individual order card */
    .item {
        background-color: #fff8dc; /* Light wheat tone */
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    #right-side div {
        background-color: #fff8dc;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
</style>

    <script>
        function updateOrder(id, status) {
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&status=${status}`
            }).then(() => location.reload());
        }

        function handleAddStock(itemName, messageId) {
            let count = prompt(`How many "${itemName}" items to add?`);
            if (count !== null && count !== "" && !isNaN(count) && count > 0) {
                fetch('add_stock.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `item=${encodeURIComponent(itemName)}&count=${count}&message_id=${messageId}`
                }).then(response => response.text())
                  .then(data => {
                      alert(data);
                      // Remove the message div after updating stock
                      const msgDiv = document.getElementById('message-' + messageId);
                      if (msgDiv) {
                          msgDiv.remove();
                      }
                  });
            } else {
                alert("Invalid input.");
            }
        }
    </script>
</head>
<body>

<h1 id="kitchen">🍳 Kitchen Orders</h1>

<div id="main">
<div id="left">
    <div class="orders-grid">
        <?php while ($row = $orders->fetch_assoc()) { ?>
            <div class="item">
                <h3>Order #<?php echo $row['id']; ?></h3>
                <p>Status: <strong><?php echo $row['status']; ?></strong></p>

                <h4>📝 Ordered Items:</h4>
                <ul>
                    <?php 
                    if (isset($order_items[$row['id']])) {
                        foreach ($order_items[$row['id']] as $item) { 
                            echo "<li>{$item['name']} x {$item['quantity']}</li>";
                        }
                    } else {
                        echo "<li>No items found.</li>";
                    }
                    ?>
                </ul>

                <button onclick="updateOrder(<?php echo $row['id']; ?>, 'Cooking')">🔥 Start Cooking</button>
                <button onclick="updateOrder(<?php echo $row['id']; ?>, 'Cooked')">✅ Mark as Cooked</button>
            </div>
        <?php } ?>
    </div>
</div>


    <div id="right">
        <h2>📨 Items to be prepared</h2>
        <div id="right-side" style="margin-top: 20px;">
            <?php if ($messages->num_rows > 0) { ?>
                <?php while ($msg = $messages->fetch_assoc()) { ?>
                    <div id="message-<?php echo $msg['id']; ?>" style="margin-bottom: 10px;">
                        <strong>🕒 <?php echo date('H:i', strtotime($msg['created_at'])); ?>:</strong> 
                        <?php echo htmlspecialchars($msg['message']); ?>
                        <button style="margin-left:10px;" onclick="handleAddStock('<?php echo addslashes($msg['message']); ?>', <?php echo $msg['id']; ?>)">✅</button>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No messages yet.</p>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>
