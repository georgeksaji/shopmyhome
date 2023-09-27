<?php
include ("connection.php");
session_start();
$_SESSION['search'] = null;
$_SESSION['brand_id'] = null;
$_SESSION['category_id'] = null;
$_SESSION['type_id'] = null;

$quantity_update_status = null;
if(isset($_SESSION['quantity_update_status']) && $_SESSION['quantity_update_status'] !== null) {
  $quantity_update_status = $_SESSION['quantity_update_status']; 
    if($quantity_update_status == 1)
    {
       $_SESSION['quantity_update_status'] = null;
    }
}


//$product_detail_id = $_SESSION['product_detail_id'];

if(isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
}
else {
  $userId = null;
  $usertype = null;
}

//search appliance
//search appliance
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
if(isset($_POST['update_quantity']))
{
  $quantity = $_POST['quantity'];
  $item_id = $_POST['update_quantity'];
  $sql = "UPDATE tbl_cart_child SET Quantity = '$quantity' WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  mysqli_query($conn,$sql);
  //header("Location: cart.php");
  //update price in cart child 

    $sql_cost_price="SELECT Cost_Per_Piece FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
    $result_cost_price = mysqli_query($conn,$sql_cost_price);
    $row_cost_price = mysqli_fetch_assoc($result_cost_price);
  /* $sql_cost_price="SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$product_detail_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
    $result_cost_price = mysqli_query($conn,$sql_cost_price);
    $row_cost_price = mysqli_fetch_assoc($result_cost_price);
    if($row_cost_price == null)
    {
      $cost_price = null;
      $price = null;
    }
    else
    {
      $cost_price = $row_cost_price['Cost_Per_Piece'];
      $cost_price = $row_cost_price['Cost_Per_Piece'];
      $price = $cost_price + ($cost_price * $appliance_profit_percentage)/100;
      $total_price = $price * $quantity;
      }*/
      $cost_price = $row_cost_price['Cost_Per_Piece'];
      $price = $cost_price + ($cost_price * $appliance_profit_percentage)/100;
      $total_price = $price * $quantity;
      

    






  
}
if(isset($_POST['remove_item']))
{
  $item_id = $_POST['remove_item'];
  $sql = "DELETE FROM tbl_cart_child WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  mysqli_query($conn,$sql);
  header("Location: cart.php");
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
  background-color: rgba(255, 255, 255, 0.9);
}
.home-outer {
  width: 100%;
  height: 100vh;
  background-color: rgb(211 247 255);
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
  min-height: 90vh;
  background-color: transparent;
  display: flex;
  align-items: center;
}
.cart-left-outer
{
    width: 60%;
    height: 90vh;
    background-color: transparent;
    display: grid;
    justify-content: center;
    align-items: center;
}
.items-outer
{
    width: 100%;
    margin-inline-start: 2%;
  max-height: 72vh; /* Set the desired maximum height */
  overflow-y: scroll;
}

.each-item
{
    width: 100%;
    table-layout:fixed;
    background-color: rgb(25, 2, 25);
    border-collapse: collapse;
}
.each-item tr
{
  
color: rgb(0, 0, 0);
}
.each-item tr td
{
  background-color: rgb(255, 255, 255);
    padding: 1%;
    text-align: center;
}
.form_button input[type=number]{
  width: 35%;
  text-align: center;
  border: none;

}
.form_button button{
  width: 45%;
  height: 20%;
  text-align: center;
  border: none;
  border-radius: 5px;
  background-color: rgb(6, 28, 100);
  color: white;
}
.cart-right-outer
{
  padding-inline-start: 2%;
    width: 40%;
    height: 90vh;
    background-color: transparent;
    display: inline-grid;
    align-items: center;
}
.cart-summary,.cart-summary-bottom,.cart-address,.cart-card
{
    width: 98%;
    height: 28.5vh;
    background-color: rgb(256,256,256);
}
.cart-summary-table
{
  width: 34vh;
  height: 100%;
  border-collapse: collapse;
}
.cart-summary-table tr td
{
  padding: 1%;
  text-align: center;
}
.check-out-button
{
  width: 80%;
  height: 100%;
  border-style: none;
  background-color: #fb641b;
  color: white;
  font-size: large;
  font-weight: 550;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  transition: 0.4s;
}
.items-top
{ 
  width: 100%;
    color: white;
    display: flex;
    align-items: center;
    height: 5vh;
    background-color: #2874f0;
    font-family: inherit;
    font-weight: 480;
    position: sticky;
top: 0;

}
.address-top
{
  width: 100%;
    color: white;
    display: flex;
    align-items: center;
    height: 5vh;
    background-color: #2874f0;
    font-family: inherit;
    font-weight: 480;
}
.address-table
{
  width: 100%;
  height: max-content;
  margin-top:5%;
  margin-inline-start: 2%;
  font-family:serif;
  
}


