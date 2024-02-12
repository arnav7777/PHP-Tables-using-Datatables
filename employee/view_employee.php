<?php
include 'db_config.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = $_GET['id'];
  $result = $conn->query("SELECT * FROM employees WHERE id=$id");
  $row = $result->fetch_assoc();
  $message = "Hi, Lets Chat!";
  $whatsapp_link = 'https://wa.me/' . $row['phone_number'] . '?text=' . urlencode($message);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Employee</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
  <h1 class="p-4 fw-light text-center bg-light">View Employee</h1><br>
  <div class="text-left m-1 p-2">
    <a href="index.php" class="btn"><span class="material-symbols-outlined">
        arrow_back
      </span></a>
  </div><br>
  <div class="container">
    <form method="" action="">
      <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="name">Name:</label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" readonly>
        </div>

        <div class="form-group col-md-6">
          <label for="phone">Phone:</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone_number']; ?>" readonly>
        </div>

      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="email">Email:</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" readonly>
        </div>
        <div class="form-group col-md-6">
          <label for="salary">Salary (in Rs.):</label>
          <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $row['salary']; ?>" readonly>
        </div>

      </div>


      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="position">Designation:</label>
          <input type="text" class="form-control" id="position" name="position" value="<?php echo $row['position']; ?>" readonly>
        </div>
        <div class="form-group col-md-6">
          <label for="date">Joining Date:</label>
          <input type="date" class="form-control" id="date" name="date" value="<?php echo $row['joining_date']; ?>" readonly>
        </div>
      </div>

      <p>Click the button below to send a WhatsApp message:</p>
      <a href="<?php echo $whatsapp_link; ?>" target="_blank">
        <button type="button" class="btn btn-success">Send WhatsApp Message</button>
      </a><br><br>
    </form>
  </div><br>

</body>

</html>