
<?php
include('connection.php');

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

//dashboard box values 

$result1=mysqli_query($conn,"SELECT * FROM tbl_staff");
$staff_num=mysqli_num_rows($result1);

$result2=mysqli_query($conn,"SELECT * FROM tbl_courier");
$courier_num=mysqli_num_rows($result2);

$result3=mysqli_query($conn,"SELECT * FROM tbl_vendor");
$vendor_num=mysqli_num_rows($result3);

$result4=mysqli_query($conn,"SELECT * FROM tbl_customer");
$customer_num=mysqli_num_rows($result4);


//form submissions
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
if(isset($_POST['update']))
{
  $updateid = $_POST['updateid'];
  $_SESSION['Update_ID'] = $updateid;
  header('location:Update_Staff.php');
}

//staff status update
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
    height: 100%;
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
    margin-right: 0.5%;
}
.buttons
{
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 450;
  font-size: 15px;
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
    margin-inline-start: 3%;
    display: inline-flex;
    height: 100%;
    width: 94%;  
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
    font-size: 25px;
    color: rgb(256,256,256);
    font-family: Alata, sans-serif;
    font-weight: 700;
    text-align: center;
    margin-top: 9%;
}
.card-text-content
{
    font-size: 40px;
    color: rgb(256,256,256);
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    font-weight: 900;
    text-align: center;
}

