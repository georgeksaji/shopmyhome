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
    <title>Order History</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
  <script>
var jsMessage1 = <?php echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script>


<!--script-->
   
   <style>
    body {
  padding: 0%;
  margin: 0%;
  overflow: hidden;

}
.home-outer {
  width: 100%;
  height: 100vh;
  overflow: hidden;
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
  padding-top:1%;
  max-height: 90vh;
    height: 90vh;
  background-color: rgb(211 247 255);
  display: inline-block;
  overflow-y: scroll;
}
.order-box-outer {
  width: 80%;
    height:max-content;
    margin:auto;
    background-color: rgba(255, 25, 255, 0.1);
    border-style: solid;
    border-width: 1px;
    display:block;
    margin-block:1.9%;
}
.order-box-top {
  width: 100%;
  height: 10%;
  padding-block:1%;
  background-color: red;
  display: flex;
  justify-content: space-evenly;
}
.order-box-center {
  width: 100%;
  height: 60%;
  background-color: blue;
  display: grid;
}
.order-box-bottom {
  width: 100%;
  height: 20%;
  background-color: green;
  display: grid;
}
.order-date {
  width: 20%;
  height: 100%;
  background-color: yellow;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.order-total {
  width: 20%;
  height: 100%;
  background-color: orange;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.order-id {
  width: 20%;
  height: 100%;
  background-color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.order-reciept {
  width: 20%;
  height: 100%;
  background-color: violet;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.order-status {
  width: 100%;
  height: 10vh;
  background-color: pink;
  display: flex;
  align-items: center;
  justify-content: center;
}
.order-items {
  width: 100%;
  height: 13vh;
  background-color: gold;
  display: flex;
  align-items: center;
  justify-content: center;
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
                  //profile page
                  //echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
                  echo '<td><button class="admin-box" name="admin">Staff Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</tr>';
                  echo '</form>';//    
                }
                //if usertype=CU
                if($usertype == 'CU') {
                  //profile page, logout
                  echo '<form action="" method="POST">';
                  //echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
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

                 <div class="order-box-outer">
                  <div class="order-box-top">
                  <div class="order-date">12-09-23</div>
                  <div class="order-total">₹34567</div>
                  <div class="order-id">PY12345</div>
                  <div class="order-reciept">Invoice</div>
                  </div>
                  <div class="order-box-center">
                    <div class="order-status">Delivered</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item 1</div>
                    <div class="order-items">Item final</div>
                  </div>
              </div>

              
               





              </div>
                   </div>
              </body>
              </html>