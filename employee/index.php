<?php
include 'db_config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Management System</title>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> <!-- Correct URL for CSS -->

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

</head>

<body>
  <h1 class="p-4 fw-light text-center bg-light">Manage Employee</h1>
  <?php
  if (isset($_SESSION['add_employee'])) {
    echo $_SESSION['add_employee'];
    unset($_SESSION['add_employee']);
  }
  if (isset($_SESSION['edit_employee'])) {
    echo $_SESSION['edit_employee'];
    unset($_SESSION['edit_employee']);
  }
  if (isset($_SESSION['delete_employee'])) {
    echo $_SESSION['delete_employee'];
    unset($_SESSION['delete_employee']);
  }
  ?>

  <div class="d-flex justify-content-center align-items-center">
    <div class="input-group w-50 mt-2">
      <input type="text" class="form-control" placeholder="Search Employee" id="search" oninput="searchEmployees()">
    </div>
  </div>
  <div class="d-flex justify-content-center">
    <div id="results" class="dropdown-menu"></div>
  </div>
  <div id="employee-details"></div>
  <script src="script_search.js"></script>
  <br>
  <a href="add_employee.php" class="btn btn-dark btn-block m-2">Add Employee</a>

  <?php
  $result = $conn->query("SELECT * FROM employees ORDER BY employee_id DESC");

  if ($result->num_rows > 0) {
    $totalCount = $result->num_rows; ?>
    <h4 class="text-center">Total Employees : <?php echo $totalCount; ?></h4>
    <div class="table-responsive p-2 m-2">
      <table class="table table-bordered table-hover" id="employeeTable">

        <div class="d-grid gap-3 d-lg-flex justify-content-lg-start p-2 m-2">
          
          <div class="d-grid gap-2 d-lg-flex">
            <label for="startDate" class="col-form-label">Start Date:</label>
            <div class="col-md-2 col-sm-3">
              <input type="date" id="startDate" class="form-control" />
            </div>

            <label for="endDate" class="col-form-label">End Date:</label>
            <div class="col-md-2 col-sm-3">
              <input type="date" id="endDate" class="form-control" />
            </div>

            <button class="btn btn-primary" onclick="searchByDateRange()">Search</button>
          </div>
          <div class="d-grid gap-2 d-lg-flex">
            <div class="col-md-8 col-sm-5">
              <input type="text" id="salaryFilter" class="form-control" placeholder="Enter Minimum salary" />
            </div>
            <button class="btn btn-primary" onclick="searchBySalary()">Search</button>
          </div>

        </div>

        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Designation</th>
            <th>Phone</th>
            <th>Salary (in Rs.)</th>
            <th>Joining Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

        <?php
        function formatIndianCurrency($amount)
        {
          $formattedAmount = number_format((float) str_replace(',', '', $amount), 2, '.', ',');
          return $formattedAmount;
        }

        while ($row = $result->fetch_assoc()) {
          $date = new DateTime($row['joining_date']);
          $formattedDate = $date->format('d-M-Y');
          $formattedSalary = formatIndianCurrency($row['salary']);

          echo "<tr>
                    <td>{$row['employee_id']}</td>
                    <td>{$row['name']}</td>
                    <td><a href='mailto:{$row['email']}' target='_blank'>{$row['email']}</a></td>
                    <td>{$row['position']}</td>
                    <td><a href='tel:{$row['country_code']}{$row['phone_number']}'>{$row['country_code']}-{$row['phone_number']}</a></td>
                    <td class='text-end'>{$formattedSalary}</td>
                    <td>{$formattedDate}</td>
                    <td>
                        <a class='btn btn-warning btn-block' href='edit_employee.php?id={$row['id']}'><span class='material-symbols-outlined'>
                        edit
                        </span></a>
                        <a class='btn btn-dark btn-block' href='view_employee.php?id={$row['id']}'><span class='material-symbols-outlined'>
                        visibility
                        </span></a>
                        <a class='btn btn-danger btn-block' href='delete_employee.php?id={$row['id']}' onclick='return confirm(\"Confirm Delete?\")'><span class='material-symbols-outlined'>
                        delete
                        </span></a>
                    </td>
                  </tr>";
        }
      } else {
        echo "<p class='text-center'>DATA NOT AVAILABLE!</p>";
      }
        ?>
        </tbody>
      </table>
    </div>
    <!-- Include moment.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
      function searchByDateRange() {
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;

        // DataTables API method to search by date range
        $.fn.dataTable.ext.search.pop(); // Remove previous search function

        if (startDate && endDate) {
          $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
              var joiningDate = new Date(data[6]); // Assuming the Joining Date is in the 7th column
              var start = new Date(startDate);
              var end = new Date(endDate);

              // Include data from start date to end date (both inclusive)
              return start <= joiningDate && joiningDate <= end;
            }
          );
        }

        // DataTables API method to redraw the table
        $('#employeeTable').DataTable().draw();
      }

      function searchBySalary() {
        var salaryFilter = document.getElementById('salaryFilter').value;

        // DataTables API method to search by salary
        $.fn.dataTable.ext.search.pop(); // Remove previous search function

        if (salaryFilter) {
          $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
              var salary = parseFloat(data[5].replace(/[^0-9.]+/g, '')); // Assuming salary is in the 5th column

              // Include data with salary greater than the entered value
              return salary >= parseFloat(salaryFilter);
            }
          );
        }

        // DataTables API method to redraw the table
        $('#employeeTable').DataTable().draw();
      }



      $(document).ready(function() {
        $('#employeeTable').DataTable({
          columnDefs: [{
            type: 'date',
            targets: [6], // Replace 6 with the index of your date column
            render: function(data, type, row) {
              // Parse the date using moment.js and format it
              return moment(data, 'DD-MMM-YYYY').format('D-MMM-YYYY');
            }
          }],
          order: [
            [0, 'desc'] // Sort by the first column (Employee ID) in descending order 
          ],
        });
      });
    </script>


</body>

</html>