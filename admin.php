<?php
include('connection.php');

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

//dashboard box values 

$result1 = mysqli_query($conn, "SELECT * FROM tbl_staff");
$staff_num = mysqli_num_rows($result1);

$result2 = mysqli_query($conn, "SELECT * FROM tbl_courier");
$courier_num = mysqli_num_rows($result2);

$result3 = mysqli_query($conn, "SELECT * FROM tbl_vendor");
$vendor_num = mysqli_num_rows($result3);

$result4 = mysqli_query($conn, "SELECT * FROM tbl_customer");
$customer_num = mysqli_num_rows($result4);

$result5 = mysqli_query($conn, "SELECT * FROM tbl_category");
$category_num = mysqli_num_rows($result5);

$result6 = mysqli_query($conn, "SELECT * FROM tbl_type");
$type_num = mysqli_num_rows($result6);

$result7 = mysqli_query($conn, "SELECT * FROM tbl_brand");
$brand_num = mysqli_num_rows($result7);

$result8 = mysqli_query($conn, "SELECT * FROM tbl_appliance");
$appliances_num = mysqli_num_rows($result8);

$result9 = mysqli_query($conn, "SELECT * FROM tbl_purchase_master");
$purchase_num = mysqli_num_rows($result9);

$result10 = mysqli_query($conn, "SELECT * FROM tbl_cart_master WHERE Cart_Status='DELIVERED'");
$sales_num = mysqli_num_rows($result10);

$result11 = mysqli_query($conn, "SELECT * FROM tbl_payment");
$payment_num = mysqli_num_rows($result11);

//total profit
// Purchase_Child_ID 	Purchase_Master_ID 	Appliance_ID 	Quantity 	Balance_Stock 	Cost_Per_Piece 	Selling_Price 	Total_Cost_Price 	
$result12 = mysqli_query($conn, "SELECT * FROM tbl_purchase_child WHERE Quantity > Balance_Stock");
$total_profit = 0;
while ($row12 = mysqli_fetch_array($result12)) {
  $number_of_pieces = $row12['Quantity'] - $row12['Balance_Stock'];
  $thisprofit=$number_of_pieces*($row12['Selling_Price'] - $row12['Cost_Per_Piece']);
  $total_profit = $total_profit + $thisprofit;
}






//session buttons
if (isset($_POST['home'])) {
  header('location:index.php');
}
if (isset($_POST['destroy'])) {
  session_unset();
  session_destroy();
  header('location:index.php');
}
//staff status update

if (isset($_POST['update_staff'])) {
  $updateid = $_POST['staff_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Staff.php');
}
if (isset($_POST['activate_staff_status_button'])) {
  $staff_id = $_POST['staff_id'];
  mysqli_query($conn, "UPDATE tbl_staff SET Staff_Status=1 WHERE Staff_ID='$staff_id'");
}

if (isset($_POST['deactivate_staff_status_button'])) {
  $staff_id = $_POST['staff_id'];
  mysqli_query($conn, "UPDATE tbl_staff SET Staff_Status=0 WHERE Staff_ID='$staff_id'");
}
//vednor status button
if (isset($_POST['update_vendor'])) {
  $updateid = $_POST['vendor_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Vendor.php');
}
if (isset($_POST['activate_vendor_status_button'])) {
  $vendor_id = $_POST['vendor_id'];
  mysqli_query($conn, "UPDATE tbl_vendor SET Vendor_Status=1 WHERE Vendor_ID='$vendor_id'");
}

if (isset($_POST['deactivate_vendor_status_button'])) {
  $vendor_id = $_POST['vendor_id'];
  mysqli_query($conn, "UPDATE tbl_vendor SET Vendor_Status=0 WHERE Vendor_ID='$vendor_id'");
}

//courier status button
if (isset($_POST['update_cour'])) {
  $updateid = $_POST['cour_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Courier.php');
}
if (isset($_POST['activate_cour_status_button'])) {
  $cour_id = $_POST['cour_id'];
  mysqli_query($conn, "UPDATE tbl_courier SET Cour_Status=1 WHERE Cour_ID='$cour_id'");
  header("Location: success_page.php?return_url=" . urlencode($_SERVER['PHP_SELF']));
}

if (isset($_POST['deactivate_cour_status_button'])) {
  $cour_id = $_POST['cour_id'];
  $sql_ca_status = "SELECT * FROM tbl_courier_assign WHERE Courier_ID = '$cour_id' AND (Delivery_Status = 'ASSIGNED' OR Delivery_Status = 'SHIPPED')";
  $result_ca_status = mysqli_query($conn, $sql_ca_status);
  $ca_status = mysqli_num_rows($result_ca_status);
  if ($ca_status > 0) {
    echo "<script>alert('Courier cannot be deactivated as it has one or more pending deliveries.');</script>";
  } else {
    mysqli_query($conn, "UPDATE tbl_courier SET Cour_Status=0 WHERE Cour_ID='$cour_id'");
    header("Location: success_page.php?return_url=" . urlencode($_SERVER['PHP_SELF']));
  }
  //mysqli_query($conn, "UPDATE tbl_courier SET Cour_Status=0 WHERE Cour_ID='$cour_id'");
}

//cust status button
if (isset($_POST['activate_cust_status_button'])) {
  $cust_id = $_POST['cust_id'];
  mysqli_query($conn, "UPDATE tbl_customer SET Cust_Status=1 WHERE Cust_ID='$cust_id'");
}

