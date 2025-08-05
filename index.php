<?php
include 'db_connect.php';
session_start();

// Fetch all menu items
$sql = "SELECT * FROM menu_items ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome - Dabba Restaurant</title>
<style>
  body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f2f2f2;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: black;
  color: white;
  padding: 20px;
}

.header .home {
  font-size: 24px;
  font-weight: bold;
}

.header .buttons a {
  background: #0044ff;
  color: white;
  padding: 10px 20px;
  margin-left: 10px;
  text-decoration: none;
  border-radius: 4px;
  font-weight: bold;
  transition: background 0.3s ease, transform 0.2s ease;
}

.header .buttons a:hover {
  background: #002a80;
  transform: scale(1.05);
}

.container {
  padding: 30px;
}

.items-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.item {
  background: white;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.item:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.item img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 6px;
}

.item h4 {
  margin: 10px 0 5px;
  font-size: 16px;
}

.item p {
  color: #555;
  margin: 0;
  font-weight: bold;
}
</style>
</head>
<body>

<div class="header">
  <div class="home">Welcome Restaurant</div>
  <div class="buttons">
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
  </div>
</div>

<div class="container">
  <h2>Our Menu</h2>
  <div class="items-grid">
    <?php
    if ($result->num_rows > 0) {
      while ($item = $result->fetch_assoc()) {
        echo "<div class='item'>";
        echo "<img src='uploads/" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "'>";
        echo "<h4>" . htmlspecialchars($item['name']) . "</h4>";
        echo "<p>$ " . number_format($item['price'], 2) . "</p>";
        echo "</div>";
      }
    } else {
      echo "<p>No items available.</p>";
    }
    ?>
  </div>
</div>

</body>
</html>
