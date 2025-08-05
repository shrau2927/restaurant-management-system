<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $role = 'user';

    $check = $conn->query("SELECT id FROM userss WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $message = "⚠️ Email already registered.";
    } else {
        $sql = "INSERT INTO userss (username, email, password, role)
                VALUES ('$username', '$email', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $message = "✅ Registration successful!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Registration</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

  body, html {
    height: 100%;
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: url('biryani.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .container {
    background: rgba(255, 255, 255, 0.9);
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    width: 350px;
    text-align: center;
  }

  h2 {
    margin-bottom: 25px;
    color: #333;
  }

  input[type=text],
  input[type=email],
  input[type=password] {
    width: 100%;
    padding: 12px 10px;
    margin: 10px 0 20px 0;
    border: 1.8px solid #aaa;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s ease;
  }

  input[type=text]:focus,
  input[type=email]:focus,
  input[type=password]:focus {
    border-color: #e67e22;
    outline: none;
  }

  input[type=submit] {
    background-color: #e67e22;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
  }

  input[type=submit]:hover {
    background-color: #cf711c;
  }

  .message {
    margin-top: 20px;
    font-weight: 600;
  }

  .message.success {
    color: green;
  }

  .message.error {
    color: #d00;
  }
</style>
</head>
<body>
  <div class="container">
    <h2>User Registration</h2>

    <?php if (!empty($message)): ?>
      <div class="message <?= strpos($message, 'successful') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="submit" value="Register" />
    </form>
  </div>
</body>
</html>