.cards-list
{
  width: 100%;
  height: 87%;
  background-color: rgb(256,256,256);
  margin-top: 2%;
  display: flex;
  justify-content: center;
  overflow-y: scroll;
}
.cards-outer{
  padding-top: 3%;
  width: 97%;
  height: max-content;
  table-layout: auto;
}
.cards-outer th{
  text-align: center;
  border: 1px solid black;
  border-collapse: collapse;
  color:rgb(256,256,256);
  background-color: rgb(0, 0, 0);
  font-weight:400;
}
.cards-outer tbody tr:nth-child(odd) {
  background-color: rgb(0,0,0,0.1); /* Set your desired background color */
/* Set text color for better contrast */
}
.cards-outer td,th{
  text-align: center;
  color:rgb(0, 0, 0);
  border-collapse: collapse;
  height:6vh;
}


.deactivate_button,.deactivate_button:hover,.deactivate_button:active
{
  background-color:rgb(239 51 36);
  color:rgb(256,256,256);
  border-style: none;
  width: -webkit-fill-available;
  margin:auto;
}
.activate_button,.activate_button:hover,.activate_button:active
{
  background-color:rgb(78 198 111);
  color:rgb(256,256,256);
  border-style: none;
  width: -webkit-fill-available;
  margin:auto;
}




</style>
<script>
  var update_quantity_status = <?php echo json_encode($quantity_update_status); ?>;
  var jsMessage1 = "Quantity decreased to available stock as Stocks decreased."; 
  if (update_quantity_status !== null) {
    alert(jsMessage1);
  }
