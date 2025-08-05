<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle status update after clicking Update button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    if (in_array($status, ['Pending', 'Delivered'])) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch orders data
$sql = "
    SELECT 
        o.id AS order_id,
        u.username AS customer_name,
        m.name AS item_name,
        m.image AS item_image,
        m.price AS total_price,
        o.status
    FROM orders o
    JOIN userss u ON o.user_id = u.id
    JOIN menu_items m ON o.menu_item_id = m.id
    ORDER BY o.id ASC
";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Orders - Admin</title>
    <style>
        /* Same styles as before */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .sidebar {
            height: 100vh;
            width: 180px;
            background-color: black;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: white;
            display: flex;
            flex-direction: column;
        }
        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            display: block;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar a.active {
            background-color: #0044ff;
        }
        .logout-btn {
            position: fixed;
            right: 20px;
            top: 15px;
            background: red;
            color: white;
            border: none;
            padding: 10px 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 3px;
            z-index: 1000;
        }
        .main-content {
            margin-left: 180px;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th {
            background-color: #0044ff;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
        }
        tbody tr:nth-child(odd) {
            background-color: #d3d3d3;
        }
        tbody tr:nth-child(even) {
            background-color: #a9a9a9;
        }
        img.menu-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        select {
            padding: 4px;
            font-size: 14px;
        }
        .update-btn {
            margin-left: 10px;
            padding: 4px 10px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>

    <script>
        function toggleUpdateButton(selectElem, btnId) {
            const updateBtn = document.getElementById(btnId);
            if (selectElem.value !== selectElem.getAttribute('data-original')) {
                updateBtn.style.display = 'inline-block';
            } else {
                updateBtn.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="sidebar">
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="add_items.php">Add Menu Items</a>
    <a href="view_items.php">View Menu Items</a>
    <a href="view_orders.php" class="active">View Orders</a>
</div>

<button class="logout-btn" onclick="window.location.href='../logout.php'">Log Out</button>

<div class="main-content">
    <h2>View Orders</h2>
    <table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Item Name</th>
            <th>Item Image</th>
            <th>Total Price</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $counter = 0;
            while ($order = $result->fetch_assoc()) {
                $counter++;
                $orderId = $order['order_id'];
                $currentStatus = $order['status'];
                $btnId = "updateBtn_$counter";

                echo "<tr>";
                echo "<td>" . htmlspecialchars($orderId) . "</td>";
                echo "<td>" . htmlspecialchars($order['customer_name']) . "</td>";
                echo "<td>" . htmlspecialchars($order['item_name']) . "</td>";
                echo "<td><img src='../uploads/" . htmlspecialchars($order['item_image']) . "' alt='Item Image' class='menu-img'></td>";
                echo "<td>$ " . number_format($order['total_price'], 2) . "</td>";

                // Status form with Update button
                echo "<td>
                    <form method='post' action='' style='display: inline-block;'>
                        <input type='hidden' name='order_id' value='" . $orderId . "'>
                        <select name='status' 
                                data-original='" . $currentStatus . "' 
                                onchange='toggleUpdateButton(this, \"$btnId\")'>
                            <option value='Pending'" . ($currentStatus == 'Pending' ? ' selected' : '') . ">Pending</option>
                            <option value='Delivered'" . ($currentStatus == 'Delivered' ? ' selected' : '') . ">Delivered</option>
                        </select>
                        <button type='submit' id='$btnId' class='update-btn' style='display:none;'>Update</button>
                    </form>
                </td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No orders found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</div>

</body>
</html>
