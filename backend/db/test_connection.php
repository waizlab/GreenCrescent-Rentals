<?php
include 'db_connect.php'; // Include the connection

// Test query: fetch all users
$sql = "SELECT uid, name, email, role FROM users";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<h2>Users Table:</h2><ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row['uid'] . " - " . $row['name'] . " - " . $row['email'] . " (" . $row['role'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "No users found.";
    }
} else {
    echo "Query failed: " . $conn->error;
}

// Close connection
$conn->close();
?>
