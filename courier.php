
<?php
include('connection.php');

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
//$user_name=//select Cour_ID tbl_courier where Cour_Username='$userId'
$user_id2="SELECT Cour_ID FROM tbl_courier WHERE Cour_Username='$userId'";
$result=mysqli_query($conn,$user_id2);
$row=mysqli_fetch_assoc($result);
$user_number=$row['Cour_ID'];

//dashboard box values 

//$result1=mysqli_query($conn,"SELECT * FROM tbl_staff");
//$staff_num=mysqli_num_rows($result1);
//courier_delivered
$result2=mysqli_query($conn,"SELECT * FROM tbl_courier_assign WHERE Delivery_Status='DELIVERED' AND Courier_ID='$user_number'");
$courier_delivered=mysqli_num_rows($result2);
//courier_joined date 
//select * from tbl_courier
$result3=mysqli_query($conn,"SELECT * FROM tbl_courier WHERE Cour_ID='$user_number'");
$row3=mysqli_fetch_assoc($result3);
//Cour_Joining_Date
$courier_joined=$row3['Cour_Joining_Date'];


//pending_delivery
$result4=mysqli_query($conn,"SELECT * FROM tbl_courier_assign WHERE Delivery_Status='NOT DELIVERED' AND Courier_ID='$user_number'");	
$pending_delivery=mysqli_num_rows($result4);







//session buttons
if(isset($_POST['home']))
{
  header('location:index.php');
}
if(isset($_POST['destroy']))
{
  session_unset();
  session_destroy();
  header('location:index.php');
}
//staff status update

if(isset($_POST['update_staff']))
{
  $updateid = $_POST['staff_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Staff.php');
}
if(isset($_POST['activate_staff_status_button']))
{
    $staff_id = $_POST['staff_id'];
    mysqli_query($conn,"UPDATE tbl_staff SET Staff_Status=1 WHERE Staff_ID='$staff_id'");
}

if(isset($_POST['deactivate_staff_status_button']))
{
    $staff_id = $_POST['staff_id'];
    mysqli_query($conn,"UPDATE tbl_staff SET Staff_Status=0 WHERE Staff_ID='$staff_id'");
}
//vednor status button
if(isset($_POST['update_vendor']))
{
  $updateid = $_POST['vendor_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Vendor.php');
}
if(isset($_POST['activate_vendor_status_button']))
{
    $vendor_id = $_POST['vendor_id'];
    mysqli_query($conn,"UPDATE tbl_vendor SET Vendor_Status=1 WHERE Vendor_ID='$vendor_id'");
}

if(isset($_POST['deactivate_vendor_status_button']))
{
    $vendor_id = $_POST['vendor_id'];
    mysqli_query($conn,"UPDATE tbl_vendor SET Vendor_Status=0 WHERE Vendor_ID='$vendor_id'");
}

//courier status button
if(isset($_POST['update_cour']))
{
  $updateid = $_POST['cour_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Courier.php');
}
if(isset($_POST['activate_cour_status_button']))
{
    $cour_id = $_POST['cour_id'];
    mysqli_query($conn,"UPDATE tbl_courier SET Cour_Status=1 WHERE Cour_ID='$cour_id'");
}

if(isset($_POST['deactivate_cour_status_button']))
{
    $cour_id = $_POST['cour_id'];
    mysqli_query($conn,"UPDATE tbl_courier SET Cour_Status=0 WHERE Cour_ID='$cour_id'");
}

//cust status button
if(isset($_POST['activate_cust_status_button']))
{
    $cust_id = $_POST['cust_id'];
    mysqli_query($conn,"UPDATE tbl_customer SET Cust_Status=1 WHERE Cust_ID='$cust_id'");
}

