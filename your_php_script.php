<?php
include("connection.php");
// your_php_script.php

// Assuming you have a database connection established earlier in your code

$fromDate = mysqli_real_escape_string($conn, $_POST['from']);
$toDate = mysqli_real_escape_string($conn, $_POST['to']);

$query_d = "SELECT * FROM tbl_payment WHERE Payment_Date BETWEEN '$fromDate' AND '$toDate'";
$result = $conn->query($query_d);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['Payment_ID'] . '</td>';
        echo '<td>' . $row['CM_ID'] . '</td>';
        // Add more table cells as needed
        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="2" style="text-align:center">No Sales Available</td>';
    echo '</tr>';
}

// Close the database connection if necessary
$conn->close();
?>
