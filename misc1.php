<?php
include('connection.php');

$result=mysqli_query($conn,"SELECT * FROM tbl_customer");
$cutomer_num=mysqli_num_rows($result);

$result=mysqli_query($conn,"SELECT * FROM tbl_staff");
$staff_num=mysqli_num_rows($result);

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
   
<!--google fonts_--> 
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alata&family=Belanosima&family=Josefin+Sans:wght@700&display=swap" rel="stylesheet">
<style>
    body{
    background-color: rgb(255, 255, 255);
    overflow-y: hidden;
    margin:0px;
    padding:0px;
}
.outer-div
{
    width:100%;
    height:100%;
}
.side-navigation
{
    height: 100vh;
    width:15%;
    float: left;
    background-color:rgb(193, 231, 249);
    position: fixed;
}
.side-navigation h2
{
    text-align: center;
    margin-top: 10%;
    margin-bottom: 15%;
    color: rgb(54, 46, 212);
    font-family: Josefin Sans, sans-serif;
    font-size: 25px;
}

.side-navigation-list
{
  display: grid;
  align-items: center;
  justify-content: center;
  margin-top: 10%;
}

.buttons
{
  margin-bottom: 4%;
}

.top-navigation
{
    height: 9vh;
    width:85%;
    margin-left: 15%;
    background-color:rgb(193, 231, 249);
    position: fixed;
}
.logo
{
    height: 10%;
    width: 100%;
    background-image:url('picture3.png');
    margin-top: 2%;
    background-size: cover;
}

.navigation-top-item {
  height: fit-content;
  width: fit-content;
  float: right;
  margin-top: 1%;
  padding-right:5%;
}

.home-box{
  font-size: medium;
  height: 40px;
  width: 80px;
  padding:2px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 550;
  background-color: rgb(0,0,0);
  display: flex;
  justify-content: center;
  align-items: center;
  transition: 0.3s;
}
a
{
  text-decoration: none;
  color: rgb(256,256,256);
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


.side-navigation-option
{
    display: flex;
    justify-content: center;
    align-items: center; 
    margin: auto;
    height: fit-content;
}

.section
{
    height:100vh;
    width:85%;
    margin-left: 15%; 
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
    display: inline-flex;
    height: 100%;
    width: 100%;  
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
.buttons
{
  background-color: rgb(256,256,256);
  color: rgb(0,0,0);
  border-style: none;
  transition: 0.3s;
  margin-bottom: 5%;
}
.buttons:hover
{
  background-color: rgb(45, 88, 233);
  color: rgb(256,256,256);
}

.staff-content,.vendor-content,.customer-content,.product-content,.courier-content{
  display: flex;
  justify-content: center;
  align-items: center;
  background-color:rgb(256,256,256)
}

/*Staff css content*/
.staff-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(55, 24, 95);/*remove colour after development */
}

/*Vendor css content*/
.vendor-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(156, 24, 95);
}

/*Courier css content*/
.courier-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(55, 24, 95);
  
}
.vendor-content-inner-top{
height:20%;
width:100%;
background-color:black;
padding:3%;
}
.vendor-content-inner-bottom
{

}

/*Customer css content*/
.customer-content-inner
{   
    margin-top: 10vh;
    height: 80vh;
    width: 90%;
    background-color: rgb(55, 24, 95); 
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
        });

  </script>
