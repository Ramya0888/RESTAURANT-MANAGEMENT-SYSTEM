<?php
include 'db.php';
$stock = $conn->query("SELECT * FROM menu");
$bills = $conn->query("SELECT * FROM bills WHERE paid = 0");

// Handle chat message sending
if (isset($_POST['send_message'])) {
    $message = $_POST['message'];
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chat (message) VALUES (?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Owner | Restaurant</title>
    <link rel="stylesheet" href="assets/owner.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ffeaa7, #fab1a0);
            padding: 30px;
        }
        h1, h2 {
            color: #2d3436;
        }
        #side{
            margin-right:300px;
            margin-top:-70px;
        }
        #main{
            display:flex;
            justify-content: space-between;
        }
        .stock-item, .bill-box {
            background: #fffbe6;
            border: 2px solid #fdcb6e;
            padding: 15px;
            margin: 10px 0;
            border-radius: 12px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .bill-box button {
            background-color: #e17055;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .bill-box button:hover {
            background-color: #d63031;
        }
        #box{
               background-color:white; 
               height:50px;
               width:800px;
               padding-left:50px;
               border:solid white;
               border-radius:5px;
               margin:10px;
        }
        textarea{
            height:300px;
            width:1000px;
            font-size:2em;
        }
        #item{
            gap:30px;
        }
        #box-container{
            gap:10px;
        }
        button{
            height:50px;
            width:300px;
            font-size:1.5em;
        }
    </style>
    <script>
        function markPaid(id) {
            fetch('mark_paid.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            }).then(() => location.reload());
        }
    </script>
</head>
<body>

<h1>📊 Stock & Bills</h1>
<h2>📦 Stock Levels</h2>
<div id="main">

<div id="box-container">
    <?php while ($row = $stock->fetch_assoc()) { ?>
        <div id="box">
        <h3 id="item"><?php echo $row['name']; ?> - Stock: <?php echo $row['stock']; ?></h3>
        </div>
    <?php } ?>
</div>
<div id="side">
<h2>💳 Pending Bills</h2>
<div>
    <?php while ($row = $bills->fetch_assoc()) { ?>
        <h3>Bill #<?php echo $row['id']; ?> - Total: $<?php echo $row['total']; ?></h3>
        <button onclick="markPaid(<?php echo $row['id']; ?>)">Mark as Paid</button>
    <?php } ?>
</div>
</div>
</div>
<h2>📨 Send the item name to be prepared</h2>
<form method="POST" style="margin-top: 20px;">
    <textarea name="message" rows="4" cols="40" placeholder="Type your message to kitchen..." required></textarea><br><br>
    <button type="submit" name="send_message" style="padding: 10px 20px;">Send Message</button>
</form>

</body>
</html>
