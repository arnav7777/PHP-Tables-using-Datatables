<?php
// autocomplete.php handles the autocomplete functionality

include 'db_config.php';
// Get the input query
$inputText = $_POST["query"];

// Query to retrieve suggestions
$query = "SELECT Distinct(position) FROM employees WHERE position LIKE '%$inputText%' LIMIT 5"; // Adjust the query as needed
$result = $conn->query($query);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div class='suggestion'>" . $row['position'] . "</div>";
  }
} else {
  echo "No suggestions found.";
}

// Close the database connection
$conn->close();
