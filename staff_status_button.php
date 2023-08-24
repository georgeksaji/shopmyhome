<?php

$status = "SELECT Staff_Status FROM tbl_staff WHERE Staff_ID = '$userId'";
$staff_result = $conn->query($status);
$staff_status = $staff_result->fetch_assoc();

if(isset($_POST['activatestatus_button']))
{
  $updatestatus=mysqli_query($conn,"UPDATE tbl_staff SET Staff_Status=1 WHERE Staff_ID='$userId'");
}
if(isset($_POST['deactivatestatus_button']))
{
    $updatestatus=mysqli_query($conn,"UPDATE tbl_staff SET Staff_Status=0 WHERE Staff_ID='$userId'");
}
?>