if(isset($_POST['deactivate_cust_status_button']))
{
    $cust_id = $_POST['cust_id'];
    mysqli_query($conn,"UPDATE tbl_customer SET Cust_Status=0 WHERE Cust_ID='$cust_id'");
}
//appliance status button
if(isset($_POST['update_app']))
{
  $updateid = $_POST['app_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_appliance.php');
}
//purchase button
if(isset($_POST['purchase_app']))
{
  $updateid = $_POST['app_id'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Make_purchase.php');
}
//category status button
if(isset($_POST['update_cat']))
{
  $updateid = $_POST['cat_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Category.php');
}
//type status button
if(isset($_POST['update_type']))
{
  $updateid = $_POST['type_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Type.php');
}
//brand status button
if(isset($_POST['update_brand']))
{
  $updateid = $_POST['brand_updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Brand.php');
}
//accept courier button
if(isset($_POST['ship_courier']))
{
  $cour_id = $_POST['courier_assign_id'];
  //Accept_CA_Status
  $this__cour_id = $cour_id;
  
  mysqli_query($conn,"UPDATE tbl_courier_assign SET Delivery_Status='SHIPPED' WHERE Courier_Assign_ID='$cour_id'");

  mysqli_query($conn,"UPDATE tbl_cart_master SET Cart_Status='SHIPPED' WHERE CM_ID IN(SELECT CM_ID FROM tbl_courier_assign WHERE Courier_Assign_ID='$cour_id')");


  header("Location: success_page.php?return_url=" . urlencode($_SERVER['PHP_SELF']));
  exit();
  }
//delivered consignment button
/*if(isset($_POST['accept_courier']))
{
  $cour_id = $_POST['courier_assign_id'];
  //Accept_CA_Status
  mysqli_query($conn,"UPDATE tbl_courier_assign SET Accept_CA_Status=1 WHERE Courier_Assign_ID='$cour_id'");
  echo "<script>alert('Consignment Accepted for delivery')</script>";
}*/

//unreachable consignment button
if(isset($_POST['unreachable']))
{
  $cour_id = $_POST['courier_assign_id'];
  
  //Accept_CA_Status
  //mysqli_query($conn,"UPDATE tbl_courier_assign SET Delivery_Status='UNREACHABLE' WHERE Courier_Assign_ID='$cour_id'");
  
  //$cour_id = $_POST['courier_assign_id'];
  // 	Delivery_Error_Status+1 if and only if <=3
  $find_status="SELECT Delivery_Error_Status,Max_Delivery_Date FROM tbl_courier_assign WHERE Courier_Assign_ID='$cour_id'";
  $result=mysqli_query($conn,$find_status);
  $row=mysqli_fetch_assoc($result);
  if($row['Delivery_Error_Status']<=2)
  {
    $delivery_date=$row['Max_Delivery_Date'];
    $delivery_date = new DateTime($delivery_date);
    $delivery_date->modify('+3 days');
    // Format the modified date with time to 'Y-m-d H:i:s' format
    $new_delivery_date = $delivery_date->format('Y-m-d H:i:s');
    mysqli_query($conn,"UPDATE tbl_courier_assign SET Delivery_Error_Status=Delivery_Error_Status+1,Max_Delivery_Date='$new_delivery_date' WHERE Courier_Assign_ID='$cour_id'");    
  echo "<script>alert('Consignment Unreachable')</script>"; 
  }
  else if($row['Delivery_Error_Status']>2)
  {
   echo "<script>alert('Maximum Limit Reached. You cannot extend delivery date any further.')</script>";
  }
  header("Location: success_page.php?return_url=" . urlencode($_SERVER['PHP_SELF']));
  exit();
}

//delivered consignment button
if(isset($_POST['delivered']))
{
  $cour_id = $_POST['courier_assign_id'];
  //cart_m_id
  $delivered_cart_id = $_POST['cart_m_id'];
  mysqli_query($conn,"UPDATE tbl_courier_assign SET Delivery_Status='DELIVERED' WHERE Courier_Assign_ID='$cour_id'");
  // 	Delivery_ID 	Courier_Assign_ID 	CM_ID 	Delivery_Date 
  $insert_delivery_details="INSERT INTO tbl_delivery(Delivery_ID,Courier_Assign_ID,CM_ID) VALUES(generate_delivery_id(),'$cour_id','$delivered_cart_id')";
  mysqli_query($conn,$insert_delivery_details);
  //update Cart_Status 	in tbl_cart_master to DELIVERED
  mysqli_query($conn,"UPDATE tbl_cart_master SET Cart_Status='DELIVERED' WHERE CM_ID='$delivered_cart_id'");
  echo "<script>alert('Consignment Delivered')</script>";
  header("Location: success_page.php?return_url=" . urlencode($_SERVER['PHP_SELF']));
  exit();
}


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!--google fonts_--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <style>
body
{
background-color: rgb(255, 255, 255);
margin:0px;
padding:0px;
overflow:hidden;
}
a
{
  text-decoration: none;
  color: rgb(256,256,256);
}
.outer-div
{
    width:100%;
    height:100%;
}

.top-navigation
{
    height: 11vh;
    width:100%;
    background-color:rgb(193, 231, 249);
    position: fixed;
}
.logo
{
    height: 10vh;
    width:17%;
    background-image:url('picture3.png');
    background-size: cover;
}
.top-navigation-list
{
    display: flex;
    justify-content: flex-end;
    align-items: center;
    float: right;
    height: 100%;
    width: 85%;
    margin-top:-5%;
    margin-left: 18%;
}
.top-navigation-list li
{
    list-style: none;
    margin-right: 0.3%;
}
.buttons,.logout-button
{
  transition: 0.3s ease-in-out;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 450;
  font-size: 14px;
  background-color:transparent;
  color:rgb(0,0,0);
  outline: none;
  box-shadow: none;
  border-style:none;
  border-radius: 0px;
}
.buttons:hover
{
  background-color:rgb(0,0,0,0.1);
  border-style:none;
  border-radius: 0px;
}
.buttons:focus
{
  background-color:rgb(42,85,229);
  color:rgb(256,256,256);

}
.logout-button:hover
{
  background-color:rgb(239 51 36);
  color:rgb(256,256,256);
}
.logout-button:focus
{
  background-color:rgb(239 51 36);
  color:rgb(256,256,256);
}

.home-box:hover
{
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 550;
  color:rgb(0,0,0);
  display: flex;
  justify-content: center;
  align-items: center;
}

.section
{
  height:100vh;
  justify-content: center;
  align-items: center;

}

/*dashboard css content*/
.dashboard-content
{
  background-color:transparent;
}
.cards-outer
{
    margin-inline-start: 5%;
    display: inline-flex;
    margin-block-start: 8%;
    height: auto;
    width: 105%;
}

.card-n-outer
{
    height: 16%;
    width: 18%;
    padding: 1%;
    margin-inline-start: 3%;
    background-color:rgb(0,0,0);
    border-radius: 9px;
    transition: 0.3s ease-in-out;
}
.card-n-inner
{
    height: 100%;
    width: 100%;;
    background-color:none;
    transition: 0.3s ease-in-out;
}
.card-n-image{
  height: 10%;
    width: 5%;
    margin: -3.1%;
    background-size: cover;
    position: absolute;
    z-index: 1;

}
.card1,.card2,.card3,.card4
{
    height: 20%;
    width: 20%;
    background-color:rgb(177, 228, 252);
    border-radius: 9px;
    transition: 0.3s ease-in-out;
    margin: auto;
    margin-top: 20vh;
}
.card1
{
  background-color:rgb(249, 201, 80);
}
.card2
{
  background-color:rgb(240,107,97);
}
.card3
{
  background-color:rgb(66,134,244);
}
.card4
{
  background-color:rgb(103,195,128);
}
.card1:hover,.card2:hover,.card3:hover,.card4:hover
{
    box-shadow: 0px 0px 10px 0px rgb(154, 223, 255);
    height:21%;
    width:21%;
}
.card-text-heading
{
  font-size: 99%;
    color: rgb(256,256,256);
    font-family: serif;
    font-weight: 700;
    text-align: center;
    margin-top: 7%;
}
.card-text-content
{
  font-size: 213%;
    color: rgb(256,256,256);
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    font-weight: 900;
    text-align: center;
}
p{
margin-top: 0;
margin-bottom: -0.5rem;
}

.staff-content,.vendor-content,.customer-content,
.product-content,.courier-content,.category-content,
.type-content,.brand-content,.appliances-content,
.purchase-content,.sales-content,.assign-content{
  display: flex;
  justify-content: center;
  align-items: center;
}
.view_table
{
  background-color: rgb(256,256,256);
  width:100%;
  table-layout:auto;
  
}
.view_table tr{
  height: 35px;
}
.view_table th{
text-align:center;
position: sticky;
top: 0;
background-color:rgb(42,85,229);
color:rgb(256,256,256);
}
.view_table th,td
{
  margin: 1px;
  padding: 1px;
  border-color: rgb(0, 0, 0, 0.5);
  /*width:auto;
  white-space: nowrap;  Prevents text from wrapping in cells 
  overflow: hidden; /* Prevents content from overflowing cells 
  text-overflow: ellipsis; /* Shows ellipsis (...) for truncated text */
}
.view_table tbody tr:nth-child(odd) {
  background-color: rgb(0,0,0,0.1); /* Set your desired background color */
/* Set text color for better contrast */
}
.add_buttons,.add_buttons:hover,.add_buttons:active
{
  background-color:rgb(0,0,0);
  color:rgb(256,256,256);
}
/*deactivate and activate buttons*/
.deactivate_button,.deactivate_button:hover,.deactivate_button:active
{
  background-color:rgb(239 51 36);
  color:rgb(256,256,256);
  margin:auto;
}
.activate_button,.activate_button:hover,.activate_button:active
{
  background-color:rgb(78 198 111);
  color:rgb(256,256,256);
  margin:auto;
}


/*Staff css content*/
.staff-content-inner
{   
margin-top: 10vh;
height: 80vh;
width: 99%;
background-color:rgb(182 232 255);
}
.staff-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.staff-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}

