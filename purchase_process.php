<?php
include("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
if(isset($_POST['submit']))
{
  $vendor = $_POST['vendor'];
  $appliance_name = $_POST['new_appliance_name'];
  $quantity = $_POST['new_quantity'];
  $price = $_POST['new_price'];
  $total_price_of_item = array();
  $count = count($appliance_name);
  for($i=0;$i<$count;$i++)
  {
    echo $total_price_of_item[$i]=$quantity[$i] * $price[$i];
  }
  $total_price = array_sum($total_price_of_item);
  // 	Purchase_Master_ID 	Vendor_ID 	Staff_ID 	Total_Amt 	Purchase_Date
  $sql = "INSERT INTO tbl_purchase_master (Purchase_Master_ID, Vendor_ID, Staff_ID, Total_Amt) VALUES (generate_purchase_master_id(), '$vendor', '$userId', '$total_price')"; 
  $result = mysqli_query($conn, $sql);
  //get purchase master id
  $sql = "SELECT Purchase_Master_ID FROM tbl_purchase_master WHERE Staff_ID='$userId' ORDER BY Purchase_Date DESC LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $purchase_master_id = $row['Purchase_Master_ID'];
  echo $purchase_master_id;
  // 	Purchase_Child_ID 	Purchase_Master_ID 	Appliance_ID 	Quantity 	Cost_Per_Piece 	Total_Cost_Price
  for($i=0;$i<$count;$i++)
  {
    $sql = "INSERT INTO tbl_purchase_child (Purchase_Child_ID, Purchase_Master_ID, Appliance_ID, Quantity, Cost_Per_Piece, Total_Cost_Price) VALUES (generate_purchase_child_id(), '$purchase_master_id', '$appliance_name[$i]', '$quantity[$i]', '$price[$i]', '$total_price_of_item[$i]')";
    $result = mysqli_query($conn, $sql);  
    //update Stock in tbl_appliance
    $sql = "UPDATE tbl_appliance SET Stock = Stock + '$quantity[$i]' WHERE Appliance_ID = '$appliance_name[$i]'";
    $result = mysqli_query($conn, $sql);
  } 
  if($result)
  {
    echo "<script>alert('Purchase made successfully!');</script>";
    header("location: admin.php");
  }
}
?>
