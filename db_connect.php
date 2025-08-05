<?php
$servername = "localhost";
$username = "root";
$password = ""; // usually empty on local servers
$dbname = "dabba";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
