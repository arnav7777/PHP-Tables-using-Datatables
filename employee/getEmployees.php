<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'employee';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Get the user input from the query string
$query = $_GET['query'];

// Fetch employees from the 'employees' table based on the user's input
$sql = "SELECT * FROM employees WHERE name LIKE '%$query%' OR email LIKE '%$query%'";
$result = $conn->query($sql);

$employees = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = array(
            'employee_id' => $row['employee_id'],
            'name' => $row['name'],
            'email' => $row['email']
            // Add other fields as needed
        );
    }
}

// Close the database connection
$conn->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($employees);
?>