.staff-content,.vendor-content,.customer-content,.product-content,.courier-content{
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
  width:auto;
  white-space: nowrap; /* Prevents text from wrapping in cells */
  overflow: hidden; /* Prevents content from overflowing cells */
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
  overflow-y:scroll;
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
  overflow-y:scroll;
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

       
  </script>
<!--script-->
    <div class="outer-div">
            
          <div class="top-navigation">
          <div class="logo"></div>
            <ul class="top-navigation-list">
              <form action="admin.php" method="POST" style="margin-block:auto"><li><button type="submit" class="btn btn-primary buttons" name="home">HOME</button></li></form>
              <li><button type="button" class="btn btn-primary buttons" id="buttonToClick" onclick="scrollToSection('.dashboard-content')">DASHBOARD</button></li>
              <li> <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">STAFFS</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.courier-content')">COURIERS</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.vendor-content')">VENDORS</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.customer-content')">CUSTOMERS</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">BRAND</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">CATEGORY</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">SUB-CATEGORY</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.product-content')">PRODUCTS</button></li>
              <li><button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">SALES</button></li>
              <form action="admin.php" method="POST" style="margin-block:auto; "><li><button type="submit" class="btn btn-primary buttons" name="destroy">LOGOUT</button></li></form>
              </ul>
          </div>
      
        <!--dashboard content goes here-->
          <div class="dashboard-content section">
          <div class="cards-outer">
          <div class="card1"><p class="card-text-heading">Total Staff</p><p class="card-text-content"><?php echo $staff_num ?></p></div>
          <div class="card2"><p class="card-text-heading">Total Courier Partners</p><p class="card-text-content"><?php echo $courier_num ?></p></div>
          <div class="card3"><p class="card-text-heading">Total Vendors</p><p class="card-text-content"><?php echo $vendor_num ?></p></div>
          <div class="card4"><p class="card-text-heading">Total Customers</p><p class="card-text-content"><?php echo $customer_num ?></p></div>
          </div>
          </div><!--dashboard content completed-->

          <!--staff content goes here-->
            <div class="staff-content section">
            <div class="staff-content-inner">

            <div class="staff-content-inner-top">

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="Register_Staff.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Staff</button></a>
                </div>
                </div>
            <div class="staff-content-inner-bottom">
              <div class="view_table_wrapper">
              <table class="table-bordered table-striped view_table">
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
                          echo "<td>" . $row_s['Staff_Fname'] . "</td>";
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
                          if($row_s['Staff_Status'] == 1){
                            echo "<td>Active</td>";
                          }else{  
                            echo "<td>Inactive</td>";
                          }


                          //update status button
                          if($row_s['Staff_Status'] == 1)
                          {
                            echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='staff_id' value='". $row_s['Staff_ID'] ."'><button type='submit' class='btn btn-primary me-md-2 deactivate_button' name='deactivate_staff_status_button'>DEACTIVATE</button></form></td>";
                          }
                          if($row_s['Staff_Status'] == 0)
                          {
                            echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='staff_id' value='". $row_s['Staff_ID'] ."'><button type='submit' class='btn btn-primary me-md-2 activate_button' name='activate_staff_status_button'>ACTIVATE</button></form></td>";
                          }
                          //edit staff button
                          echo '<td><form action="admin.php" method="POST"><input type="hidden" name="updateid" value="'. $row_s['Staff_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="update">UPDATE</button></form></td>';
                          echo "</tr>";
                      }
                  } 
                  
                  else 
                  {
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
                <a href="Register_Vendor.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Vendors</button></a>
                </div>
                </div>
            <div class="vendor-content-inner-bottom">
            <table class="table-bordered table-striped view_table">
                <tr> 
                  <th>ID</th> 
                  <th>Username</th> 
                  <th>Courier Name</th>
                  <th>Registrant ID</th>
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
                          echo "<td>" . $row_v['Vendor_Username'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Name'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Registrant_Id'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Phno'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Hname'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Street'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Dist'] . "</td>";
                          echo "<td>" . $row_v['State_Ut'] . "</td>";
                          echo "<td>" . $row_v['Vendor_Pin'] . "</td>";
                          echo "<td>" . $row_v['Vjoining_Date'] . "</td>";
                          if($row_v['Vendor_Status'] == 1){
                            echo "<td>Active</td>";
                          }else{  
                            echo "<td>Inactive</td>";
                          }

                          //update status button
                        
                          if($row_v['Vendor_Status'] == 1)
                          {
                            echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='staff_id' value='". $row_v['Vendor_ID'] ."'><button type='submit' class='btn btn-primary me-md-2 deactivate_button' name='deactivatestatus_button'>DEACTIVATE</button></form></td>";
                          }
                          if($row_v['Vendor_Status'] == 0)
                          {
                            echo "<td style='display: flex;align-items: center;justify-content: center;'><form action='admin.php' method='POST'><input type='hidden' name='staff_id' value='". $row_v['Vendor_ID'] ."'><button type='submit' class='btn btn-primary me-md-2 activate_button' name='activatestatus_button'>ACTIVATE</button></form></td>";
                          }
                          //edit staff button
                          echo '<td><form action="admin.php" method="POST"><input type="hidden" name="updateid" value="'. $row_v['Vendor_ID'] .'"><button type="submit" class="btn btn-primary me-md-2" name="update">UPDATE</button></form></td>';
                          echo "</tr>";
                      
                        



                      }
                  }   
                  else {
                      echo "No data available.";
                  }

                  // Close the database connection
                  $vendor_num2->close();
                  ?>
 
              </table>
            </div>
                  
          

            </div>
            </div>
            <!--vendor content completed-->

            <!--courier content goes here-->
            <div class="courier-content section">
              <div class="courier-content-inner">


              <div class="courier-content-inner-top">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="Register_Courier.php"><button class="btn btn-primary me-md-2 add_buttons" type="button">Add Courier Partners</button></a>
                </div>
                </div>
            <div class="courier-content-inner-bottom">
            <table class="table-bordered table-striped view_table">
                <tr> 
                  <th>ID</th> 
                  <th>Username</th> 
                  <th>Courier Name</th>
                  <th>Registrant ID</th>
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
                          echo "<td>" . $row_c['Registrant_ID'] . "</td>";
                          echo "<td>" . $row_c['Cour_Phone'] . "</td>";
                          echo "<td>" . $row_c['Cour_Building_name'] . "</td>";
                          echo "<td>" . $row_c['Cour_Street'] . "</td>";
                          echo "<td>" . $row_c['Cour_Dist'] . "</td>";
                          echo "<td>" . $row_c['Cour_State_ut'] . "</td>";
                          echo "<td>" . $row_c['Cour_Pin'] . "</td>";
                          echo "<td>" . $row_c['Cour_Joining_Date'] . "</td>";
                          if($row_c['Cour_Status'] == 1){
                            echo "<td>Active</td>";
                          }else{  
                            echo "<td>Inactive</td>";
                          }
                          echo "<td>" . $row_c['Cour_Status'] . "</td>";
                          echo "<td>" . $row_c['Cour_Status'] . "</td>";
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
                  <th colspan="2">Action</th>
                </tr>
                <?php
              
                      $query_customer = "SELECT * FROM tbl_customer";
                      $customer_num2 = $conn->query($query_customer);

                      if ($customer_num2) {
                          while ($row_c = $customer_num2->fetch_assoc()) {
                              echo "<tr>";
                              echo "<td>" . $row_c['Cust_ID'] . "</td>";
                              echo "<td>" . $row_c['C_Username'] . "</td>";
                              echo "<td>" . $row_c['Cust_Fname'] . "</td>";
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

            <!--product content goes here-->
            <div class="product-content section">
              <div class="product-content-inner"></div>
            </div>
            <!--product content completed-->
            </div>
<script type="text/js" src="js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>   
</body>
</html>