<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }

        /* Sidebar - vertical left */
        .sidebar {
            background-color: black;
            color: white;
            width: 180px;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            cursor: pointer;
        }

        .sidebar a:hover {
            background-color: #222;
        }

        /* Main content area */
        .main-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Top black bar with logout */
        .topbar {
            background-color: black;
            height: 60px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 20px;
            color: white;
            position: relative;
        }

        /* Logout button */
        .logout-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
        }

        /* Main content below top bar */
        .main-content {
            background-color: #d8f1fb;
            flex-grow: 1;
            padding: 40px 20px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            position: relative;
        }

        /* Sidebar link "Admin Dashboard" is small text on screenshot, keep it normal */
    </style>
</head>
<body>

<div class="sidebar">
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="add_items.php">Add Menu Items</a>
    <a href="view_items.php">View Menu Items</a>
    <a href="view_orders.php">View Orders</a>
</div>

<div class="main-area">
    <div class="topbar">
        <form action="../logout.php" method="POST" style="margin:0;">
            <input class="logout-btn" type="submit" value="Log Out">
        </form>
    </div>

    <div class="main-content">
        Admin Dashboard
    </div>
</div>

</body>
</html>
