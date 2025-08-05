<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $menuItemId = (int)$_POST['menu_item_id'];
    $quantity = (int)$_POST['quantity'];
    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO orders (user_id, menu_item_id, quantity, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $userId, $menuItemId, $quantity, $status);

    if ($stmt->execute()) {
        header("Location: user_dashboard.php?ordered=1");
        exit();
    } else {
        echo "Order failed: " . $stmt->error;
    }
}
?>
