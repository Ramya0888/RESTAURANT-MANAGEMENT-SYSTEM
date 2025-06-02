<?php
include 'db.php';

// Fetch available menu items
$items = $conn->query("SELECT * FROM menu WHERE stock > 0");

// Fetch past orders
$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");

// Fetch the top 3 most ordered items
$top_ordered_result = $conn->query("
    SELECT m.id, m.name, SUM(oi.quantity) AS total_ordered
    FROM order_items oi
    INNER JOIN menu m ON oi.item_id = m.id
    GROUP BY oi.item_id
    ORDER BY total_ordered DESC
    LIMIT 3
");

$top_ordered_items = [];
if ($top_ordered_result && $top_ordered_result->num_rows > 0) {
    $rank = 1;
    while ($row = $top_ordered_result->fetch_assoc()) {
        $top_ordered_items[$row['id']] = [
            'name' => $row['name'],
            'rank' => $rank,
        ];
        $rank++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer | Restaurant</title>
    <link rel="stylesheet" href="assets/index.css">
    <style>
        body {
            font-family: monospace;
            background: linear-gradient(palevioletred, white);
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 3em;
        }

        .container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        #menu {
            display: grid;
            grid-template-columns: repeat(3,300px);
            gap: 20px;
            flex: 2;
        }
        #main-container{
            display:flex;
            flex-direction:row;
        }
        .item-box {
            background-color: #fffbea;
            border: 2px dashed #f4c542;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .item-box img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .item-box h3 {
            margin: 10px 0 5px;
        }

        .item-box p {
            margin: 5px 0;
        }

        .addtocart {
            background-color: #ffbb00;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        #side {
            flex: 1;
            margin-left: 20px;
            background-color: #fff8dc;
            padding: 20px;
            border-radius: 15px;
            border: 2px dashed #f0a500;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            min-width: 300px;
        }

        #cart {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .cart-row {
            font-size: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 10px;
        }

        .cart-btn {
            height: 35px;
            width: 35px;
            background-color: #f57c00;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1em;
            cursor: pointer;
        }
        #welcome{
            font-size:4em;
        }
        .placeorder {
            margin-top: 15px;
            background-color: lightgoldenrodyellow;
            color: brown;
            font-size: 1.5em;
            padding: 10px;
            width: 100%;
            border: dashed black 3px;
            border-radius: 10px;
            cursor: pointer;
        }
    </style>
    <script src="cart.js" defer></script>
</head>
<body>
<h1 id="welcome">🍽️ Welcome to Our Restaurant</h1>
<div id="main-container">
<div id="main">
    

    <div id="menu">
        <?php while ($row = $items->fetch_assoc()) { ?>
            <div class="item">
                <img src="assets/images/<?php echo htmlspecialchars($row['images']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>💲 Price: $<?php echo $row['price']; ?></p>
                
                <?php 
                if (isset($top_ordered_items[$row['id']])) { 
                    $badge = "";
                    if ($top_ordered_items[$row['id']]['rank'] == 1) {
                        $badge = "Most Ordered!";
                    } elseif ($top_ordered_items[$row['id']]['rank'] == 2) {
                        $badge = "Most Ordered!";
                    } elseif ($top_ordered_items[$row['id']]['rank'] == 3) {
                        $badge = "Most Ordered!";
                    }
                ?>
                     <h3 style="font-size: 16px; color: black; margin-top: 5px;"><?php echo $badge; ?></h3>
                <?php } ?>

                <button class="addtocart" onclick="addToCart(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>)">Add to Cart</button>
            </div>
        <?php } ?>
    </div>
</div>

<div id="side">
    <h2>🛒 Your Cart</h2>
    <div id="cart"></div>
    <h3>Total: $<span id="total">0</span></h3>
    <button class="placeorder" onclick="placeOrder()">✅ Order Now</button>

    <!-- Customer Orders Section -->
    <?php 
    $grand_total = 0; 

    if ($orders->num_rows > 0) { ?>
        <h2 style="margin-top: 30px;">🧾 Your Previous Orders</h2>
        <ul style="list-style: none; padding: 0;">
            <?php while ($order = $orders->fetch_assoc()) { ?>
                <li style="margin-bottom: 20px; background: #fff3cd; padding: 15px; border-radius: 10px;">
                    <strong>Order ID:</strong> <?php echo $order['id']; ?> <br>
                    <strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?> <br>
                    <strong>Created At:</strong> <?php echo $order['created_at']; ?> <br>

                    <strong>Items:</strong>
                    <ul style="padding-left: 20px;">
                        <?php
                        $order_id = $order['id'];
                        $order_items = $conn->query("
                            SELECT oi.quantity, m.name, m.price
                            FROM order_items oi
                            INNER JOIN menu m ON oi.item_id = m.id
                            WHERE oi.order_id = $order_id
                        ");

                        $order_total = 0;

                        if ($order_items->num_rows > 0) {
                            while ($item = $order_items->fetch_assoc()) {
                                echo "<li>" . htmlspecialchars($item['name']) . " (Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . ")</li>";

                                $item_total = $item['quantity'] * $item['price'];
                                $order_total += $item_total;
                            }
                        } else {
                            echo "<li>No items found for this order.</li>";
                        }

                        $grand_total += $order_total;
                        ?>
                    </ul>

                    <strong>Order Total:</strong> $<?php echo number_format($order_total, 2); ?>
                </li>
            <?php } ?>
        </ul>

        <div style="margin-top: 30px; padding: 20px; background: #d4edda; border-radius: 10px;">
            <h3>🧮 Total Across All Orders: $<?php echo number_format($grand_total, 2); ?></h3>
            <button onclick="payNow(<?php echo $grand_total; ?>)" style="margin-top: 10px; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                💳 Pay Now
            </button>
        </div>

    <?php } ?>
</div>
</div>
<script>
function payNow(amount) {
    if (amount <= 0) {
        alert("No outstanding payment to process!");
    } else {
        alert("Redirecting to payment gateway for $" + amount.toFixed(2) + " payment...");
        // window.location.href = "payment.php?amount=" + amount;
    }
}
</script>

</body>
</html>
