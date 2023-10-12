<?php
include 'connection.php';







$find_status="SELECT Delivery_Error_Status,Max_Delivery_Date,CM_ID FROM tbl_courier_assign WHERE Courier_Assign_ID='CR00002'";
$result=mysqli_query($conn,$find_status);
$row=mysqli_fetch_assoc($result);
if ($row['Delivery_Error_Status'] <= 2) {
  $delivery_date = $row['Max_Delivery_Date'];
  $current_date = date("Y-m-d H:i:s");

  if ($delivery_date == $current_date) {
      $delivery_date = new DateTime($delivery_date);
      $delivery_date->modify('+1 days');
      $new_delivery_date = $delivery_date->format('Y-m-d H:i:s');

      $update_query = "UPDATE tbl_courier_assign SET Max_Delivery_Date='$new_delivery_date' WHERE Courier_Assign_ID='$cour_id'";
      mysqli_query($conn, $update_query);
  }
}