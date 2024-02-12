<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script><style>
    /* Styles for larger screens */
    .overlay-container {
        position: fixed;
        top: 25%;
        left: 25%;
        width: 50%;
        height: 50%;
        z-index: 1000;
        /* Set the z-index for the overlay */
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 999;
        /* Set the z-index just below the alert */
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .overlay-container {
            top: 10%;
            left: 10%;
            width: 80%;
            height: 80%;
        }
    }
</style>

</head>
<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'employee';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);



// Get the selected employee from the query string
$selectedEmployee = $_GET['employeeId'];

// Fetch employee details from the 'employees' table
$sql = "SELECT * FROM `employees` WHERE `employee_id` = '$selectedEmployee'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $row = $result->fetch_assoc();
    //$employeeDetails = "Employee ID: " . $row["employee_id"] . "<br>Employee Name: " . $row["name"] . "<br>Other details: " . $row["email"];
    $employeeDetails = "<div class='overlay-container'>
    <div class='overlay'></div>
    <div class='alert alert-secondary alert-dismissible fade show' role='alert' style='z-index: 1000; position: relative;'>
        <strong><h2>Employee Details : </h2><br><br><h4>Employee ID: " . $row['employee_id'] . "<br>Name: " . $row['name'] . "<br>Email: " . $row['email'] . "<br>Designation:".$row['position']."<br>Phone:".$row['phone_number']."<br>Salary:".$row['salary']."<br>Joining Date:".$row['joining_date']."<br></h4></strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
</div>";


    echo $employeeDetails;
} else {
    echo "Employee not found $selectedEmployee";
    echo $sql;
}

// Close the database connection
$conn->close();
?>