/*Courier css content*/
.courier-content-inner
{   
  margin-top: 10vh;
  height: 80vh;
  width: 99%;
  background-color:rgb(182 232 255);
  
}
.courier-content-inner-top{
  height:20%;
  width:100%;
  background-color:rgb(54, 46, 212,0.9);
  padding:3%;
}
.courier-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}

/*Vendor css content*/
.vendor-content-inner
{   
  margin-top: 10vh;
  height: 80vh;
  width: 99%;
  background-color:rgb(182 232 255);
  
}
.vendor-content-inner-top{
  height:20%;
  width:100%;
  background-color:rgb(54, 46, 212,0.9);
  padding:3%;
}
.vendor-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
/*Customer css content*/
.customer-content-inner
{   
margin-top: 10vh;
height: 80vh;
width: 98%;
background-color:rgb(182 232 255); 
}
.customer-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.customer-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}

/*Category css content*/
.category-content-inner
{
  margin-top: 10vh;
  height: 80vh;
  width: 90%;
  background-color:rgb(182 232 255);
}
.category-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.category-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}

/*type css content*/
.type-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color:rgb(182 232 255);
}
.type-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.type-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}
/*brand css content*/
.brand-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(182 232 255);
}
.brand-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.brand-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}

/*appliances css content*/
.appliances-content-inner
{   
margin-top: 10vh;
height: 80vh;
width: 98%;
background-color:rgb(182 232 255); 
}
.appliances-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.appliances-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}
/*purchase css content*/
.purchase-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(182 232 255);
}
.purchase-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.purchase-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}
/*assign css content*/
.assign-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(182 232 255);
}
.assign-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.assign-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}
#unassigned-table, #assigned-table {
    display: none;
}




