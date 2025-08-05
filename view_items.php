<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT * FROM menu_items ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Items - Admin</title>
    <style>
        body, html {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            height: 100%;
        }

        /* Sidebar */
        .sidebar {
            background: black;
            color: white;
            width: 220px;
            height: 100vh;
            position: fixed;
            padding-top: 30px;
            text-align: center;
            font-size: 16px;
            left: 0;
            top: 0;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px 0;
            text-decoration: none;
            border-bottom: 1px solid #444;
            cursor: pointer;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #222;
        }

        /* Main content */
        .main {
            margin-left: 220px;
            padding: 20px;
        }

        /* Logout button top right */
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
            background-color: #d3d3d3; /* lighter grey */
        }

        tbody tr:nth-child(even) {
            background-color: #a9a9a9; /* darker grey */
        }

        img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div style="font-weight:bold; margin-bottom:30px;">Admin Dashboard</div>
    <a href="add_items.php">Add Menu Items</a>
    <a href="view_items.php" class="active">View Menu Items</a>
    <a href="view_orders.php">View Orders</a>
</div>

<button class="logout-btn" onclick="window.location.href='../logout.php'">Log Out</button>

<div class="main">
    <h2>View Menu Items</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Item Name</th>
                <th>Item Price</th>
                <th>Item Category</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while($item = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['id']) . "</td>";
                    echo "<td><img src='../uploads/" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "'></td>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>$ " . number_format($item['price'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($item['category']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No menu items found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