if (isset($_POST['deactivate_cust_status_button'])) {
  $cust_id = $_POST['cust_id'];
  mysqli_query($conn, "UPDATE tbl_customer SET Cust_Status=0 WHERE Cust_ID='$cust_id'");
}
//appliance status button
if (isset($_POST['update_app'])) {
  $updateid = $_POST['app_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_appliance.php');
}
//purchase button
if (isset($_POST['purchase_app'])) {
  $updateid = $_POST['app_id'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Make_purchase.php');
}
//category status button
if (isset($_POST['update_cat'])) {
  $updateid = $_POST['cat_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Category.php');
}
//type status button
if (isset($_POST['update_type'])) {
  $updateid = $_POST['type_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Type.php');
}
//brand status button
if (isset($_POST['update_brand'])) {
  $updateid = $_POST['brand_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Brand.php');
}

//assign courier button
if (isset($_POST['assign_courier'])) {
  $pay_id = $_POST['payment_id'];
  $assigned_delivery_partner = $_POST['courier_assign_id'];
  $customer_id = $_POST['Customer_Id'];
  $cm_id = $_POST['master_cart_id'];
  $date_of_assign = $_POST['date'];
  $currentDateTime = new DateTime();
  // Add 10 days to the current date
  $currentDateTime->modify('+10 days');
  
  // Format the modified delivery date with both date and time components
  $expectedDeliveryDate = $currentDateTime->format('Y-m-d H:i:s');

  // Now $expectedDeliveryDate contains the updated date and time

  //generate_courier_assignment_id()
  //insert into tbl_courier_assign
  // 	Courier_Assign_ID 	Courier_ID 	CM_ID 	Customer_ID 	Courier_Assign_Date 
  mysqli_query($conn, "INSERT INTO tbl_courier_assign(Courier_Assign_ID,Courier_ID,CM_ID,Max_Delivery_Date,Customer_ID) VALUES(generate_courier_assignment_id(),'$assigned_delivery_partner','$cm_id','$expectedDeliveryDate','$customer_id')");

  //update Courier_Assignment_Status=1 in tbl_payment
  //mysqli_query($conn,"UPDATE tbl_payment SET Courier_Assignment_Status=1 WHERE Payment_ID='$pay_id'");
  //echo "<script>alert('Courier Assigned Successfully');</script>";
  // 	Cart_Status 	="COURIER ASSIGNED"
  mysqli_query($conn, "UPDATE tbl_cart_master SET Cart_Status='COURIER ASSIGNED' WHERE CM_ID='$cm_id'");

  header("Location: success_page.php?return_url=" . urlencode($_SERVER['PHP_SELF']));
  exit();
}
//download_sales_report_to_pdf
if(isset($_POST['download_sales_report']))
{
  header('location:Sales_report.php');
}
//download_purchase_report_to_pdf
if(isset($_POST['download_purchase_report']))
{
  header('location:Purchase_report.php');
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="favicon.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <!--google fonts_-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <script src="table2excel.js"></script>
  <style>
    body {
      background-color: rgb(255, 255, 255);
      margin: 0px;
      padding: 0px;
      overflow: hidden;
    }

    a {
      text-decoration: none;
      color: rgb(256, 256, 256);
    }

    .outer-div {
      width: 100%;
      height: 100%;
    }

    .top-navigation {
      height: 11vh;
      width: 100%;
      background-color: rgb(193, 231, 249);
      position: fixed;
    }

    .logo {
      height: 10vh;
      width: 17%;
      background-image: url('picture3.png');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
    }

    .top-navigation-list {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      float: right;
      height: 100%;
      width: 85%;
      margin-top: -5%;
      margin-left: 18%;
    }

    .top-navigation-list li {
      list-style: none;
      margin-right: 0.3%;
    }

    .buttons,
    .logout-button {
      transition: 0.3s ease-in-out;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-weight: 450;
      font-size: 14px;
      background-color: transparent;
      color: rgb(0, 0, 0);
      outline: none;
      box-shadow: none;
      border-style: none;
      border-radius: 0px;
    }

    .buttons:hover {
      background-color: rgb(0, 0, 0, 0.1);
      border-style: none;
      border-radius: 0px;
    }

    .buttons:focus {
      background-color: rgb(42, 85, 229);
      color: rgb(256, 256, 256);

    }

    .logout-button:hover {
      background-color: rgb(239 51 36);
      color: rgb(256, 256, 256);
    }

    .logout-button:focus {
      background-color: rgb(239 51 36);
      color: rgb(256, 256, 256);
    }

    .home-box:hover {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-weight: 550;
      color: rgb(0, 0, 0);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .section {
      height: 100vh;
      justify-content: center;
      align-items: center;

    }

    /*dashboard css content*/
    .dashboard-content {
      background-color: transparent;
    }

    .cards-outer {
      margin-inline-start: 5%;
      display: inline-flex;
      margin-block-start: 10%;
      height: auto;
      width: 105%;
    }

    .card-n-outer {
      height: 16%;
      width: 18%;
      padding: 1%;
      margin-inline-start: 3%;
      background-color: rgb(0, 0, 0);
      border-radius: 9px;
      transition: 0.3s ease-in-out;
    }

    .card-n-inner {
      height: 100%;
      width: 100%;
      ;
      background-color: none;
      transition: 0.3s ease-in-out;
    }

    .card-n-image {
      height: 10%;
      width: 5%;
      margin: -3.1%;
      background-size: cover;
      position: absolute;
      z-index: 1;

    }

    .card1,
    .card2,
    .card3,
    .card4 {
      height: 20%;
      width: 20%;
      background-color: rgb(177, 228, 252);
      border-radius: 9px;
      transition: 0.3s ease-in-out;
      margin: auto;
      margin-top: 20vh;
    }

    .card1 {
      background-color: rgb(249, 201, 80);
    }

    .card2 {
      background-color: rgb(240, 107, 97);
    }

    .card3 {
      background-color: rgb(66, 134, 244);
    }

    .card4 {
      background-color: rgb(103, 195, 128);
    }

    .card1:hover,
    .card2:hover,
    .card3:hover,
    .card4:hover {
      box-shadow: 0px 0px 10px 0px rgb(154, 223, 255);
      height: 21%;
      width: 21%;
    }

    .card-text-heading {
      font-size: 99%;
      color: rgb(256, 256, 256);
      font-family: serif;
      font-weight: 700;
      text-align: center;
      margin-top: 7%;
    }

    .card-text-content {
      font-size: 213%;
      color: rgb(256, 256, 256);
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      font-weight: 900;
      text-align: center;
    }

    p {
      margin-top: 0;
      margin-bottom: -0.5rem;
    }

    .staff-content,
    .vendor-content,
    .customer-content,
    .product-content,
    .courier-content,
    .category-content,
    .type-content,
    .brand-content,
    .appliances-content,
    .purchase-content,
    .sales-content,
    .assign-content {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .view_table {
      background-color: rgb(256, 256, 256);
      width: 100%;
      table-layout: auto;

    }

    .view_table tr {
      height: 35px;
    }

    .view_table th {
      text-align: center;
      position: sticky;
      top: 0;
      background-color: rgb(42, 85, 229);
      color: rgb(256, 256, 256);
    }

    .view_table th,
    td {
      margin: 1px;
      padding: 1px;
      border-color: rgb(0, 0, 0, 0.5);
      /*width:auto;
  white-space: nowrap;  Prevents text from wrapping in cells 
  overflow: hidden; /* Prevents content from overflowing cells 
  text-overflow: ellipsis; /* Shows ellipsis (...) for truncated text */
    }

    .view_table tbody tr:nth-child(odd) {
      background-color: rgb(0, 0, 0, 0.1);
      /* Set your desired background color */
      /* Set text color for better contrast */
    }

    .add_buttons,
    .add_buttons:hover,
    .add_buttons:active {
      background-color: rgb(0, 0, 0);
      color: rgb(256, 256, 256);
    }

    /*deactivate and activate buttons*/
    .deactivate_button,
    .deactivate_button:hover,
    .deactivate_button:active {
      background-color: rgb(239 51 36);
      color: rgb(256, 256, 256);
      margin: auto;
    }

    .activate_button,
    .activate_button:hover,
    .activate_button:active {
      background-color: rgb(78 198 111);
      color: rgb(256, 256, 256);
      margin: auto;
    }

    .terminated_button,
    .terminated_button:hover,
    .terminated_button:active {
      background-color: rgb(0 0 0);
      color: rgb(256, 256, 256);
      margin: auto;
    }


    /*Staff css content*/
    .staff-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 99%;
      background-color: rgb(182 232 255);
    }

    .staff-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .staff-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    /*Courier css content*/
    .courier-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 99%;
      background-color: rgb(182 232 255);

    }

    .courier-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .courier-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    /*Vendor css content*/
    .vendor-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 99%;
      background-color: rgb(182 232 255);

    }

    .vendor-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .vendor-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    /*Customer css content*/
    .customer-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 98%;
      background-color: rgb(182 232 255);
    }

    .customer-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .customer-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    /*Category css content*/
    .category-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(182 232 255);
    }

    .category-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .category-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    /*type css content*/
    .type-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(182 232 255);
    }

    .type-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .type-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    /*brand css content*/
    .brand-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(182 232 255);
    }

    .brand-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .brand-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    /*appliances css content*/
    .appliances-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 98%;
      background-color: rgb(182 232 255);
    }

    .appliances-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .appliances-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    /*purchase css content*/
    .purchase-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(182 232 255);
    }

    .purchase-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .purchase-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }


    /*assign css content*/
    .assign-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(182 232 255);
    }

    .assign-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .assign-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    #unassigned-table,
    #assigned-table {
      display: none;
    }



    /*sales css content*/
    .sales-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(55, 24, 95);
    }

    .sales-content-inner-top {
      height: 20%;
      width: 100%;
      background-color: rgb(54, 46, 212, 0.9);
      padding: 3%;
    }

    .sales-content-inner-bottom {
      padding-top: 1%;
      padding-inline: 1%;
    }

    .view_table_wrapper {
      max-height: 60vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }


    /*product css content*/
    .product-content-inner {
      margin-top: 10vh;
      height: 80vh;
      width: 90%;
      background-color: rgb(55, 24, 95);
    }
  </style>