/*sales css content*/
.sales-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(55, 24, 95);
}
.sales-content-inner-top{
height:20%;
width:100%;
background-color:rgb(54, 46, 212,0.9);
padding:3%;
}
.sales-content-inner-bottom
{
  padding-top: 1%;
  padding-inline:1%;
}
.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
  overflow-y: scroll;
}


/*product css content*/
.product-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(55, 24, 95);
}
</style>
</head>
  <body>

<script>
var jsMessage1 = <?php echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script>


<!--script-->

    


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
        
      function scrollToSection(selector) 
      {
          const section = document.querySelector(selector);
          if (section) {
              window.scrollTo({ top: section.offsetTop, behavior: 'smooth' });
          }
        };

        document.addEventListener("DOMContentLoaded", function() {
            var buttonToClick = document.getElementById("buttonToClick");
            buttonToClick.click();
            buttonToClick.focus()
        });

        function submitForm() {
            document.getElementById("typeForm").submit();
        }

     

       
  </script>
<!--script-->
    <div class="outer-div">
            
          <div class="top-navigation">
          <div class="logo"></div>
            <ul class="top-navigation-list">
              <form action="admin.php" method="POST" style="margin-block:auto"><li><button type="submit" class="btn btn-primary buttons" name="home">HOME</button></li></form>
              <li><button type="button" class="btn btn-primary buttons" id="buttonToClick" onclick="scrollToSection('.dashboard-content')">DASHBOARD</button></li>
              <!--<li> <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">STAFFS</button></li>-->
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.courier-content')">ASSIGNED ORDERS</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.vendor-content')">IN TRANSIT</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.customer-content')">DELIVERED</button></li>
              <form action="admin.php" method="POST" style="margin-block:auto; "><li><button type="submit" class="btn btn-primary logout-button" name="destroy">LOGOUT</button></li></form>
              </ul>
          </div>
      
        <!--dashboard content goes here-->
          <div class="dashboard-content section">
          <div class="cards-outer">
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/delivered.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Delivered</p><p class="card-text-content"><?php echo $courier_delivered ?></p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/calender.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Joined On</p><p class="card-text-content"><?php echo $courier_joined ?></p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/courier.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Delivery Pending</p><p class="card-text-content"><?php echo $pending_delivery ?></p></div></div>
          <!--<div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/staff.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Staffs</p><p class="card-text-content"><?php echo $staff_num ?></p></div></div>
          </div><div class="cards-outer" style="margin-block-start: 3%;">
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/category.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Categories</p><p class="card-text-content"><?php echo $category_num ?></p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/type.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Types</p><p class="card-text-content"><?php echo $type_num ?></p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/brand.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Brands</p><p class="card-text-content"><?php echo $brand_num ?></p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/appliance.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Appliances</p><p class="card-text-content"><?php echo $appliances_num ?></p></div></div>
          </div><div class="cards-outer" style="margin-block-start: 3%;">
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/purchase.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Purchases</p><p class="card-text-content"><?php echo $purchase_num ?></p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('./dashboard_icons/sales.gif');"></div><div class="card-n-inner"><p class="card-text-heading">Total Sales</p><p class="card-text-content"><?php echo $sales_num ?></p></div></div>
      -->
        </div>
          
          
          <!--<div class="card2"><p class="card-text-heading">TOTAL COURIER PARTNERS</p><p class="card-text-content"><?php echo $courier_num ?></p></div>
          <div class="card3"><p class="card-text-heading">Total Vendors</p><p class="card-text-content"><?php echo $vendor_num ?></p></div>
          <div class="card4"><p class="card-text-heading">Total Customers</p><p class="card-text-content"><?php echo $customer_num ?></p></div>
      --></div>
          </div><!--dashboard content completed-->

           
            <!--vendor content goes here-->
            <div class="vendor-content section">
            <div class="vendor-content-inner">
            
            <div class="vendor-content-inner-top">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="color:white;">
                Deliver the Consignments on time and update the status of the delivery. 
                If you are unable to deliver the consignment, please update the status as "Customer Unreachable" and your deliery time will be extended by 3 days. 
                If you are unable to deliver the consignment after the third time, the consignment will be reassigned to another courier partner and you will be suspended.
                </div>
                </div>
            <div class="vendor-content-inner-bottom">
              <div class="view_table_wrapper">
              <table class="table-bordered table-striped view_table"> 
                <tr> 
                <th>Assign ID</th>
                  <th>Cart ID</th>
                  <th>Delivery Details</th>
                  <th>Assign Date</th>
                  <th>Deliver Before</th>
                  <th colspan="2">Action</th>
                </tr>
                </tr>
                <?php
                  // Assuming you have an SQL query stored in the $result2 variable
                  $query = "SELECT * FROM tbl_courier_assign WHERE Delivery_Status = 'SHIPPED' AND Courier_ID ='$user_number'";
                  $courier_num2 = $conn->query($query);
                  if(mysqli_num_rows($courier_num2)>0)
                    {
                      while ($row_c = $courier_num2->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . $row_c['Courier_Assign_ID'] . "</td>";
                          echo "<td>" . $row_c['CM_ID'] . "</td>";
                          //$master_c_id=$row_c['CM_ID'];
                          //select customer details from tbl_customer where cust_id = $row_c['Customer_ID']
                          // 	Cust_Fname 	Cust_Lname 	Cust_Phone 	Cust_Gender 	Cust_Hname 	Cust_Street 	Cust_Dist 	State_Ut 	Cust_Pin
                          $customer_id=$row_c['Customer_ID'];
                          $query_c = "SELECT * FROM tbl_customer WHERE Cust_ID='$customer_id'";
                          $customer_num2 = mysqli_query($conn,$query_c);
                          $row_cust = mysqli_fetch_array($customer_num2);
                          //if(mysqli_num_rows($row_cust)=1)
                          if($row_cust)
                          {
                            echo "<td>" . $row_cust['Cust_Fname'] . " " . $row_cust['Cust_Lname'] . "<br>" . $row_cust['Cust_Phone'] . "<br>" . $row_cust['Cust_Hname'] . ", " . $row_cust['Cust_Street'] . "<br>" . $row_cust['Cust_Dist'] . ", " . $row_cust['State_Ut'] . ", " . $row_cust['Cust_Pin'] . "</td>";
                          }
                          //echo "<td>" . $row_c['Delivery_Details'] . "</td>";
                          echo "<td>" . $row_c['Courier_Assign_Date'] . "</td>";
                          echo "<td>" . $row_c['Max_Delivery_Date'] . "</td>";
                          echo '<td style="text-align:center;"><form method="POST"><input type="hidden" name="cart_m_id" value="'. $row_c['CM_ID'] .'"><input type="hidden" name="courier_assign_id" value="'. $row_c['Courier_Assign_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="delivered">DELIVERED</button></form></td>';
                          //Customer Unreachable button
                          //Delivery_Error_Status
                          $delivery_error_status=$row_c['Delivery_Error_Status'];
                          if($delivery_error_status<3)
                          {
                          $delivery_error_status=$delivery_error_status+1;
                          echo '<td style="text-align:center;"><form method="POST"><input type="hidden" name="courier_assign_id" value="'. $row_c['Courier_Assign_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="unreachable">CUSTOMER UNREACHABLE x'.$delivery_error_status.'</button></form></td>';
                          }
                          else
                          {
                            echo '<td style="text-align:center;"><form method="POST"><button type="submit" class="btn btn-primary me-md-2" name="unreachable" disabled>CUSTOMER UNREACHABLE</button></form></td>';
                          }
                          echo "</tr>";
                      }
                    }
                      else {
                        echo '<tr>';
                        echo '<td colspan="6" style="text-align:center">No Consignments left to deliver</td>';
                        echo '</tr>';
                      }
                    echo'</table>';
                  ?>  
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
                <!--<a href="Register_Courier.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Courier Partners</button></a>-->
                </div>
                </div>
            <div class="courier-content-inner-bottom">
            <div class="view_table_wrapper">
            <table class="table-bordered table-striped view_table">
                <tr> 
                  <th>Assign ID</th>
                  <th>Cart ID</th>
                  <th>Delivery Details</th>
                  <th>Assign Date</th>
                  <th>Deliver Before</th>
                  <th>Update Status</th>
                </tr>
                </tr>
                <?php
                  // Assuming you have an SQL query stored in the $result2 variable
                  $query = "SELECT * FROM tbl_courier_assign WHERE Delivery_Status='ASSIGNED' AND Courier_ID ='$user_number'";
                  $courier_num2 = $conn->query($query);
                  if(mysqli_num_rows($courier_num2)>0)
                  {
                    //if ($courier_num2) {
                      while ($row_c = $courier_num2->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . $row_c['Courier_Assign_ID'] . "</td>";
                          echo "<td>" . $row_c['CM_ID'] . "</td>";
                          //select customer details from tbl_customer where cust_id = $row_c['Customer_ID']
                          // 	Cust_Fname 	Cust_Lname 	Cust_Phone 	Cust_Gender 	Cust_Hname 	Cust_Street 	Cust_Dist 	State_Ut 	Cust_Pin
                          $customer_id=$row_c['Customer_ID'];
                          $query_c = "SELECT * FROM tbl_customer WHERE Cust_ID='$customer_id'";
                          $customer_num2 = mysqli_query($conn,$query_c);
                          $row_cust = mysqli_fetch_array($customer_num2);
                          //if(mysqli_num_rows($row_cust)=1)
                          if($row_cust)
                          {
                            echo "<td>" . $row_cust['Cust_Fname'] . " " . $row_cust['Cust_Lname'] . "<br>" . $row_cust['Cust_Phone'] . "<br>" . $row_cust['Cust_Hname'] . ", " . $row_cust['Cust_Street'] . "<br>" . $row_cust['Cust_Dist'] . ", " . $row_cust['State_Ut'] . ", " . $row_cust['Cust_Pin'] . "</td>";
                          }
                          //echo "<td>" . $row_c['Delivery_Details'] . "</td>";
                          echo "<td>" . $row_c['Courier_Assign_Date'] . "</td>";
                          echo "<td>" . $row_c['Max_Delivery_Date'] . "</td>";
                          echo '<td style="text-align:center;"><form method="POST"><input type="hidden" name="courier_assign_id" value="'. $row_c['Courier_Assign_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="ship_courier">SHIP CONSIGNMENT</button></form></td>';
                          echo "</tr>";
                      }
                    }
                      else {
                        echo '<tr>';
                        echo '<td colspan="6" style="text-align:center">No Consignments assigned</td>';
                        echo '</tr>';
                      }
                    echo'</table>';
                  ?>  
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
                </div>
                </div>
            <div class="customer-content-inner-bottom">
            <div class="view_table_wrapper">
            <table class="table-bordered table-striped view_table">
                <tr> 
                <th>Delivery ID</th>
                  <th>Cart ID</th>
                  <th>Courier Assign ID</th>
                  <th>Customer ID</th>
                  <th>Delivery Date</th>
                </tr>
                  <?php
                  //select * from tbl_delivered
                  $select_delivered_items="SELECT * FROM tbl_delivery WHERE Courier_Assign_ID IN (SELECT Courier_Assign_ID FROM tbl_courier_assign WHERE Delivery_Status = 'DELIVERED' AND Courier_ID = '$user_number')";
                  $delivered_app=mysqli_query($conn,$select_delivered_items);
                  if(mysqli_num_rows($delivered_app)>0)
                  {
                    while($row_del=mysqli_fetch_array($delivered_app))
                    {
                      //Delivery_ID 	Courier_Assign_ID 	CM_ID 	Delivery_Date 	
                      echo "<tr>";
                      echo "<td>" . $row_del['Delivery_ID'] . "</td>";
                      echo "<td>" . $row_del['CM_ID'] . "</td>";
                      echo "<td>" . $row_del['Courier_Assign_ID'] . "</td>";
                      //select Customer_ID from tbl_cart_master where CM_ID=$row_del['CM_ID']
                      $select_customer_id = "SELECT Customer_ID FROM tbl_cart_master WHERE CM_ID='" . $row_del['CM_ID'] . "'";
                      $customer_id_app=mysqli_query($conn,$select_customer_id);
                      $row_customer_id=mysqli_fetch_array($customer_id_app);
                      $ordered_cust_id=$row_customer_id['Customer_ID'];
                      echo "<td>" . $ordered_cust_id . "</td>";
                      echo "<td>" . $row_del['Delivery_Date'] . "</td>";
                      echo "</tr>";
                    }
                  }
                  else
                  {
                    echo '<tr>';
                    echo '<td colspan="5" style="text-align:center">No Consignments delivered yet.</td>';
                    echo '</tr>';
                  }
                      // Close the database connection
                      $delivered_app->close();
                      ?>

              </table>
            </div>
            </div>
            </div>
            </div>
            <!--customer content completed-->

            <!--category content goes here
          <div class="category-content section">
          <div class="category-content-inner">
            <div class="category-content-inner-top">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="Add_category.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Category</button></a>
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
                <?php/*
              
                      $query_category = "SELECT * FROM tbl_category";
                      $category_num2 = $conn->query($query_category);

                      if ($category_num2) {
                          while ($row_cat = $category_num2->fetch_assoc()) {
                              echo "<tr>";
                              echo "<td>" . $row_cat['Cat_ID'] . "</td>";
                              echo "<td>" . $row_cat['Cat_Name'] . "</td>";
                              echo '<td style="text-align: center;"><form action="admin.php" method="POST"><input type="hidden" name="cat_updateid" value="'. $row_cat['Cat_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="update_cat">UPDATE</button></form></td>';
                              echo "</tr>";
                             }}
                       else {
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
                        echo '<td style="text-align: center;"><form action="admin.php" method="POST"><input type="hidden" name="type_updateid" value="'. $row_typ['Type_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="update_type">UPDATE</button></form></td>';
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
            <a href="Add_brand.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Brand</button></a>
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
                  $brand_image=$row_br['Brand_Logo'];
    
                        echo "<tr>";
                        echo "<td>" . $row_br['Brand_ID'] . "</td>";
                        echo "<td>" . $row_br['Brand_Name'] . "</td>";
                        echo "<td><img src='" . $brand_image . "' alt='Brand Logo' height='60px' width='60px'></td>";
                        echo '<td style="text-align: center;"><form action="admin.php" method="POST"><input type="hidden" name="brand_updateid" value="'. $row_br['Brand_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="update_brand">UPDATE</button></form></td>';
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
              <a href="Add_appliance1.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Appliance</button></a>
              </div>
              </div>
              <div class="staff-content-inner-bottom">
              <div class="view_table_wrapper">
              <table class="table-bordered table-striped view_table">
              <tr> 
                <th>Appliance ID</th> 
                <th>Appliance Name</th> 
                <th>Type ID</th>
                <th>Brand ID</th>
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
                        echo "<td>" . $row_app['Type_ID'] . "</td>";
                        echo "<td>" . $row_app['Brand_ID'] . "</td>";
                        echo "<td><img src='" . $image1 . "' alt='Image 1' height='50px' width='45px'></td>";
                        echo "<td><img src='" . $image2 . "' alt='Image 2' height='50px' width='45px'></td>";
                        echo "<td>" . $row_app['Appliance_Profit_Percentage'] . "</td>";
                        echo "<td>" . $row_app['Appliance_Description'] . "</td>";

                        
                        //edit staff button
                        echo '<td><form action="admin.php" method="POST"><input type="hidden" name="app_updateid" value="'. $row_app['Appliance_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="update_app">UPDATE</button></form></td>';
                        echo "</tr>";
                    }
                } 
                
                else 
                {
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
            <a href="Make_Purchase1.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Make Purchase</button></a>
             </div>
              </div>
              <div class="purchase-content-inner-bottom">
              <div class="view_table_wrapper">
              <table class="table-bordered table-striped view_table">
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
                 } 
                 else 
                 {
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

              <div class="d-grid gap-2 d-md-flex justify-content-md-end" >
              <form method="POST" style="float: right;height:100%;widht:100%;">
              <!--<button class="btn btn-primary me-md-2 add_buttons" type="submit" name="Unassigned">Unassigned Orders</button>-->
              <button class="btn btn-primary me-md-2 add_buttons" type="button" onclick="showTable('unassigned-table')">Unassigned Orders</button>
              <!--<button class="btn btn-primary me-md-2 add_buttons" type="submit" name="Assigned">Assigned Orders</button>-->
              <button class="btn btn-primary me-md-2 add_buttons" type="button" onclick="showTable('assigned-table')">Assigned Orders</button>

            </form>
             </div>
              </div>
              <div class="assign-content-inner-bottom">    
                
              <table class="table-bordered table-striped view_table" id="default-table">
              <tr>
              <td colspan="6" style="text-align:center;background-color:rgb(255 46 46);color:rgb(256 256 256)">Select "Unassigned Orders" or "Assigned Orders" to view the respective tables.</td>
              </tr>
              </table> 
              <div class="view_table_wrapper">
              <?php
               //Unassigned table
              echo '<table class="table-bordered table-striped view_table" id="unassigned-table">';
              echo '<tr>';
              echo '<th>Payment ID</th>'; 
              echo '<th>Customer ID</th>'; 
              echo '<th>Purchase Date</th>';
              echo '<th>Delivery Address</th>';
              echo '<th colspan="2">Assign Delivery Partner</th>';
              echo '</tr>';
              
              //delivery partner
              $sql_list_all_delivery_partner="SELECT Cour_ID,Cour_Name FROM tbl_courier WHERE Cour_Status=1";
              $result_list_all_delivery_partner=$conn->query($sql_list_all_delivery_partner);
              $delivery_partners = [];
              while ($row_list_all_delivery_partner = $result_list_all_delivery_partner->fetch_assoc()) {
              $delivery_partners[] = $row_list_all_delivery_partner;
              }
              $query = "SELECT * FROM tbl_payment WHERE Courier_Assignment_Status=0"; // Replace with your actual query
                $payment_num2 = $conn->query($query);
                //if ($payment_num2) 
                if ($payment_num2->num_rows > 0)
                {
                  while ($row_pay = $payment_num2->fetch_assoc()) 
                  {
                        $payment_id=$row_pay['Payment_ID'];
                        $cart_master_id=$row_pay['CM_ID'];
                        $date=$row_pay['Payment_Date'];
                        //customer details
                        $sql_customer_details="SELECT Customer_ID FROM tbl_cart_master WHERE CM_ID='$cart_master_id'";
                        $result_customer_details=$conn->query($sql_customer_details);
                        $row_customer_details=$result_customer_details->fetch_assoc();
                        $customer_id=$row_customer_details['Customer_ID'];
                        //customer address
                        $sql_customer_address="SELECT Cust_Hname,Cust_Street,Cust_Dist,State_Ut,Cust_Pin FROM tbl_customer WHERE Cust_ID='$customer_id'";
                        $result_customer_address=$conn->query($sql_customer_address);
                        $row_customer_address=$result_customer_address->fetch_assoc();
                        $customer_address=$row_customer_address['Cust_Hname'].", ".$row_customer_address['Cust_Street'].", ".$row_customer_address['Cust_Dist'].", ".$row_customer_address['State_Ut'].", ".$row_customer_address['Cust_Pin'];
                        
                        echo "<tr>";
                        echo "<td>" . $payment_id . "</td>";
                        echo "<td>" . $customer_id . "</td>";
                        echo "<td>" . $date . "</td>";
                        echo "<td>" . $customer_address . "</td>";
                        echo "<td><form action='' method='POST'><input type='hidden' name='payment_id' value='". $payment_id ."'><select name='courier_assign_id' id='courier_id' style='width: 100%;'><option value=''>Select Delivery Partner</option>";
                        foreach ($delivery_partners as $delivery_partner) {
                        echo "<option value='".$delivery_partner['Cour_ID']."'>".$delivery_partner['Cour_Name']."</option>";
                        }
                        echo "</select></td><input type='hidden' name='master_cart_id' value='". $cart_master_id ."'><input type='hidden' name='Customer_Id' value='". $customer_id ."'>";
                        echo "<td><button type='submit' class='btn btn-primary me-md-2' name='assign_courier'>ASSIGN</button></form></td>";
                        echo "</tr>";
                     }
                
                 } 
                 
                 else {
                  echo '<tr>';
                  echo '<td colspan="6" style="text-align:center">No Unassigned Orders Available</td>';
                  echo '</tr>';
              }
              
                 
                echo '</table>';
                


                //Assigned table
                echo '<table class="table-bordered table-striped view_table" id="assigned-table">'; 
                echo '<tr>';
                echo '<th>Payment ID</th>';
                echo '<th>Customer ID</th>';
                echo '<th>Delivery Partner</th>';
                echo '<th>Purchase Date</th>';
                //courier assign date
                echo '<th>Courier Assign Date</th>';
                echo '<th>Expected Delivery Before</th>';//10 days from courier assign date
                echo '</tr>';
                $query = "SELECT * FROM tbl_payment WHERE Courier_Assignment_Status=1";
                $payment_num2 = $conn->query($query);
                //if ($payment_num2)
                if ($payment_num2->num_rows > 0)
                {
                  while ($row_pay = $payment_num2->fetch_assoc())
                  {
                        $payment_id=$row_pay['Payment_ID'];
                        $cart_master_id=$row_pay['CM_ID'];
                        $date=$row_pay['Payment_Date'];
                        //customer details
                        $sql_customer_details="SELECT Customer_ID FROM tbl_cart_master WHERE CM_ID='$cart_master_id'";
                        $result_customer_details=$conn->query($sql_customer_details);
                        $row_customer_details=$result_customer_details->fetch_assoc();
                        $customer_id=$row_customer_details['Customer_ID'];
                        //customer address
                        $sql_customer_address="SELECT Cust_Hname,Cust_Street,Cust_Dist,State_Ut,Cust_Pin FROM tbl_customer WHERE Cust_ID='$customer_id'";
                        $result_customer_address=$conn->query($sql_customer_address);
                        $row_customer_address=$result_customer_address->fetch_assoc();
                        $customer_address=$row_customer_address['Cust_Hname'].", ".$row_customer_address['Cust_Street'].", ".$row_customer_address['Cust_Dist'].", ".$row_customer_address['State_Ut'].", ".$row_customer_address['Cust_Pin'];
                        //delivery partner
                        $sql_delivery_partner="SELECT Courier_ID,Courier_Assign_Date FROM tbl_courier_assign WHERE CM_ID='$cart_master_id'";
                        $result_delivery_partner=$conn->query($sql_delivery_partner);
                        $row_delivery_partner=$result_delivery_partner->fetch_assoc();
                        $delivery_partner_id=$row_delivery_partner['Courier_ID'];
                        $delivery_partner_assign_date=$row_delivery_partner['Courier_Assign_Date'];
                        //delivery partner name
                        $sql_delivery_partner_name="SELECT Cour_Name FROM tbl_courier WHERE Cour_ID='$delivery_partner_id'";
                        $result_delivery_partner_name=$conn->query($sql_delivery_partner_name);
                        $row_delivery_partner_name=$result_delivery_partner_name->fetch_assoc();
                        $delivery_partner_name=$row_delivery_partner_name['Cour_Name'];
                        //expected delivery date including time
                        $assignDateTime = new DateTime($delivery_partner_assign_date);
                        $assignDateTime->modify('+10 days');
                        $expectedDeliveryDate = $assignDateTime->format('Y-m-d H:i:s');
                        
                        echo "<tr>";
                        echo "<td>" . $payment_id . "</td>";
                        echo "<td>" . $customer_id . "</td>";
                        echo "<td>" . $delivery_partner_name . "</td>";
                        echo "<td>" . $date . "</td>";
                        echo "<td>" . $delivery_partner_assign_date . "</td>";
                        echo "<td>" . $expectedDeliveryDate . "</td>";
                        echo "</tr>";
                      }
                
                    } 
                    
                 else 
                 {
                  echo '<tr>';
                  echo '<td colspan="6" style="text-align:center">No Assigned Orders pending for delivery</td>';
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
            <div class="sales-content-inner">
              </div>
            </div>
            <!--sales content completed-->



            
            </div>*/
            ?>-->
<script type="text/js" src="js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>   
</body>
</html>