<!--script-->
    <div class="outer-div">
        <div class="side-navigation">   
            <!--<h2>ADMIN PANEL</h2>-->
            <div class="logo"></div>
              <div class="side-navigation-list">
              
                <button type="button" class="btn btn-primary buttons" id="buttonToClick" onclick="scrollToSection('.dashboard-content')">Dashboard</button>
                <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.staff-content')">Manage Staff</button>
                <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.customer-content')">Manage Customer</button>
                <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.vendor-content')">Manage Vendor</button>
                <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.courier-content')">Manage Courier</button>
                <button type="button" class="btn btn-primary buttons" onclick="scrollToSection('.product-content')">Manage Products</button>
              </div>
          </div>
          
          <div class="top-navigation">
          <div class="navigation-top-item">
          <div class="home-box"><a href="...">Home</a></div></div>
          </div>
      
        <!--dashboard content goes here-->
          <div class="dashboard-content section">
          <div class="cards-outer">
          <div class="card1"><p class="card-text-heading">Total Customers</p><p class="card-text-content"><?php echo $cutomer_num ?><p></div>
          <div class="card2"><p class="card-text-heading">Total Staff</p><p class="card-text-content"><?php echo $staff_num ?><p></p></div>
          <div class="card3"><p class="card-text-heading">Total Orders</p><p class="card-text-content">7<p></p></div>
          <div class="card4"><p class="card-text-heading">Total Profit</p><p class="card-text-content">Rs.9<p></p></div>
          </div>
          </div><!--dashboard content completed-->

          <!--staff content goes here-->
            <div class="staff-content section">
              <div class="staff-content-inner"></div>
            </div>
            <!--staff content completed-->
            
            <!--vendor content goes here-->
            <div class="vendor-content section">
            <div class="vendor-content-inner">
            <div class="vendor-content-inner-top">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary me-md-2" type="button">Add Vendors</button>
                </div>
            <div class="vendor-content-inner-bottom">

            </div>
            </div>
            </div>
            </div>
            <!--vendor content completed-->

            <!--courier content goes here-->
            <div class="courier-content section">
              <div class="courier-content-inner"></div>
            </div>
            <!--courier content completed-->

            <!--customer content goes here-->
            <div class="customer-content section">
              <div class="customer-content-inner"></div>
            </div>
            <!--customer content completed-->

            <!--product content goes here-->
            <div class="product-content section">
              <div class="product-content-inner"></div>
            </div>
            <!--product content completed-->
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>



<?php

$dbhost='localhost';
$dbuser='root';
$dbpass='';
$db='retech';
$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$db);
if ($conn->connect_error) 
{
    die("Connection failed:" . $conn->connect_error);
}

session_start();

if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $pas = substr($pass, 0, 10);

        $emailResult = mysqli_query($conn, "SELECT Username FROM `Login` WHERE username = '$email'");
        $passResult = mysqli_query($conn, "SELECT Password FROM `Login` WHERE username = '$email'");

        if (mysqli_num_rows($emailResult) > 0) {
            $emailRow = mysqli_fetch_assoc($emailResult);
            $email = $emailRow['Username'];
      
            if (mysqli_num_rows($passResult) > 0) {
                $passRow = mysqli_fetch_assoc($passResult);
                $ps = $passRow['Password'];
      
                if ($ps !== $pas) {
                    $message[] = '*Your password is incorrect*';
                }
                else
                {
                    $selectt = mysqli_query($conn, "SELECT * FROM `Login` WHERE Username = '$email'");
                    $user = mysqli_fetch_assoc($selectt);
                    $user_type = $user['User_type'];

                    if($user_type == 'User')
                    {
                     $selectt = mysqli_query($conn, "SELECT * FROM `Customer` WHERE Cust_Username = '$email'");
                     $user = mysqli_fetch_assoc($selectt);
                        $_SESSION['userid'] = $user['Customer_id'];
                        $_SESSION['usertp'] = 'User';
                        header('location:home.php');
                    }
                    elseif($user_type == 'Staff')
                    {
                     $selectt = mysqli_query($conn, "SELECT * FROM `Staff` WHERE S_Username = '$email'");
                     $user = mysqli_fetch_assoc($selectt);
                        $_SESSION['userid'] = $user['Staff_id'];
                        $_SESSION['usertp'] = 'Staff'; 
                        header('location:home.php');
                    }
                    elseif($user_type == 'Admin')
                    {
                     $selectt = mysqli_query($conn, "SELECT * FROM `Staff` WHERE S_Username = '$email'");
                     $user = mysqli_fetch_assoc($selectt);
                        $_SESSION['userid'] = $user['Staff_id'];
                        $_SESSION['usertp'] = 'Admin'; 
                        header('location:home.php');
                    }
                    
                }
            } 
        } else {
            $msg[] = '*We cannot find an account with that email address*';
        }
    }
?>
