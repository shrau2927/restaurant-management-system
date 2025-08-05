<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$message = "";

// Handle order form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_item_id'], $_POST['quantity'])) {
    $menuItemId = (int)$_POST['menu_item_id'];
    $quantity = (int)$_POST['quantity'];

    if ($menuItemId > 0 && $quantity > 0) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, menu_item_id, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $userId, $menuItemId);
        if ($stmt->execute()) {
            $message = "Order placed successfully!";
        } else {
            $message = "Failed to place order: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Invalid order data.";
    }
}

$view = (isset($_GET['view']) && $_GET['view'] === 'dashboard') ? 'dashboard' : 'home';

// Fetch menu items
$itemsSql = "SELECT * FROM menu_items ORDER BY id ASC";
$itemsResult = $conn->query($itemsSql);

// Fetch user's orders
if ($view === 'dashboard') {
    $ordersSql = "
        SELECT o.id AS order_id, m.name AS item_name, m.image, o.status
        FROM orders o
        JOIN menu_items m ON o.menu_item_id = m.id
        WHERE o.user_id = ?
        ORDER BY o.id ASC
    ";
    $stmt = $conn->prepare($ordersSql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $ordersResult = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>User Dashboard</title>
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-image: url('images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
  }

  .navbar {
    background: rgba(0, 0, 0, 0.85);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
  }

  .navbar a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    font-weight: bold;
  }

  .navbar a.active {
    text-decoration: underline;
  }

  .container {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    margin: 40px auto;
    max-width: 1100px;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
  }

  .message {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
  }

  .items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
  }

  .item {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  .item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 6px;
  }

  button.order-btn {
    background: #28a745;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
  }

  button.order-btn:hover {
    background: #218838;
  }

  table {
    border-collapse: collapse;
    width: 100%;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    margin-top: 20px;
  }

  th {
    background-color: #0044ff;
    color: white;
    padding: 12px;
    text-align: left;
  }

  td {
    padding: 12px;
    vertical-align: middle;
  }

  tbody tr:nth-child(odd) {
    background-color: #d3d3d3;
  }

  tbody tr:nth-child(even) {
    background-color: #a9a9a9;
  }

  .order-img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
  }
</style>
</head>
<body>

<div class="navbar">
  <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
  <div>
    <a href="user_dashboard.php"<?php if ($view === 'home') echo ' class="active"'; ?>>Home</a>
    <a href="user_dashboard.php?view=dashboard"<?php if ($view === 'dashboard') echo ' class="active"'; ?>>Dashboard</a>
    <a href="logout.php">Log Out</a>
  </div>
</div>

<div class="container">

  <?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <?php if ($view === 'home'): ?>
    <div class="items-grid">
      <?php while ($item = $itemsResult->fetch_assoc()): ?>
        <div class="item">
          <?php if (!empty($item['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
          <?php else: ?>
            <div style="height:150px;background:#ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#666;">No Image</div>
          <?php endif; ?>
          <h4><?php echo htmlspecialchars($item['name']); ?></h4>
          <p>$ <?php echo number_format($item['price'], 2); ?></p>
          <form method="POST" action="">
            <input type="hidden" name="menu_item_id" value="<?php echo (int)$item['id']; ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="order-btn">Order Now</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>

  <?php else: ?>
    <h2>Your Orders</h2>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Image</th>
          <th>Item Name</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($ordersResult->num_rows > 0): ?>
          <?php while ($order = $ordersResult->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($order['order_id']); ?></td>
              <td>
                <?php if (!empty($order['image'])): ?>
                  <img src="uploads/<?php echo htmlspecialchars($order['image']); ?>" alt="<?php echo htmlspecialchars($order['item_name']); ?>" class="order-img">
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($order['item_name']); ?></td>
              <td><?php echo htmlspecialchars($order['status']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4">You haven’t placed any orders yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  <?php endif; ?>

</div>

</body>
</html>