</head>

<body>
  <!--
<script>
var jsMessage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php //echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script>
-->

  <script>
    function showTable(tableId) {
      var unassignedTable = document.getElementById('unassigned-table');
      var assignedTable = document.getElementById('assigned-table');
      var defaultTable = document.getElementById('default-table');

      if (tableId === 'unassigned-table') {
        unassignedTable.style.display = 'table';
        assignedTable.style.display = 'none';
        defaultTable.style.display = 'none';
      } else if (tableId === 'assigned-table') {
        unassignedTable.style.display = 'none';
        assignedTable.style.display = 'table';
        defaultTable.style.display = 'none';
      }

      // If neither button is pressed, show the default table
      if (tableId !== 'unassigned-table' && tableId !== 'assigned-table') {
        unassignedTable.style.display = 'none';
        assignedTable.style.display = 'none';
        defaultTable.style.display = 'table';
      }
    }
  </script>


  <div class="outer-div">

    <div class="top-navigation">
      <div class="logo"></div>
      <ul class="top-navigation-list">
        <form action="admin.php" method="POST" style="margin-block:auto">
          <li><button type="submit" class="btn btn-primary buttons" name="home">HOME</button></li>
        </form>
        <li><button type="button" class="btn btn-primary buttons" id="buttonToClick"
            onclick="scrollToSection('.dashboard-content')">DASHBOARD</button></li>
        <li> <button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.staff-content')">STAFFS</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.courier-content')">COURIERS</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.vendor-content')">VENDORS</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.customer-content')">CUSTOMERS</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.category-content')">CATEGORY</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.type-content')">TYPE</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.brand-content')">BRAND</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.appliances-content')">APPLIANCE</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.purchase-content')">PURCHASES</button></li>
        <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.assign-content')">ASSIGN
            COURIER</button></li>
        <li><button type="button" class="btn btn-primary buttons"
            onclick="scrollToSection('.sales-content')">SALES</button></li>
        <form action="admin.php" method="POST" style="margin-block:auto; ">
          <li><button type="submit" class="btn btn-primary logout-button" name="destroy">LOGOUT</button></li>
        </form>
      </ul>
    </div>

    <!--dashboard content goes here-->
    <div class="dashboard-content section">
      <div class="cards-outer">
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/courier.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Courier Partners</p>
            <p class="card-text-content">
              <?php echo $courier_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/vendor.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Vendors</p>
            <p class="card-text-content">
              <?php echo $vendor_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/customer.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Customers</p>
            <p class="card-text-content">
              <?php echo $customer_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/staff.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Staffs</p>
            <p class="card-text-content">
              <?php echo $staff_num ?>
            </p>
          </div>
        </div>
      </div>
      <div class="cards-outer" style="margin-block-start: 3%;">
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/category.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Categories</p>
            <p class="card-text-content">
              <?php echo $category_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/type.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Types</p>
            <p class="card-text-content">
              <?php echo $type_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/brand.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Brands</p>
            <p class="card-text-content">
              <?php echo $brand_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/appliance.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Appliances</p>
            <p class="card-text-content">
              <?php echo $appliances_num ?>
            </p>
          </div>
        </div>
      </div>
      <div class="cards-outer" style="margin-block-start: 3%;">
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/purchase.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Purchases</p>
            <p class="card-text-content">
              <?php echo $purchase_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/paid.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Paid Orders</p>
            <p class="card-text-content">
              <?php echo $payment_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/delivered.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Total Deliveries</p>
            <p class="card-text-content">
              <?php echo $sales_num ?>
            </p>
          </div>
        </div>
        <div class="card-n-outer">
          <div class="card-n-image" style="background-image: url('./dashboard_icons/profit.gif');"></div>
          <div class="card-n-inner">
            <p class="card-text-heading">Profits Earned</p>
            <p class="card-text-content">
            <?php echo "₹" . $total_profit; ?>
            </p>
          </div>
        </div>


        <!--<div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/sales.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Sales</p><p class="card-text-content"><?php echo $sales_num ?></p></div></div>
      -->
      </div>


      <!--<div class="card2"><p class="card-text-heading">TOTAL COURIER PARTNERS</p><p class="card-text-content"><?php echo $courier_num ?></p></div>
          <div class="card3"><p class="card-text-heading">Total Vendors</p><p class="card-text-content"><?php echo $vendor_num ?></p></div>
          <div class="card4"><p class="card-text-heading">Total Customers</p><p class="card-text-content"><?php echo $customer_num ?></p></div>
      -->
    </div>
  </div><!--dashboard content completed-->

  <!--staff content goes here-->
  <div class="staff-content section">
    <div class="staff-content-inner">

      <div class="staff-content-inner-top">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end"><form method='POST'>
       <button class="btn btn-primary me-md-2 add_buttons" type="button" id="downloadtoexcel_staff">Download Excel</button>
          <a href="Register_Staff.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Staff</button></a>
  </form>
        </div>
      </div>
      <div class="staff-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class='table-bordered table-striped view_table'>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Full&nbspName</th>
              <th>Designation</th>
              <th>Salary</th>
              <th>Phone</th>
              <th>House</th>
              <th>Street</th>
              <th>District</th>
              <th>State</th>
              <th>Pincode</th>
              <th>Gender</th>
              <th>Joining Date</th>
              <th>Status</th>
              <th colspan="2">Action</th>
            </tr>
            <?php
            // Assuming you have an SQL query stored in the $result2 variable
            
            $query = "SELECT * FROM tbl_staff"; // Replace with your actual query
            $staff_num2 = $conn->query($query);

            if ($staff_num2) {
              while ($row_s = $staff_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_s['Staff_ID'] . "</td>";
                echo "<td>" . $row_s['Staff_Username'] . "</td>";
                echo "<td>" . $row_s['Staff_Fname'] . " " . $row_s['Staff_Lname'] . "</td>";
                echo "<td>" . $row_s['Staff_Designation'] . "</td>";
                echo "<td>" . $row_s['Staff_Salary'] . "</td>";
                echo "<td>" . $row_s['Staff_Phone'] . "</td>";
                echo "<td>" . $row_s['Staff_Hname'] . "</td>";
                echo "<td>" . $row_s['Staff_Street'] . "</td>";
                echo "<td>" . $row_s['Staff_Dist'] . "</td>";
                echo "<td>" . $row_s['State_Ut'] . "</td>";
                echo "<td>" . $row_s['Staff_Pin'] . "</td>";
                echo "<td>" . $row_s['Staff_Gender'] . "</td>";
                echo "<td>" . $row_s['Sjoining_Date'] . "</td>";
                if ($row_s['Staff_Status'] == 1) {
                  echo "<td>Active</td>";
                } else {
                  echo "<td>Inactive</td>";
                }


                //update status button
                if ($row_s['Staff_Status'] == 1) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='staff_id' value='" . $row_s['Staff_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 deactivate_button' name='deactivate_staff_status_button'>DEACTIVATE</button></form></td>";
                }
                if ($row_s['Staff_Status'] == 0) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='staff_id' value='" . $row_s['Staff_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 activate_button' name='activate_staff_status_button'>ACTIVATE</button></form></td>";
                }
                //edit staff button
                echo '<td><form action="admin.php" method="POST"><input type="hidden" name="staff_updateid" value="' . $row_s['Staff_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_staff">UPDATE</button></form></td>';
                echo "</tr>";
              }
            } else {
              echo "No data available.";
            }

            // Close the database connection
            $staff_num2->close();
            ?>


          </table>

          <table class='table-bordered table-striped view_table' id='staff-table' style="display:none;">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Full&nbspName</th>
              <th>Designation</th>
              <th>Salary</th>
              <th>Phone</th>
              <th>House</th>
              <th>Street</th>
              <th>District</th>
              <th>State</th>
              <th>Pincode</th>
              <th>Gender</th>
              <th>Joining Date</th>
              <th>Status</th>
            </tr>
            <?php
            // Assuming you have an SQL query stored in the $result2 variable
            
            $query = "SELECT * FROM tbl_staff"; // Replace with your actual query
            $staff_num2 = $conn->query($query);

            if ($staff_num2) {
              while ($row_s = $staff_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_s['Staff_ID'] . "</td>";
                echo "<td>" . $row_s['Staff_Username'] . "</td>";
                echo "<td>" . $row_s['Staff_Fname'] . " " . $row_s['Staff_Lname'] . "</td>";
                echo "<td>" . $row_s['Staff_Designation'] . "</td>";
                echo "<td>" . $row_s['Staff_Salary'] . "</td>";
                echo "<td>" . $row_s['Staff_Phone'] . "</td>";
                echo "<td>" . $row_s['Staff_Hname'] . "</td>";
                echo "<td>" . $row_s['Staff_Street'] . "</td>";
                echo "<td>" . $row_s['Staff_Dist'] . "</td>";
                echo "<td>" . $row_s['State_Ut'] . "</td>";
                echo "<td>" . $row_s['Staff_Pin'] . "</td>";
                echo "<td>" . $row_s['Staff_Gender'] . "</td>";
                echo "<td>" . $row_s['Sjoining_Date'] . "</td>";
                if ($row_s['Staff_Status'] == 1) {
                  echo "<td>Active</td>";
                } else {
                  echo "<td>Inactive</td>";
                }
              }
            } else {
              echo "No data available.";
            }

            // Close the database connection
            $staff_num2->close();
            ?>
          </table>

        </div>
      </div>
    </div>
  </div>
  <!--staff content completed-->

  <!--vendor content goes here-->
  <div class="vendor-content section">
    <div class="vendor-content-inner">

      <div class="vendor-content-inner-top">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          
       <button class="btn btn-primary me-md-2 add_buttons" type="button" id="downloadtoexcel_vendor">Download Excel</button>
          <a href="Register_Vendor.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add
              Vendors</button></a>
        </div>
      </div>
      <div class="vendor-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table" id="vendor-table">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Courier Name</th>
              <th>Registrant Staff ID</th>
              <th>Phone</th>
              <th>Building</th>
              <th>Street</th>
              <th>District</th>
              <th>State</th>
              <th>Pincode</th>
              <th>Joining Date</th>
              <th>Status</th>
              <th colspan="2">Action</th>
            </tr>
            <?php
            // Assuming you have an SQL query stored in the $result2 variable
            $query = "SELECT * FROM tbl_vendor"; // Replace with your actual query
            
            $vendor_num2 = $conn->query($query);

            if ($vendor_num2) {
              while ($row_v = $vendor_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_v['Vendor_ID'] . "</td>";
                echo "<td>" . $row_v['Vendor_Email'] . "</td>";
                echo "<td>" . $row_v['Vendor_Name'] . "</td>";
                echo "<td>" . $row_v['Staff_Id'] . "</td>";
                echo "<td>" . $row_v['Vendor_Phno'] . "</td>";
                echo "<td>" . $row_v['Vendor_Hname'] . "</td>";
                echo "<td>" . $row_v['Vendor_Street'] . "</td>";
                echo "<td>" . $row_v['Vendor_Dist'] . "</td>";
                echo "<td>" . $row_v['State_Ut'] . "</td>";
                echo "<td>" . $row_v['Vendor_Pin'] . "</td>";
                echo "<td>" . $row_v['Vjoining_Date'] . "</td>";
                if ($row_v['Vendor_Status'] == 1) {
                  echo "<td>Active</td>";
                } else {
                  echo "<td>Inactive</td>";
                }

                //update status button
            
                if ($row_v['Vendor_Status'] == 1) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='vendor_id' value='" . $row_v['Vendor_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 deactivate_button' name='deactivate_vendor_status_button'>DEACTIVATE</button></form></td>";
                }
                if ($row_v['Vendor_Status'] == 0) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='vendor_id' value='" . $row_v['Vendor_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 activate_button' name='activate_vendor_status_button'>ACTIVATE</button></form></td>";
                }
                //edit vendor button
                echo '<td><form action="admin.php" method="POST"><input type="hidden" name="vendor_updateid" value="' . $row_v['Vendor_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_vendor">UPDATE</button></form></td>';
                echo "</tr>";





              }
            } else {
              echo "No data available.";
            }

            // Close the database connection
            $vendor_num2->close();
            ?>

          </table>
        </div>
      </div>
    </div>
  </div>
  <!--vendor content completed-->

  <!--courier content goes here-->
  <div class="courier-content section">
    <div class="courier-content-inner">


      <div class="courier-content-inner-top">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          
       <button class="btn btn-primary me-md-2 add_buttons" type="button" id="downloadtoexcel_cour">Download Excel</button>
          <a href="Register_Courier.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Courier
              Partners</button></a>
        </div>
      </div>
      <div class="courier-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table" id="cour-table">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Courier Name</th>
              <th>Registrant Staff ID</th>
              <th>Phone</th>
              <th>Building</th>
              <th>Street</th>
              <th>District</th>
              <th>State</th>
              <th>Pincode</th>
              <th>Joining Date</th>
              <th>Status</th>
              <th colspan="2">Action</th>
            </tr>
            <?php
            // Assuming you have an SQL query stored in the $result2 variable
            $query = "SELECT * FROM tbl_courier"; // Replace with your actual query
            
            $courier_num2 = $conn->query($query);

            if ($courier_num2) {
              while ($row_c = $courier_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_c['Cour_ID'] . "</td>";
                echo "<td>" . $row_c['Cour_Username'] . "</td>";
                echo "<td>" . $row_c['Cour_Name'] . "</td>";
                echo "<td>" . $row_c['Staff_ID'] . "</td>";
                echo "<td>" . $row_c['Cour_Phone'] . "</td>";
                echo "<td>" . $row_c['Cour_Building_name'] . "</td>";
                echo "<td>" . $row_c['Cour_Street'] . "</td>";
                echo "<td>" . $row_c['Cour_Dist'] . "</td>";
                echo "<td>" . $row_c['Cour_State_ut'] . "</td>";
                echo "<td>" . $row_c['Cour_Pin'] . "</td>";
                echo "<td>" . $row_c['Cour_Joining_Date'] . "</td>";
                if ($row_c['Cour_Status'] == 1) {
                  echo "<td>Active</td>";
                } else if ($row_c['Cour_Status'] == 3) {
                  echo "<td>Terminated</td>";
                }
                else if ($row_c['Cour_Status'] == 2) {
                  echo "<td>Awaiting Termination</td>";
                }
                 else if ($row_c['Cour_Status'] == 0) {
                  echo "<td>Inactive</td>";
                }

                //update status button
            
                if ($row_c['Cour_Status'] == 1) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='cour_id' value='" . $row_c['Cour_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 deactivate_button' name='deactivate_cour_status_button'>DEACTIVATE</button></form></td>";
                }
                if ($row_c['Cour_Status'] == 0) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='cour_id' value='" . $row_c['Cour_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 activate_button' name='activate_cour_status_button'>ACTIVATE</button></form></td>";
                }
                if ($row_c['Cour_Status'] == 2) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><button type='submit' class='btn btn-primary me-md-2 terminated_button' disabled>ACTIVE</button></form></td>";
                }
                if ($row_c['Cour_Status'] == 3) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><button type='submit' class='btn btn-primary me-md-2 terminated_button' disabled>TERMINATED</button></form></td>";
                }
                //edit cour button
                echo '<td><form action="admin.php" method="POST"><input type="hidden" name="cour_updateid" value="' . $row_c['Cour_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_cour">UPDATE</button></form></td>';
                echo "</tr>";


              }
            } else {
              echo "No data available.";
            }

            // Close the database connection
            $courier_num2->close();
            ?>

          </table>
          
        </div>
      </div>
    </div>
  </div>

  <!--courier content completed-->

  <!--customer content goes here-->
  <div class="customer-content section">
    <div class="customer-content-inner">

      <div class="customer-content-inner-top">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          
       <button class="btn btn-primary me-md-2 add_buttons" type="button" id="downloadtoexcel_cust">Download Excel</button>
        </div>
      </div>
      <div class="customer-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table" id="cust-table">
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Full&nbspName</th>
              <th>Phone</th>
              <th>House</th>
              <th>Street</th>
              <th>District</th>
              <th>State</th>
              <th>Pincode</th>
              <th>Gender</th>
              <th>Joining Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
            <?php

            $query_customer = "SELECT * FROM tbl_customer";
            $customer_num2 = $conn->query($query_customer);

            if ($customer_num2) {
              while ($row_c = $customer_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_c['Cust_ID'] . "</td>";
                echo "<td>" . $row_c['C_Username'] . "</td>";
                echo "<td>" . $row_c['Cust_Fname'] . " " . $row_c['Cust_Lname'] . "</td>";
                echo "<td>" . $row_c['Cust_Phone'] . "</td>";
                echo "<td>" . $row_c['Cust_Hname'] . "</td>";
                echo "<td>" . $row_c['Cust_Street'] . "</td>";
                echo "<td>" . $row_c['Cust_Dist'] . "</td>";
                echo "<td>" . $row_c['State_Ut'] . "</td>";
                echo "<td>" . $row_c['Cust_Pin'] . "</td>";
                echo "<td>" . $row_c['Cust_Gender'] . "</td>";
                echo "<td>" . $row_c['Cust_Date'] . "</td>";

                if ($row_c['Cust_Status'] == 1) {
                  echo "<td>Active</td>";
                } else {
                  echo "<td>Inactive</td>";
                }
                //update status button
            
                if ($row_c['Cust_Status'] == 1) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='cust_id' value='" . $row_c['Cust_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 deactivate_button' name='deactivate_cust_status_button'>DEACTIVATE</button></form></td>";
                }
                if ($row_c['Cust_Status'] == 0) {
                  echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='cust_id' value='" . $row_c['Cust_ID'] . "'><button type='submit' class='btn btn-primary me-md-2 activate_button' name='activate_cust_status_button'>ACTIVATE</button></form></td>";
                }
                echo "</tr>";



              }
            } else {
              echo "No data available.";
            }

            // Close the database connection
            $customer_num2->close();
            ?>

          </table>
        </div>
      </div>
    </div>
  </div>
  <!--customer content completed-->

  <!--category content goes here-->
  <div class="category-content section">
    <div class="category-content-inner">
      <div class="category-content-inner-top">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <a href="Add_category.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add
              Category</button></a>
        </div>
      </div>
      <div class="category-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table">
            <tr>
              <th>Category ID</th>
              <th>Category Name</th>
              <th>Action</th>
            </tr>
            <?php

            $query_category = "SELECT * FROM tbl_category";
            $category_num2 = $conn->query($query_category);

            if ($category_num2) {
              while ($row_cat = $category_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_cat['Cat_ID'] . "</td>";
                echo "<td>" . $row_cat['Cat_Name'] . "</td>";
                echo '<td style="text-align: center;"><form action="admin.php" method="POST"><input type="hidden" name="cat_updateid" value="' . $row_cat['Cat_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_cat">UPDATE</button></form></td>';
                echo "</tr>";
              }
            } else {
              echo "No data available.";
            }


            // Close the database connection
            $category_num2->close();
            ?>

          </table>
        </div>
      </div>
    </div>
  </div>
  <!--category content completed-->

  <!--type content goes here-->
  <div class="type-content section">
    <div class="type-content-inner">
      <div class="type-content-inner-top">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="width: 9%;float: right;">
          <a href="Add_type.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Type</button></a>
        </div>
      </div>
      <div class="type-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table">
            <tr>
              <th>Type ID</th>
              <th>Category ID</th>
              <th>Type Name</th>
              <th>Action</th>
            </tr>
            <?php
            $query_type = "SELECT * FROM tbl_type";
            $type_num2 = $conn->query($query_type);

            if ($type_num2) {
              while ($row_typ = $type_num2->fetch_assoc()) {

                echo "<tr>";
                echo "<td>" . $row_typ['Type_ID'] . "</td>";
                echo "<td>" . $row_typ['Cat_ID'] . "</td>";
                echo "<td>" . $row_typ['Type_Name'] . "</td>";
                echo '<td style="text-align: center;"><form action="admin.php" method="POST"><input type="hidden" name="type_updateid" value="' . $row_typ['Type_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_type">UPDATE</button></form></td>';
                echo "</tr>";
              }
            }
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--type content completed-->

  <!--brand content goes here-->
  <div class="brand-content section">
    <div class="brand-content-inner">
      <div class="brand-content-inner-top">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="width: 9%;float: right;">
          <a href="Add_brand.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add
              Brand</button></a>
        </div>
      </div>
      <div class="brand-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table">
            <tr>
              <th>Brand ID</th>
              <th>Brand Name</th>
              <th>Brand Image</th>
              <th>Action</th>
            </tr>
            <?php
            $query_type = "SELECT * FROM tbl_brand";
            $brand_num2 = $conn->query($query_type);

            if ($brand_num2) {
              while ($row_br = $brand_num2->fetch_assoc()) {
                $brand_image = $row_br['Brand_Logo'];

                echo "<tr>";
                echo "<td>" . $row_br['Brand_ID'] . "</td>";
                echo "<td>" . $row_br['Brand_Name'] . "</td>";
                echo "<td><img src='" . $brand_image . "' alt='Brand Logo' height='60px' width='60px'></td>";
                echo '<td style="text-align: center;"><form action="admin.php" method="POST"><input type="hidden" name="brand_updateid" value="' . $row_br['Brand_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_brand">UPDATE</button></form></td>';
                echo "</tr>";
              }
            }
            $brand_num2->close();
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--brand content completed-->
  <!--appliances content goes here-->
  <div class="appliances-content section">
    <div class="appliances-content-inner">
      <div class="staff-content-inner-top">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <a href="Add_appliance1.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add
              Appliance</button></a>
        </div>
      </div>
      <div class="staff-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table">
            <tr>
              <th>Appliance ID</th>
              <th>Appliance Name</th>
              <th>Appliance Type</th>
              <th>Appliance Brand</th>
              <th colspan="2">Images</th>
              <th>Profit Percentage</th>
              <th>Description</th>
              <th colspan="2">Action</th>
            </tr>
            <?php

            // Assuming you have an SQL query stored in the $result2 variable
            
            $query = "SELECT * FROM tbl_appliance"; // Replace with your actual query
            $app_num2 = $conn->query($query);

            if ($app_num2) {
              while ($row_app = $app_num2->fetch_assoc()) {
                $image1 = $row_app['Appliance_Image1'];
                $image2 = $row_app['Appliance_Image2'];
                echo "<tr>";
                echo "<td>" . $row_app['Appliance_ID'] . "</td>";
                echo "<td>" . $row_app['Appliance_Name'] . "</td>";
                //echo "<td>" . $row_app['Type_ID'] . "</td>";
                $app_type=$row_app['Type_ID'];
                $sql_type="SELECT Type_Name FROM tbl_type WHERE Type_ID='$app_type'";
                $result_type=$conn->query($sql_type);
                $row_type=$result_type->fetch_assoc();
                //remove last letter from string
                $row_type['Type_Name']=substr($row_type['Type_Name'],0,-1);
                echo "<td>" . $row_type['Type_Name'] . "</td>";
                $app_brand=$row_app['Brand_ID'];
                $sql_brand="SELECT Brand_Name FROM tbl_brand WHERE Brand_ID='$app_brand'";
                $result_brand=$conn->query($sql_brand);
                $row_brand=$result_brand->fetch_assoc();
                echo "<td>" . $row_brand['Brand_Name'] . "</td>";
                echo "<td><img src='" . $image1 . "' alt='Image 1' height='50px' width='45px'></td>";
                echo "<td><img src='" . $image2 . "' alt='Image 2' height='50px' width='45px'></td>";
                echo "<td>" . $row_app['Appliance_Profit_Percentage'] . "</td>";
                echo "<td>" . $row_app['Appliance_Description'] . "</td>";


                //edit staff button
                echo '<td><form action="admin.php" method="POST"><input type="hidden" name="app_updateid" value="' . $row_app['Appliance_ID'] . '"><button type="submit" class="btn btn-primary me-md-2" name="update_app">UPDATE</button></form></td>';
                echo "</tr>";
              }
            } else {
              echo "No data available.";
            }

            // Close the database connection
            $app_num2->close();
            ?>


          </table>
        </div>
      </div>





    </div>
  </div>
  <!--appliances content completed-->

  <!--purchase content goes here-->
  <div class="purchase-content section">
    <div class="purchase-content-inner">
      <div class="purchase-content-inner-top">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="float: right;">
          <a href="Make_Purchase1.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Make
              Purchase</button></a>
              <form method='POST'><button class="btn btn-primary me-md-2 add_buttons" type="button" id="downloadtoexcel_purchases">Download Excel</button>
              <button class="btn btn-primary me-md-2 add_buttons" type="submit" name="download_purchase_report">Download pdf</button>
          </form>
        </div>
      </div>
      <div class="purchase-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table" id="purchase-table">
            <tr>
              <th>Purchase ID</th>
              <th>Vendor ID</th>
              <th>Staff ID</th>
              <th>Total Cost</th>
              <th>Purchase Date</th>
            </tr>
            <?php
            $query = "SELECT * FROM tbl_purchase_master"; // Replace with your actual query
            $purchase_num2 = $conn->query($query);
            if ($purchase_num2) {
              while ($row_pur = $purchase_num2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_pur['Purchase_Master_ID'] . "</td>";
                echo "<td>" . $row_pur['Vendor_ID'] . "</td>";
                echo "<td>" . $row_pur['Staff_ID'] . "</td>";
                echo "<td>" . $row_pur['Total_Amt'] . "</td>";
                echo "<td>" . $row_pur['Purchase_Date'] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "No data available.";
            }
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--purchase content completed-->

  <!--assign courier goes here-->




  <div class="assign-content section" id="assign-courier-id">
    <div class="assign-content-inner">
      <div class="assign-content-inner-top">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <form method="POST" style="float: right;height:100%;widht:100%;">
            <!--<button class="btn btn-primary me-md-2 add_buttons" type="submit" name="Unassigned">Unassigned Orders</button>-->
            <button class="btn btn-primary me-md-2 add_buttons" type="button"
              onclick="showTable('unassigned-table')">Unassigned Orders</button>
            <!--<button class="btn btn-primary me-md-2 add_buttons" type="submit" name="Assigned">Assigned Orders</button>-->
            <button class="btn btn-primary me-md-2 add_buttons" type="button"
              onclick="showTable('assigned-table')">Assigned Orders</button>

          </form>
        </div>
      </div>
      <div class="assign-content-inner-bottom">

        <table class="table-bordered table-striped view_table" id="default-table">
          <tr>
            <td colspan="6" style="text-align:center;background-color:rgb(255 46 46);color:rgb(256 256 256)">Select
              "Unassigned Orders" or "Assigned Orders" to view the respective tables.</td>
          </tr>
        </table>
        <div class="view_table_wrapper">
          <?php
          //Unassigned table
          echo '<table class="table-bordered table-striped view_table" id="unassigned-table">';
          echo '<tr>';
          echo '<th>Cart Master ID</th>';
          echo '<th>Payment ID</th>';
          echo '<th>Customer ID</th>';
          echo '<th>Purchase Date</th>';
          echo '<th>Delivery Address</th>';
          echo '<th colspan="2">Assign Delivery Partner</th>';
          echo '</tr>';

          //delivery partner
          /*$sql_list_all_delivery_partner = "SELECT Cour_ID,Cour_Name FROM tbl_courier WHERE Cour_Status=1";
          $result_list_all_delivery_partner = $conn->query($sql_list_all_delivery_partner);
          $delivery_partners = array();
          while ($row_list_all_delivery_partner = $result_list_all_delivery_partner->fetch_assoc()) {
            $delivery_partners[] = $row_list_all_delivery_partner;
          }*/
          //select * from tbl_cart_master where  Cart_Status 	=PAID
         // $query = "SELECT * FROM tbl_payment WHERE CM_ID IN (SELECT CM_ID FROM tbl_cart_master WHERE Cart_Status='PAID' OR Cart_Status='REASSIGNED' )";
       

          $query = "SELECT * FROM tbl_payment WHERE CM_ID IN (SELECT CM_ID FROM tbl_cart_master WHERE (Cart_Status='PAID' OR Cart_Status='REASSIGNED'))";

          // Replace with your actual query
          $payment_num2 = $conn->query($query);
          //if ($payment_num2) 
          if ($payment_num2->num_rows > 0) {
            while ($row_pay = $payment_num2->fetch_assoc()) {
              $payment_id = $row_pay['Payment_ID'];
              $cart_master_id = $row_pay['CM_ID'];
              $date = $row_pay['Payment_Date'];
              //customer details
              $sql_customer_details = "SELECT Customer_ID FROM tbl_cart_master WHERE CM_ID='$cart_master_id'";
              $result_customer_details = $conn->query($sql_customer_details);
              $row_customer_details = $result_customer_details->fetch_assoc();
              $customer_id = $row_customer_details['Customer_ID'];
              //customer address
              $sql_customer_address = "SELECT Cust_Hname,Cust_Street,Cust_Dist,State_Ut,Cust_Pin FROM tbl_customer WHERE Cust_ID='$customer_id'";
              $result_customer_address = $conn->query($sql_customer_address);
              $row_customer_address = $result_customer_address->fetch_assoc();
              $customer_address = $row_customer_address['Cust_Hname'] . ", " . $row_customer_address['Cust_Street'] . ", " . $row_customer_address['Cust_Dist'] . ", " . $row_customer_address['State_Ut'] . ", " . $row_customer_address['Cust_Pin'];

              echo "<tr>";
              echo "<td>" . $cart_master_id . "</td>"; 
              echo "<td>" . $payment_id . "</td>";
              echo "<td>" . $customer_id . "</td>";
              echo "<td>" . $date . "</td>";
              echo "<td>" . $customer_address . "</td>";
              echo "<td><form action='' method='POST'><input type='hidden' name='payment_id' value='" . $payment_id . "'><select name='courier_assign_id' id='courier_id' style='width: 100%;' required><option value=''>Select Delivery Partner</option>";
              //select all courier partners from tabl courier with status 1
              //select * from tbl_courier where Cour_Status=1 and Cour_ID not in (select Courier_ID from tbl_courier_assign where CM_ID='$cart_master_id' and Delivery_Status='REASSIGNED')
              $sql_list_all_delivery_partner = "SELECT Cour_ID,Cour_Name FROM tbl_courier WHERE Cour_Status=1 AND Cour_ID NOT IN (SELECT Courier_ID FROM tbl_courier_assign WHERE CM_ID='$cart_master_id' AND Delivery_Status='REASSIGNED');";
              $result_list_all_delivery_partner = $conn->query($sql_list_all_delivery_partner);
              $delivery_partners = array();
              while ($row_list_all_delivery_partner = $result_list_all_delivery_partner->fetch_assoc()) {
                $delivery_partners[] = $row_list_all_delivery_partner;
              }
              //option all in array
              foreach ($delivery_partners as $delivery_partner) {
                echo "<option value='" . $delivery_partner['Cour_ID'] . "'>" . $delivery_partner['Cour_Name'] . "</option>";
              }
              
              
              
              
              
              // $sql_list_all_delivery_partner = "SELECT Cour_ID,Cour_Name FROM tbl_courier WHERE Cour_Status=1";
              // $result_list_all_delivery_partner = $conn->query($sql_list_all_delivery_partner);
              // $delivery_partners = array();
              // while ($row_list_all_delivery_partner = $result_list_all_delivery_partner->fetch_assoc()) {
              //   $delivery_partners[] = $row_list_all_delivery_partner;
              // }
              
              // foreach ($delivery_partners as $delivery_partner) 
              // {
              //   //select Courier_ID from tbl_courier_assign where CM_ID='$cart_master_id' and Delivery_Status='REASSIGNED'
              //   $sql_check_reassigned = "SELECT DISTINCT (Courier_ID) FROM tbl_courier_assign WHERE CM_ID='$cart_master_id' AND Delivery_Status='REASSIGNED';";
              //   $result_check_reassigned = $conn->query($sql_check_reassigned);
              //   $row_check_reassigned = $result_check_reassigned->fetch_assoc();

                
              //   if(mysqli_num_rows($result_check_reassigned)>0)
              //   {
              //   if ($row_check_reassigned['Courier_ID'] == $delivery_partner['Cour_ID'])
              //   {
              //     continue;
              //   }
              //   else
              //   {
              //   echo "<option value='" . $delivery_partner['Cour_ID'] . "'>" . $delivery_partner['Cour_Name'] . "</option>";
              //   }
              //   }
              //   else if(mysqli_num_rows($result_check_reassigned)==0)
              //   {
              //     echo "<option value='" . $delivery_partner['Cour_ID'] . "'>" . $delivery_partner['Cour_Name'] . "</option>";
              //   }
              // }
              echo "</select></td><input type='hidden' name='master_cart_id' value='" . $cart_master_id . "'><input type='hidden' name='date' value='" . $date . "'><input type='hidden' name='Customer_Id' value='" . $customer_id . "'>";
              echo "<td><button type='submit' class='btn btn-primary me-md-2' name='assign_courier'>ASSIGN</button></form></td>";
              echo "</tr>";
            }

          } else {
            echo '<tr>';
            echo '<td colspan="6" style="text-align:center">No Unassigned Orders Available</td>';
            echo '</tr>';
          }


          echo '</table>';



          //Assigned table
          echo '<table class="table-bordered table-striped view_table" id="assigned-table">';
          echo '<tr>';
          echo '<th>Customer ID</th>';
          echo '<th>Cart Master ID</th>';
          echo '<th>Payment ID</th>';
          echo '<th>Delivery Partner</th>';
          echo '<th>Purchase Date</th>';
          //courier assign date
          echo '<th>Courier Assign Date</th>';
          echo '<th>Expected Delivery Before</th>'; //10 days from courier assign date
          echo '<th>Status</th>';
          echo '</tr>';
          //$query = "SELECT * FROM tbl_payment WHERE Courier_Assignment_Status=1";
          //$query = "SELECT * FROM tbl_payment WHERE CM_ID IN (SELECT CM_ID FROM tbl_cart_master WHERE Cart_Status='COURIER ASSIGNED' OR Cart_Status='SHIPPED')";
          //select * from tbl_cart master where cart sttaus is courier assigned or shipped
          // Replace with your actual query
          $query = "SELECT * FROM tbl_cart_master WHERE Cart_Status='COURIER ASSIGNED' OR Cart_Status='SHIPPED'";
          $unassigned_num2 = $conn->query($query);
          //if ($payment_num2)
          if ($unassigned_num2->num_rows > 0) {
            while ($row_unassign = $unassigned_num2->fetch_assoc()) {
              $ca_master_id = $row_unassign['CM_ID'];
              $customer_id = $row_unassign['Customer_ID'];
              //customer details
              $sql_payment_details = "SELECT Payment_ID,Payment_Date FROM tbl_payment WHERE CM_ID='$ca_master_id'";
              $result_pay_details = $conn->query($sql_payment_details);
              $row_pay_details = $result_pay_details->fetch_assoc();
              $payment_id = $row_pay_details['Payment_ID'];
              //payment date
              $date = $row_pay_details['Payment_Date'];

              //customer address
              $sql_customer_address = "SELECT Cust_Hname,Cust_Street,Cust_Dist,State_Ut,Cust_Pin FROM tbl_customer WHERE Cust_ID='$customer_id'";
              $result_customer_address = $conn->query($sql_customer_address);
              $row_customer_address = $result_customer_address->fetch_assoc();
              $customer_address = $row_customer_address['Cust_Hname'] . ", " . $row_customer_address['Cust_Street'] . ", " . $row_customer_address['Cust_Dist'] . ", " . $row_customer_address['State_Ut'] . ", " . $row_customer_address['Cust_Pin'];
              //delivery partner
              $sql_delivery_partner = "SELECT * FROM tbl_courier_assign WHERE CM_ID='$ca_master_id' AND (Delivery_Status = 'ASSIGNED' OR Delivery_Status = 'SHIPPED')";
              $result_delivery_partner = $conn->query($sql_delivery_partner);
              $row_delivery_partner = $result_delivery_partner->fetch_assoc();
              $delivery_partner_id = $row_delivery_partner['Courier_ID'];
              $delivery_partner_assign_date = $row_delivery_partner['Courier_Assign_Date'];
              $expectedDeliveryDate = $row_delivery_partner['Max_Delivery_Date'];
              $delivery_status = $row_delivery_partner['Delivery_Status'];
              //delivery partner name
              $sql_delivery_partner_name = "SELECT Cour_Name FROM tbl_courier WHERE Cour_ID='$delivery_partner_id'";
              $result_delivery_partner_name = $conn->query($sql_delivery_partner_name);
              $row_delivery_partner_name = $result_delivery_partner_name->fetch_assoc();
              $delivery_partner_name = $row_delivery_partner_name['Cour_Name'];
              //expected delivery date including time
              //$assignDateTime = new DateTime($delivery_partner_assign_date);
              //$assignDateTime->modify('+10 days');
              //$expectedDeliveryDate = $assignDateTime->format('Y-m-d H:i:s');
          
              echo "<tr>";
              
              echo "<td>" . $customer_id . "</td>";
              echo "<td>" . $ca_master_id . "</td>";
              echo "<td>" . $payment_id . "</td>";
              echo "<td>" . $delivery_partner_name . "</td>";
              echo "<td>" . $date . "</td>";
              echo "<td>" . $delivery_partner_assign_date . "</td>";
              echo "<td>" . $expectedDeliveryDate . "</td>";
              if ($delivery_status == 'DELIVERED') {
                echo "<td style='color:green'>" . $delivery_status . "</td>";
              } else {
                echo "<td style='color:red'>" . $delivery_status . "</td>";
              }
              echo "</tr>";

            }

          } else {
            echo '<tr>';
            echo '<td colspan="8" style="text-align:center">No Assigned Orders pending for delivery</td>';
            echo '</tr>';

          }
          echo '</table>';






          ?>

        </div>
      </div>


    </div>
  </div>
  <!--assign courier content completed-->

  <!--sales content goes here-->
  <div class="sales-content section">
    <div class="purchase-content-inner">
      <div class="purchase-content-inner-top">

      <!-- <form id="sales-filter-form" style="width: fit-content;float: left;">
    <label for="from">From</label>
    <input type="date" id="from" name="from" value="<?php echo date('Y-m-d'); ?>">
    <label for="to">To</label>
    <input type="date" id="to" name="to" value="<?php echo date('Y-m-d'); ?>">
    <button type="submit" class="btn btn-primary" name="sales_report">Generate Report</button>
</form> -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="float: right;"> 
        <form method='POST'>
            <button class="btn btn-primary me-md-2 add_buttons" type="button" id="downloadtoexcel_sales">Download Excel</button>
            <button class="btn btn-primary me-md-2 add_buttons" type="submit" name="download_sales_report">Download pdf</button>
          </form>

        </div>
      </div>
      <div class="purchase-content-inner-bottom">
        <div class="view_table_wrapper">
          <table class="table-bordered table-striped view_table" id="sales-report-table">
            <thead>
            <tr>
              <th>Payment ID</th>
              <th>Cart Master ID</th>
              <th>Customer ID</th>
              <th>Order Date</th>
              <th>Order Amount</th>
              <th>Order Status</th>

            </tr>
        </thead>
            <?php
            $query_d = "SELECT * FROM tbl_payment"; // Replace with your actual query
            $delivery_num2 = $conn->query($query_d);
            if (mysqli_num_rows($delivery_num2) > 0) {
              while ($row_d = $delivery_num2->fetch_assoc()) {
                $payment_id = $row_d['Payment_ID'];
                $delivered_cart_id = $row_d['CM_ID'];
                $order_date = $row_d['Payment_Date'];
                //cart master details
                $sql_cart_master_details = "SELECT Customer_ID,Total_Amount,Cart_Status FROM tbl_cart_master WHERE CM_ID='$delivered_cart_id'";
                $result_cart_master_details = $conn->query($sql_cart_master_details);
                $row_cart_master_details = $result_cart_master_details->fetch_assoc();
                // 	CM_ID 	Customer_ID 	Total_Amount 	Cart_Status 	
                $customer_id = $row_cart_master_details['Customer_ID'];
                $total_amount = $row_cart_master_details['Total_Amount'];
                $cart_status = $row_cart_master_details['Cart_Status'];

                echo '<tr>';
                echo '<td>' . $payment_id . '</td>';
                echo '<td>' . $delivered_cart_id . '</td>';
                echo '<td>' . $customer_id . '</td>';
                echo '<td>' . $order_date . '</td>';
                echo '<td>' . $total_amount . '</td>';
                echo '<td>' . $cart_status . '</td>';
               echo '</tr>';

              }
            } else {
              echo '<tr>';
              echo '<td colspan="6" style="text-align:center">No Sales Available</td>';
              echo '</tr>';
            }
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--sales content completed-->




  </div>
  <script type="text/js" src="js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"></script>
  <script>
    function scrollToSection(selector) {
      const section = document.querySelector(selector);
      if (section) {
        window.scrollTo({ top: section.offsetTop, behavior: 'smooth' });
      }
    };

    document.addEventListener("DOMContentLoaded", function () {
      var buttonToClick = document.getElementById("buttonToClick");
      buttonToClick.click();
      buttonToClick.focus()
    });

    function submitForm() {
      document.getElementById("typeForm").submit();
    }

    function scrollToSection(selector) {
      var element = document.querySelector(selector);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
  }
  //download sales report to excel
  document.getElementById('downloadtoexcel_sales').addEventListener('click', function(){
    //var table2excel = new Table2Excel();
    var table2excel = new Table2Excel({
        // Set the filename of the Excel file
        defaultFileName: 'Sales Report SMH',
    });
  table2excel.export(document.querySelectorAll("#sales-report-table"));
  });

  //download purchase report  to excel
  document.getElementById('downloadtoexcel_purchases').addEventListener('click', function(){
    //var table2excel = new Table2Excel();
    var table2excel = new Table2Excel({
        // Set the filename of the Excel file
        defaultFileName: 'Purchase Report SMH',
    });
  table2excel.export(document.querySelectorAll("#purchase-table"));
  });

  //download courier report  to excel
  document.getElementById('downloadtoexcel_cour').addEventListener('click', function(){
    //var table2excel = new Table2Excel();
    var table2excel = new Table2Excel({
        // Set the filename of the Excel file
        defaultFileName: 'Courier Report SMH',
    });
  table2excel.export(document.querySelectorAll("#cour-table"));
  });
  //download customer report  to excel
  document.getElementById('downloadtoexcel_cust').addEventListener('click', function(){
    //var table2excel = new Table2Excel();
    var table2excel = new Table2Excel({
        // Set the filename of the Excel file
        defaultFileName: 'Customer Report SMH',
    });
  table2excel.export(document.querySelectorAll("#cust-table"));
  });
  //download customer report  to excel
  document.getElementById('downloadtoexcel_vendor').addEventListener('click', function(){
    //var table2excel = new Table2Excel();
    var table2excel = new Table2Excel({
        // Set the filename of the Excel file
        defaultFileName: 'Vendor Report SMH',
    });
  table2excel.export(document.querySelectorAll("#vendor-table"));
  });
  //download staff report  to excel
  document.getElementById('downloadtoexcel_staff').addEventListener('click', function(){
    var table2excel = new Table2Excel({
   // Set the filename of the Excel file
 defaultFileName: 'Staff Report SMH', });
    table2excel.export(document.querySelectorAll("#staff-table"));
}); // Missing closing brace for addEventListener function



// document.getElementById('sales-filter-form').addEventListener('submit', function(event) {
//     event.preventDefault();

//     var fromDate = document.getElementById('from').value;
//     var toDate = document.getElementById('to').value;

//     fetch('your_php_script.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         body: 'from=' + fromDate + '&to=' + toDate,
//     })
//     .then(function(response) {
//         return response.text();
//     })
//     .then(function(data) {
//         // Clear old rows from the table
//         document.getElementById('sales-report-table-body').innerHTML = '';

//         // Add new rows to the table
//         document.getElementById('sales-report-table-body').innerHTML = data;
//     });
// });


</script>
</body>

</html>