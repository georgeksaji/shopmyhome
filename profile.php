<?php
include ("connection.php");
session_start();
$_SESSION['search'] = null;
$_SESSION['brand_id'] = null;
$_SESSION['category_id'] = null;
$_SESSION['type_id'] = null;
//$product_detail_id = $_SESSION['product_detail_id'];

if(isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
}
else {
  $userId = null;
  $usertype = null;
}


//logout button
if(isset($_POST['logout']))
{
  session_destroy();
  header("Location: index.php");
}
//cart button
if(isset($_POST['cart']))
{
  $quantity = $_POST['quantity'];
  header("Location: cart.php");
}
//password edit button
if(isset($_POST['password-edit']))
{
  header("Location: password-edit.php");
}
if(isset($_POST['profile-edit']))
{
  header("Location: profile-edit.php");
}
//view orders button
if(isset($_POST['view-orders']))
{
  header("Location: view-orders.php");
}
//view cards button
if(isset($_POST['view-cards']))
{
  header("Location: view-cards.php");
}

?>

<!doctype html>
<html lang="en">
  <head>
    <title>shopmyhome</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!--     
  <script>
var jsMessage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php //echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script> -->


<!--script-->
   
   <style>
    body {
  padding: 0%;
  margin: 0%;

}
.home-outer {
  width: 100%;
  height: 100vh;
}

.top-navigation {
  width: 100%;
  height:10vh;
  background-color: rgba(101, 194, 240, 0.4);
  background-origin: content-box;
  display: -webkit-box;
  align-items: center;
}

.navigation-logo {
  background-image: url(Picture3.png);
    background-size: contain;
    margin-inline-start: 2vh;
    height: 100%;
    width: 33vh;
}

.navbar{
  width: 49%;
}
.container-fluid,
.d-flex
{
  width: 100%;

}

.form-control{
  width: 50%;
  padding-top: 1%;
  padding-bottom: 1%;
  background-color: rgb(6, 28, 100,0.1);
  font-weight: 350;
  font-family:calibri;
  transition: 0.4s;
}
.form-control::-webkit-search-cancel-button {
  display: none;
}


.form-control:hover,
.form-control:focus {
background-color: rgba(255, 255, 255, 0.456);
outline: none;
box-shadow: none;
} 

.btn
{
border-color:rgb(6, 28, 100);
color: rgb(6, 28, 100);
transition: 0.4s;
}

.btn:hover
{
background-color:rgb(6, 28, 100);
}

.navigation-item {
  
    height: 100%;
    width: 80%;
    padding-right: 4%;
}
.action-table {
  width: 100%;
  height: 100%;
  border-collapse: separate;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}
.action table td{
  display: inline-block;
}
.login-box,.signup-box,.admin-box,.logout-box,.profile-box{
  border-style: none;
  background-color: rgba(255, 255, 255, 0.1);
  transition: 0.3s;
  font-size: small;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 550;
  color:rgb(0, 0, 0);
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 91px;
  height: 40px;
}
.login-box:hover,.signup-box:hover,.admin-box:hover{
  background-color: rgb(0, 0, 0);
  color: white;
}
.logout-box:hover{
  background-color: rgb(239,51,36);
  color: white;
}
.profile-box:hover{
  transition: 0.5s;
  transform: scale(1.2);
}
.action table .btn-primary,.action table .btn
{
  border-style: none;
  background-color: rgb(6, 28, 100);
}
.action table .btn:hover {
    background-color: rgb(256,256,256);
}
.action-table td
{
  width:auto;
  white-space: nowrap; 
  overflow: hidden;  
  text-overflow: ellipsis;
}

a {
  text-decoration: none;
}
.content-section {
  width: 100%;
  height: 90vh;
  background-color: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
}
.cards-outer
{
  width: 100%;
  height: 100%;

  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
}
.card-button
{
  margin:2%;
  width:20%;
  height:20%;
  background-color: transparent;
  border-style: none;
}
.card
{
  width: 100%;
  height: 100%;
  background-color: rgb(256,256,256);
  margin: 2%;
  border-style:solid;
  border-width: 1px;
  border-color: grey;
  display: contents;
  border-radius: 10px;
  transition: 0.5s;
}
.card-inner-left1,.card-inner-left2,.card-inner-left3,.card-inner-left4

{
  width: 30%;
  height: 100%;
  float: left;
  background-color: rgb(0,0,0);
  border-style:solid;
  border-width: 1px;
  border-right: none;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  border-radius: 10px 0px 0px 10px;
}
.card-inner-right

