<?php
include '../db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // Keep original filename instead of using uniqid()
        $originalName = basename($_FILES['image']['name']);
        $target_file = $target_dir . $originalName;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $imagePath = $originalName;  // Save just filename in DB
            } else {
                $message = "Failed to upload image.";
            }
        } else {
            $message = "Only JPG, JPEG, PNG & GIF files allowed.";
        }
    } else {
        $imagePath = "";
    }

    if (empty($message)) {
        $sql = "INSERT INTO menu_items (name, price, category, image) VALUES ('$name', '$price', '$category', '$imagePath')";
        if ($conn->query($sql) === TRUE) {
            $message = "Item added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add Menu Item</title>
<style>
  /* Reset */
  * {
    box-sizing: border-box;
  }

  body, html {
    margin: 0; padding: 0;
    height: 100%;
    font-family: Arial, sans-serif;
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
  }
  .sidebar a {
    display: block;
    color: white;
    padding: 12px 0;
    text-decoration: none;
    border-bottom: 1px solid #444;
    cursor: pointer;
  }
  .sidebar a:hover {
    background: #222;
  }

  /* Main content */
  .main {
    margin-left: 220px;
    height: 100vh;
    background: #d8f0fc; /* light blue */
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 60px;
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
  }

  /* Form styling */
  form {
    background: white;
    padding: 30px 40px;
    border-radius: 8px;
    width: 350px;
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
  }

  form label {
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 14px;
    color: #333;
  }

  form input[type="text"],
  form input[type="number"],
  form select,
  form input[type="file"] {
    width: 100%;
    padding: 10px 8px;
    border: 2px solid #4267b2;
    border-radius: 4px;
    font-size: 14px;
  }

  form input[type="submit"] {
    background: #0033cc;
    color: white;
    border: none;
    padding: 12px;
    margin-top: 20px;
    width: 100%;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    border-radius: 4px;
  }
  form input[type="submit"]:hover {
    background: #001f80;
  }

  /* Message */
  .message {
    margin-bottom: 20px;
    color: green;
    font-weight: bold;
    font-size: 14px;
    text-align: center;
  }
  .error {
    color: red;
  }
</style>
</head>
<body>

<div class="sidebar">
  <div style="font-weight:bold; margin-bottom:30px;">Admin Dashboard</div>
  <a href="add_items.php">Add Menu Items</a>
  <a href="view_items.php">View Menu Items</a>
  <a href="view_orders.php">View Orders</a>
</div>

<button class="logout-btn" onclick="window.location.href='../logout.php'">Log Out</button>

<div class="main">
  <?php if(!empty($message)): ?>
    <div class="<?php echo strpos($message, 'Error') === false ? 'message' : 'error'; ?>">
      <?php echo $message; ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" action="">
    <label>Upload item Image:</label>
    <input type="file" name="image" accept="image/*">

    <label>Enter item Name:</label>
    <input type="text" name="name" required>

    <label>Enter item Price:</label>
    <input type="number" step="0.01" name="price" required>

    <label>Enter item Category:</label>
    <select name="category" required>
      <option value="">Select Category</option>
      <option value="Starter">Starter</option>
      <option value="Main Course">Main Course</option>
      <option value="Dessert">Dessert</option>
      <option value="Beverage">Beverage</option>
    </select>

    <input type="submit" value="add item">
  </form>
</div>

</body>
</html>
