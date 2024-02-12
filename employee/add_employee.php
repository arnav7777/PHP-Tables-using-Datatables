<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $position = htmlspecialchars($_POST['position']);
  $phone = $_POST['phone'];
  $salary = $_POST['salary'];
  $joining_date = $_POST['date'];
  $country_code = $_POST['country_code'];

  $result = $conn->query("SELECT MAX(employee_id) AS max_id FROM employees");
  $row = $result->fetch_assoc();
  $maxId = $row['max_id'];

  // Increment the numeric part
  $numericPart = intval(substr($maxId, 2)) + 1;
  $newEmployeeId = 'CS' . str_pad($numericPart, 5, '0', STR_PAD_LEFT);

  $conn->query("INSERT INTO employees (employee_id,name, email, position,phone_number, salary,joining_date,country_code) VALUES ('$newEmployeeId','$name', '$email', '$position', '$phone', '$salary', '$joining_date','$country_code')");

  session_start();
  $_SESSION['add_employee'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Employee has been successfully registered.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>";

  header('Location: index.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Employee</title>
</head>

<body class="m-1">
  <h1 class="p-4 fw-light text-center bg-light">Add Employee</h1><br>
  <div class="text-left m-1 p-2">
    <a href="index.php" class="btn"><span class="material-symbols-outlined">
        arrow_back
      </span></a>
  </div><br>
  <form method="post" action="" class="container" id="employeeForm" onsubmit="return checkEmailAndSubmit()">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name" class="form-label">Name:<span style="color: red;">*</span></label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div><br>

        <div class="form-group">
          <label for="email" class="form-label">Email:<span style="color: red;">*</span></label>
          <input type="email" class="form-control" id="email" name="email" required>
          <span id="emailErrorMessage" style="color: red;padding:2px;"></span>
        </div>

        <div class="form-group">
          <label for="position" class="form-label">Designation:<span style="color: red;">*</span></label>
          <input type="text" class="form-control" id="position" name="position" autocomplete required>
        </div><i class="p-1" id="suggestions-container"></i><br>


      </div>

      <div class="col-md-6">
        <label for="phone" class="form-label">Phone:<span style="color: red;">*</span></label>
        <div class="input-group">
          <select id="country_code" name="country_code" class="input-group-text">
            <option value="+91">+91 (India)</option>
            <option value="+1">+1 (United States)</option>
            <option value="+44">+44 (United Kingdom)</option>
            <option value="+86">+86 (China)</option>
            <option value="+81">+81 (Japan)</option>
            <option value="+49">+49 (Germany)</option>
            <option value="+33">+33 (France)</option>
            <option value="+7">+7 (Russia)</option>
            <option value="+61">+61 (Australia)</option>
            <option value="+55">+55 (Brazil)</option>
            <option value="+20">+20 (Egypt)</option>
            <option value="+234">+234 (Nigeria)</option>
            <option value="+92">+92 (Pakistan)</option>
            <option value="+52">+52 (Mexico)</option>
            <option value="+27">+27 (South Africa)</option>
            <option value="+82">+82 (South Korea)</option>
            <option value="+46">+46 (Sweden)</option>
            <option value="+34">+34 (Spain)</option>
            <option value="+358">+358 (Finland)</option>
          </select>
          <input type="number" class="form-control" id="phone" name="phone" max="9999999999" required>
        </div><br>


        <div class="form-group">
          <label for="salary" class="form-label">Salary (in Rs.):<span style="color: red;">*</span></label>
          <input type="number" class="form-control" id="salary" name="salary" required>
        </div><br>

        <div class="form-group">
          <label for="salary" class="form-label">Joining Date:<span style="color: red;">*</span></label>
          <input type="date" class="form-control" id="date" name="date" required>
        </div><br>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Add Employee</button>
  </form>



  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    $(document).ready(function() {
      // Function to handle position input
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

    function checkEmailAndSubmit() {
      // Get the email value
      var email = $("#email").val();

      // Use AJAX to check if email exists
      $.ajax({
        type: "POST",
        url: "check_email.php",
        data: {
          email: email
        },
        success: function(response) {
          if (response.emailExists) {
            // Display the error message
            $("#emailErrorMessage").text("Email Already Exists! Try another email Id.");
            $("#emailError").fadeIn();
          } else {
            // Clear the error message
            $("#emailErrorMessage").text("");
            $("#emailError").fadeOut();

            // Proceed with form submission
            $("#employeeForm")[0].submit();
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error("AJAX Error:", textStatus, errorThrown);
        }
      });

      // Prevent the default form submission
      return false;
    }
  </script>

</body>

</html>