</script>

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
                //if userid =ST00001 AND usertype=AD then a=admin.php
                if($userId == 'ST00001' && $usertype == 'AD') {
                  //admin panel page , logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><button class="admin-box" name="admin">Admin Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</form>';
                }
                //if userid!=ST00001 AND usertype=ST
                if($userId != 'ST00001' && $usertype == 'ST') {
                  //profile page,Staff Dashboard, logout
                  echo '<form action="" method="POST">';
                  //profile page
                  echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
                  echo '<td><button class="admin-box" name="admin">Staff Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</tr>';
                  echo '</form>';//    
                }
                //if usertype=CU
                if($usertype == 'CU') {
                  //profile page, logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
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

            <div class="cart-left-outer">
                <div class="items-outer">
                <div class="items-top" style="padding-inline-start:2%;">CART ITEMS</div>
                    <table class="each-item">
                      <?php
                      if($cm_id != null)
                      {
                      $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
                      $result = mysqli_query($conn,$sql);
                      $count = mysqli_num_rows($result);
                      if($count > 0)
                      {
                        while($row = mysqli_fetch_assoc($result))
                        {
                          $item_id = $row['Appliance_ID'];
                          $quantity = $row['Quantity'];
                          $price = $row['Price'];
                          $sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$item_id'";
                          $result1 = mysqli_query($conn,$sql);
                          $row1 = mysqli_fetch_assoc($result1);
                          $product_name = $row1['Appliance_Name'];
                          $product_image = $row1['Appliance_Image1'];
                          echo "<tr>";
                          echo "<td><img src='$product_image' height='100vh'></td>";
                          echo "<td>$product_name</td>";
                          
                          //$sql_quantity
                          $select_quantity="SELECT Balance_Stock FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
                          $result_quantity = mysqli_query($conn,$select_quantity);
                          $row_quantity = mysqli_fetch_assoc($result_quantity);
                          $available_quantity = $row_quantity['Balance_Stock'];
                          //echo "<td>Available: ".$row_quantity['Balance_Stock']."</td>";

                          //$quantity increase and decrease button
                          echo "<td><form method='POST' class='form_button'><input type='number' name='quantity' value='$quantity' min='1' max='$available_quantity'><button type='submit' name='update_quantity' value='$item_id'>Update</button></td>";
                          echo "<td>₹ $price</td>";
                          echo '<td><button name="remove_item" style="background-color:transparent;border-style:none"><img src="remove.png" height="30px"></button></td>';
                          echo "</form></tr>";
                        }
                      }
                    }
                    else{
                      echo "<tr><td colspan='4'>No items in cart</td></tr>";
                    }
                        ?>
                    </table>
                </div>
                </div>

              <div class="cart-right-outer">
              <div class="cart-address">
                <div class="address-top" style="padding-inline-start:2%;">DELIVERY ADDRESS</div>
                <table class="address-table">
                    <?php
                    if($userId != null)
                    {
                      $sql = "SELECT * FROM tbl_customer WHERE Cust_ID = '$userId'";
                      $result = mysqli_query($conn,$sql);
                      $row = mysqli_fetch_assoc($result);
                      //C_Username 	Cust_Fname 	Cust_Lname 	Cust_Phone 	Cust_Gender 	Cust_Hname 	Cust_Street 	Cust_Dist 	State_Ut 	Cust_Pin
                      $name = $row['Cust_Fname']." ".$row['Cust_Lname'];
                      $phone = $row['Cust_Phone'];
                      $address = $row['Cust_Hname'].", ".$row['Cust_Street'].", ".$row['Cust_Dist'].", ".$row['State_Ut'].", ".$row['Cust_Pin'];
                      echo "<tr><td>$name</td></tr>";
                      echo "<tr><td>$phone</td></tr>";
                      echo "<tr><td>$address</td></tr>";
                      
                    }
                    else{
                      echo "<td colspan='6'>No address found</td>";
                    }
                    ?>
                 

                  </table>
               
               </div>

               <!--card details-->
               <div class="cart-card">
               <div class="address-top" style="padding-inline-start:2%;">CARD DETAILS</div>
               


               <div class="cards-list">
                        <table class="cards-outer">
                         

                         <?php
                          $query = "SELECT * FROM tbl_card WHERE Customer_ID = '$userId' AND Card_Status = 1";
                          $result = $conn->query($query); 

                          if ($result->num_rows > 0) {
                            echo "<tr>";
                            echo "<th></th>";
                            echo "<th>Card Number</th>";
                            echo "<th>Bank Name</th>";
                            echo "</tr>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='radio' name='card' value='" . $row['Card_No'] . "'></td>";
                                echo "<td>" . $row['Card_No'] . "</td>";
                                echo "<td>" . $row['Bank_Name'] . "</td>";
                                

                                echo "<tr>";
                            }        

                            }
                          else if ($result->num_rows == 0) {
                            echo "<tr>";
                            echo "<td colspan='2' style='text-align:center;'>No cards added yet!</td>";
                            echo "</tr>";
                          }
                         ?>


                         </table>
              </div>









               </div>
               <!--cart summary-->
              <div class="cart-summary-bottom" style="height:18vh;background-color:transparent;display: grid;text-align: center;justify-content: center;align-items: center;">
                <div class="cart-summary-body" style="height: 64%;width: max-content;">
                  <table class="cart-summary-table">
                    <tr>
                      <?php
                      if ($cm_id != null) {
                          $sql = "SELECT Total_Amount FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
                          $result = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_assoc($result);
                          $total_amount = $row['Total_Amount'];
                      } else {
                          $total_amount = 0;
                      }
                      echo "<td><h5 style='color:red;'>₹ $total_amount</h5></td>";
                      ?>
                    </tr>
                  </table>
                </div>
                <div class="cart-summary-footer" style="height:7vh">
                <button class="check-out-button">Checkout</button>
                
              </div>
              </div>
              
              


            </div>
            </div>
            </body>
            </html>