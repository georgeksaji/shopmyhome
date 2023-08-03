<?php  
$hostname = "localhost";  
$username = "root";  
$password = "";
$db_name="ohas";
//create connection
$conn = mysqli_connect($hostname, $username, $password, $db_name);  
//check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo "Connected successfully";
}
?>  