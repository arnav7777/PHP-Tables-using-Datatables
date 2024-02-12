<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = $_GET['id'];
  $conn->query("SELECT * FROM employees WHERE id=$id");
  $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $name = $row['name'];
  $email = $row['email'];


  session_start();
  $_SESSION['delete_employee'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  <strong>$name - ($email)</strong> has been successfully Deleted.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>";
  $conn->query("DELETE FROM employees WHERE id=$id");

  header('Location: index.php');
  exit;
}
