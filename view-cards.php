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
if (isset($_POST['show_profile_page'])) {
  header("Location: profile.php");
}

//iffest submit add to tbl card
if(isset($_POST['submit']))
{
  $card_type = $_POST['card-type'];
  $card_number = $_POST['card-number'];
  $card_holder_name = $_POST['card-holder-name'];
  $bank_name = $_POST['bank-name'];
  $expiry_date = $_POST['expiry-date'];
  $cvv = $_POST['cvv'];
// Card_ID 	Customer_ID 	Card_No 	Card_Holder_Name 	CVV 	Bank_Name 	Card_Type 	Expiry_Date 	Card_Status 	
  //find if card number already exists
  $sql = "SELECT * FROM tbl_card WHERE Card_No = '$card_number'";
  $result = mysqli_query($conn,$sql);
  if(mysqli_num_rows($result) > 0)
  {
    echo "<script>alert('Card number already exists!');</script>";
  }
  else if(mysqli_num_rows($result) == 0)
  {
    //insert into tbl_card
  $sql = "INSERT INTO tbl_card (Card_ID,Customer_ID,Card_No,Card_Holder_Name,CVV,Bank_Name,Card_Type,Expiry_Date) VALUES (generate_card_id(),'$userId','$card_number','$card_holder_name','$cvv','$bank_name','$card_type','$expiry_date')";
  mysqli_query($conn,$sql); 	
    echo "<script>alert('Card added successfully!');</script>";
    header('Location: profile.php');
}
}
//activate and deactivate card status
if(isset($_POST['deactivate_card_status_button']))
{
  $card_id = $_POST['card_id'];
  $sql = "UPDATE tbl_card SET Card_Status = '0' WHERE Card_ID = '$card_id'";
  mysqli_query($conn,$sql); 	
    echo "<script>alert('Card deactivated successfully!');</script>";
    header('Location: profile.php');
}
if(isset($_POST['activate_card_status_button']))
{
  $card_id = $_POST['card_id'];
  $sql = "UPDATE tbl_card SET Card_Status = '1' WHERE Card_ID = '$card_id'";
  mysqli_query($conn,$sql); 	
    echo "<script>alert('Card activated successfully!');</script>";
    header('Location: profile.php');
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
  background-color: rgba(255, 255, 255, 0.9);
  overflow: hidden;
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
  height: 90vh;
  background-color: transparent;
  display: inline-flex;
}
.content-section-left {
  width: 50%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
.card-outer
{    
  width: 82%;
    height: 88%;
    background-color: rgb(255, 255, 255);
    border-radius: 10px;
    justify-content: center;
    border-radius: 10px;
}
.card-heading
{    width: 100%;
    height: 10%;
    color: rgb(256,256,256);
    padding-left: 5%;
    background-color: #2a55e5;
    border-radius: 10px 10px 0px 0px;
    display: flex;
    align-items: center;
}
.input-card-details
{
    width: 100%;
    height: 90%;
    display: flow;
}
.card-type
{
    width: 100%;
    height: 20%;
    background-color: rgb(255, 255, 255);
}
.card-type td {

  text-align: center;
  width: 50%;
}
.input-box
{
    width: 100%;
    height: 10%;
    background-color: rgb(255, 255, 255);
    padding-block:5%;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
}
input[type=text],input[type=date],input[type=number],input[type=password],input[type=email],select
{
    width: 50%;
    height: 6vh;
    border-radius: 5px;
    border-style: none;
    font-size: small;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 550;
    color:rgb(0, 0, 0);
    background-color: rgb(6, 28, 100,0.1);
    transition: 0.4s;
}
input[type=number]
{
    -webkit-appearance: none; 
    -moz-appearance: textfield;
}
.card-details2
{
    width: 100%;
    height: 15%;
    background-color: rgb(255, 255, 255);
    margin: auto;
}
.card-details2 td
{
   
  text-align: center;
}
.input-box button
{
    background-color: rgb(255,216,21,0.7);
    border-radius: 5px;
    color: white;
    width: 180px;
    cursor: pointer;
    margin: 20px;
    padding: 8px 5px;
    border: none;
    font-size: 19px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 600;
    width: 241px;
}
.content-section-right {
  width: 50%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
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
  background-color:#ffe45b;
}
.cards-outer tbody tr:nth-child(odd) {
  background-color: rgb(0,0,0,0.1); /* Set your desired background color */
/* Set text color for better contrast */
}
.cards-outer td,th{
  border-style: solid;
  border-width:1px;
  border-color:rgb(0,0,0,0.3);
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
.expired_button,.expired_button:hover,.expired_button:active
{
  background-color:rgb(0 0 0);
  color:rgb(256,256,256);
  border-style: none;
  width: -webkit-fill-available;
  margin:auto;
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
              /*if($userId == null && $usertype == null) {
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
                }*/
                //if usertype=CU
                if($usertype == 'CU') {
                  //profile page, logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><a href="profile.php"><button class="profile-box" name="show_profile_page"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
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
                  <div class="content-section-left">



                  <form method="POST" style="height:100%;width:100%;display:flex;justify-content:center;align-items:center;">
                  <div class="card-outer">
                    <div class="card-heading">Add new card</div>
                      <div class="input-card-details">
                            <table class="card-type">
                            <tr>                        
                            <td><input type="radio" name="card-type" value="D" required> Debit Card</td>
                            <td><input type="radio" name="card-type" value="C" required> Credit Card</td>
                            </tr>             
                            </table>
                            <div class="input-box">
                                <label for="card-number">Card Number</label>
                                <input type="text" name="card-number" placeholder="Enter card number" pattern="[0-9]{16}" required>
                            </div>
                            <div class="input-box">
                                <label for="card-holder-name">Card Holder Name</label>
                                <input style="margin-left: -6%;" type="text" name="card-holder-name"  min="1" max="20" placeholder="Enter card holder name" required>
                            </div>
                            <div class="input-box">
                                <label for="bank-name">Bank Name</label>
                                <input style="margin-left: 2%;" type="text" name="bank-name"  min="1" max="25" placeholder="Enter bank name" required>
                            </div>
                            <table class="card-details2">
                                <tr>
                                    <td><label for="expiry-date">Expiry Date</label>
                                    <input type="date" name="expiry-date"  placeholder="MM/YY" min="<?php echo date('Y-m-d'); ?>" required></td>

                                    <td><label for="cvv">CVV</label>  
                                    <input type="number" name="cvv" min="100" max="9999" placeholder="Enter CVV" required></td>
                                </tr>
                            </table>
                            <div class="input-box" style="border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                            <button type="submit" name="submit" style="border-radius: 10px;">Add Card</button>
                            </div>
                        </div>
                    </div>
                  </form>
                  </div>

                <div class="content-section-right">

                  <form method="POST" style="height:100%;width:100%;display:flex;justify-content:center;align-items:center;">
                    <div class="card-outer" style="width:90%;">
                      <div class="card-heading">Added Cards</div>
                        <div class="cards-list">
                        <table class="cards-outer">
                         <tr>
                          <th>Card Number</th>
                          <th>Card Holder</th>
                          <th>Bank</th>
                          <th>Expiry Date</th>
                          <th>Action</th>
                         </tr> 

                         <?php
                          $query = "SELECT * FROM tbl_card WHERE Customer_ID = '$userId'";
                          $result = $conn->query($query);

                          if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                //echo "<td>" . $row['Card_No'] . "</td>";
                                echo "<td>XXXX-XXXX-XXXX-" . substr($row['Card_No'], -4) . "</td>";
                                echo "<td>" . $row['Card_Holder_Name'] . "</td>";
                                echo "<td>" . $row['Bank_Name'] . "</td>";
                                echo "<td>" . $row['Expiry_Date'] . "</td>";
                                if($row['Card_Status'] == 1)
                                {
                                  echo "<td><form  method='POST'><input type='hidden' name='card_id' value='". $row['Card_ID'] ."'><button type='submit' class='deactivate_button' name='deactivate_card_status_button'>DEACTIVATE</button></form></td>";
                                }
                                if($row['Card_Status'] == 0)
                                {
                                  echo "<td><form  method='POST'><input type='hidden' name='card_id' value='". $row['Card_ID'] ."'><button type='submit' class='activate_button' name='activate_card_status_button'>ACTIVATE</button></form></td>";
                                }
                                if($row['Card_Status'] == 2)
                                {
                                  echo "<td><form method='POST'><button type='submit' class='expired_button' disabled>EXPIRED</button></form></td>";
                                }
                                echo "</tr>";
                            }                         
                            }
                          else if ($result->num_rows == 0) {
                            echo "<tr>";
                            echo "<td colspan='5' style='text-align:center;'>No cards added yet!</td>";
                            echo "</tr>";
                          }
                         ?>


                         </table>
              </div>

                    </div>
                  </form>
              </div>


                  </div>



                </div>






                        
              </div>
                </div>

            </div>
            </body>
            </html>

