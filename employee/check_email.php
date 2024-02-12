<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'employee';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];

  // Sanitize the input to prevent SQL injection
  $email = mysqli_real_escape_string($conn, $email);

  // Check if the email already exists in the database
  $result = $conn->query("SELECT COUNT(*) AS count FROM employees WHERE email = '$email'");
  $row = $result->fetch_assoc();
  $emailExists = $row['count'] > 0;

  // Set the content type to JSON
  header('Content-Type: application/json');

  // Return the response as JSON
  echo json_encode(['emailExists' => $emailExists]);
}