{
  width: 70%;
  height: 100%;
  float:right;
  color:white;
  background-color: black;
  border-style:solid;
  border-width: 0px;
  border-left: none;
  border-radius: 0px 10px 10px 0px;
  display:flex;
  justify-content: center;
  align-items: center;
 
}
.card-inner-left1
{
background-image: url('orders.png');
}
.card-inner-left2
{

  background-image: url('lock.png');
}
.card-inner-left3
{

  background-image: url('card.png');
}
.card-inner-left4
{

  background-image: url('profile-update.png');
}

</style>
    
  </head>
  <body>
 <div class="home-outer">
 
  <div class="top-navigation">
        <a href="index.php"><div class="navigation-logo"></div></a>
           <div class="navigation-item">
            <table class="action-table">
              <?php
              // if userid and usertype null, display login and signup
              if($userId == null && $usertype == null) {
                echo '<form action="" method="POST">';
                echo '<tr><td><button class="login-box" name="login">Login</button></td>';
                echo '<td><button class="signup-box" name="signup">Sign Up</button></td>';
                echo '</form>';
                }
                /*if userid =ST00001 AND usertype=AD then a=admin.php
                if($userId == 'ST00001' && $usertype == 'AD') {
                  //admin panel page , logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><button class="admin-box" name="admin">Admin Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</form>';
                }*/
                //if userid!=ST00001 AND usertype=ST
                if($userId != 'ST00001' && $usertype == 'ST') {
                  //profile page,Staff Dashboard, logout
                  echo '<form action="" method="POST">';
                  //select name from tbl_customer where customer_id = $userId
                  $sql = "SELECT * FROM tbl_staff WHERE Staff_ID = '$userId'";
                  $result = mysqli_query($conn,$sql);
                  $row = mysqli_fetch_assoc($result);
                  $name = $row['Staff_Fname'];
                  //hi name
                  echo '<tr><td><button class="profile-box" style="display: flex;align-items: end;transform: scale(1)"><h6 style="color:red">Hi, '.$name.'</h6></button></td>';
                  
                  echo '<td><button class="admin-box" name="admin">Staff Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</tr>';
                  echo '</form>';//    
                }
                //if usertype=CU
                if($usertype == 'CU') {
                  //profile page, logout
                  echo '<form action="" method="POST">';
                  //select name from tbl_customer where customer_id = $userId
                  $sql = "SELECT * FROM tbl_customer WHERE Cust_ID = '$userId'";
                  $result = mysqli_query($conn,$sql);
                  $row = mysqli_fetch_assoc($result);
                  $name = $row['Cust_Fname'];
                  //hi name
                  echo '<tr><td><button class="profile-box" style="display: flex;align-items: end;transform: scale(1)"><h6 style="">'.$name.'</h6></button></td>';
                  //cart button
                  $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
                  $result = mysqli_query($conn,$sql);
                  if(mysqli_num_rows($result) > 0)
                  {
                  $row = mysqli_fetch_assoc($result);
                  $cm_id = $row['CM_ID'];
                  $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
                  $result = mysqli_query($conn,$sql);
                  $count = mysqli_num_rows($result);
                  echo "<td><button type='submit' class='profile-box' name='cart'><img src='cart.png' height='30px' width='30px'><span style='font-size: 0.95em; color: rgb(239,51,36)' class='badge text-bg-secondary'>$count</span></button></td>";
                  }
                  else if(mysqli_num_rows($result) == 0)
                  {
                    $cm_id = null;
                    echo "<td><button type='submit' class='profile-box' name='cart'><img src='cart.png' height='30px' width='30px'><span style='font-size: 0.95em; color: rgb(239,51,36)' class='badge text-bg-secondary'>0</span></button></td>"; 
                  }
                   echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</tr>';
                  echo '</form>';
                }
                ?>
              </table>
            </div> 
            </div>
            <div class="content-section">
                  <div class="cards-outer">
                  <form method="POST" style="height:100%;width:100%;display:flex;justify-content:center;align-items:center">
                  <button class="card-button" name="password-edit"><div class="card"><div class="card-inner-left2"></div><div class="card-inner-right">LOGIN & SECURITY</div></div></button>
                   <?php
                  if($usertype == 'CU') {
                  echo  '<button class="card-button" name="profile-edit"><div class="card"><div class="card-inner-left4"></div><div class="card-inner-right">UPDATE PROFILE</div></div></button>';
                  echo '<button class="card-button" name="view-orders"><div class="card"><div class="card-inner-left1"></div><div class="card-inner-right">ORDERS</div></div></button>';
                  echo '<button class="card-button" name="view-cards"><div class="card"><div class="card-inner-left3"></div><div class="card-inner-right">CARDS</div></div></button>';
                  }
                 ?>  
                </form>
                  </div>
                   

                   </div>
              </body>
              </html>