<?php
// Check if a return URL is provided as a query parameter
if (isset($_GET['return_url'])) {
    $return_url = urldecode($_GET['return_url']);
    // Redirect the user back to the original page
    header("Location: " . $return_url);
    exit();
} else {
    // If no return URL is provided, redirect to a default page
    header("Location: index.php");
    exit();
}
?>
