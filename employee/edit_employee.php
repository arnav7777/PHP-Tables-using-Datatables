<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $position = htmlspecialchars($_POST['position']);
  $phone = $_POST['phone'];
  $salary = $_POST['salary'];
  $joining_date = $_POST['date'];
  $country_code = $_POST['country_code'];
  session_start();
  $_SESSION['edit_employee'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>$name</strong> details has been successfully changed.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>";
  $conn->query("UPDATE employees SET name='$name', position='$position', phone_number='$phone', salary='$salary', joining_date='$joining_date',country_code='$country_code' WHERE id=$id");

  header('Location: index.php');
  exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM employees WHERE id=$id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Employee</title>
</head>

<body>
  <h1 class="p-4 fw-light text-center bg-light">Edit Employee</h1><br>
  <div class="text-left m-1 p-2">
    <a href="index.php" class="btn"><span class="material-symbols-outlined">
        arrow_back
      </span></a>
  </div><br>
  <form method="post" action="" class="container">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name" class="form-label">Name:<span style="color: red;">*</span></label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
        </div><br>

        <div class="form-group">
          <label for="email" class="form-label">Email:</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" disabled>
        </div><br>

        <div class="form-group">
          <label for="position" class="form-label">Designation:<span style="color: red;">*</span></label>
          <input type="text" class="form-control" id="position" name="position" value="<?php echo $row['position']; ?>" required>
          <i class="p-1" id="suggestions-container"></i><br>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="phone" class="form-label">Phone:<span style="color: red;">*</span></label>
          <div class="input-group">
            <select id="country_code" name="country_code" class="input-group-text">
              <option value="+91" <?php echo ($row['country_code'] == '+91') ? 'selected' : ''; ?>>+91 (India)</option>
              <option value="+1" <?php echo ($row['country_code'] == '+1') ? 'selected' : ''; ?>>+1 (United States)</option>
              <option value="+44" <?php echo ($row['country_code'] == '+44') ? 'selected' : ''; ?>>+44 (United Kingdom)</option>
              <option value="+86" <?php echo ($row['country_code'] == '+86') ? 'selected' : ''; ?>>+86 (China)</option>
              <option value="+81" <?php echo ($row['country_code'] == '+81') ? 'selected' : ''; ?>>+81 (Japan)</option>
              <option value="+49" <?php echo ($row['country_code'] == '+49') ? 'selected' : ''; ?>>+49 (Germany)</option>
              <option value="+33" <?php echo ($row['country_code'] == '+33') ? 'selected' : ''; ?>>+33 (France)</option>
              <option value="+7" <?php echo ($row['country_code'] == '+7') ? 'selected' : ''; ?>>+7 (Russia)</option>
              <option value="+61" <?php echo ($row['country_code'] == '+61') ? 'selected' : ''; ?>>+61 (Australia)</option>
              <option value="+55" <?php echo ($row['country_code'] == '+55') ? 'selected' : ''; ?>>+55 (Brazil)</option>
              <option value="+20" <?php echo ($row['country_code'] == '+20') ? 'selected' : ''; ?>>+20 (Egypt)</option>
              <option value="+234" <?php echo ($row['country_code'] == '+234') ? 'selected' : ''; ?>>+234 (Nigeria)</option>
              <option value="+92" <?php echo ($row['country_code'] == '+92') ? 'selected' : ''; ?>>+92 (Pakistan)</option>
              <option value="+52" <?php echo ($row['country_code'] == '+52') ? 'selected' : ''; ?>>+52 (Mexico)</option>
              <option value="+27" <?php echo ($row['country_code'] == '+27') ? 'selected' : ''; ?>>+27 (South Africa)</option>
              <option value="+82" <?php echo ($row['country_code'] == '+82') ? 'selected' : ''; ?>>+82 (South Korea)</option>
              <option value="+46" <?php echo ($row['country_code'] == '+46') ? 'selected' : ''; ?>>+46 (Sweden)</option>
              <option value="+34" <?php echo ($row['country_code'] == '+34') ? 'selected' : ''; ?>>+34 (Spain)</option>
              <option value="+358" <?php echo ($row['country_code'] == '+358') ? 'selected' : ''; ?>>+358 (Finland)</option>
              <!-- Add more country codes and set the 'selected' attribute based on your data -->
            </select>
            <input type="number" class="form-control" id="phone" name="phone" value="<?php echo $row['phone_number']; ?>" max="9999999999" required>
          </div>
        </div><br>

        <div class="form-group">
          <label for="salary" class="form-label">Salary(in Rs.):<span style="color: red;">*</span></label>
          <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $row['salary']; ?>" required>
        </div><br>

        <div class="form-group">
          <label for="date" class="form-label">Joining Date:<span style="color: red;">*</span></label>
          <input type="date" class="form-control" id="date" name="date" value="<?php echo $row['joining_date']; ?>" required>
        </div><br>
      </div>
    </div><br>

    <button type="submit" class="btn btn-primary">Update Employee</button>
  </form>

  <script>
    // Function to handle position input
    $(document).ready(function() {
      $("#position").on("input", function() {
        var inputText = $(this).val();

        if (inputText.length >= 3) {
          $.ajax({
            type: "POST",
            url: "autocomplete.php",
            data: {
              query: inputText
            },
            success: function(data) {
              $("#suggestions-container").html(data);
            }
          });
        } else {
          $("#suggestions-container").html("");
        }
      });

      // Handle click on suggestion
      $(document).on("click", ".suggestion", function() {
        var suggestion = $(this).text();
        $("#position").val(suggestion);
        $("#suggestions-container").html("");
      });
    });
  </script>
</body>

</html>