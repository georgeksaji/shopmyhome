<?php
include ("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

if(isset($_POST['submit']))
{
  $name = $_POST['name'];
  $phoneNumber = $_POST['phoneNumber'];
  $buildingName = $_POST['buildingName'];
  $pincode = $_POST['pincode'];
  $state = $_POST['state'];
  $email = $_POST['email'];
  $street = $_POST['street'];
  $district = $_POST['district'];

  $sql = "SELECT * FROM tbl_vendor WHERE Vendor_Username = '$email'";
  $result = mysqli_query($conn, $sql);
  $num = mysqli_num_rows($result);
  if($num > 0) 
  {
    echo "<script>alert('User already exists!');</script>";
  } 
  else
  {
    $insert1= "INSERT INTO tbl_vendor(Vendor_ID,Vendor_Username,Vendor_Registrant_Id,Vendor_Name,Vendor_Phno,Vendor_Hname,Vendor_Street,Vendor_Dist,State_Ut,Vendor_Pin)VAlUES(generate_vendor_id(),'$email','$userId','$name','$phoneNumber','$buildingName','$street','$district','$state','$pincode')";
    mysqli_query($conn,$insert1);
    echo "<script>alert('Vendor registered successfully!');</script>";
    //header("Location: Login.php");
  }
}
?>
<html>
<head>
  <title>Register Vendor</title>
  <style>
    body {
      background-image: url("background.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: fixed;
    }

    .outercontainer {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .registration-box {
    background-color: rgba(256,256,256,0.9);
    padding: 18px;
    border-radius: 10px;
    width: min-content;
    text-align: center;
    transition: 0.5s;
    }

    .registration-box:hover {
    transform: scale(1.01);
    
    }

    .registration-box-logo {
      background-image: url(Picture3.png);
      margin: auto;
      background-size: contain;
      background-repeat: no-repeat;
      height:80px;
      width:263px;

    }

    .registration-box-heading {
      font-family:Times New Roman, Times, serif;
      margin: auto;
      color: rgb(50, 131, 212);
      margin-bottom: 20px;
      font-weight: 800;
      font-size: 180%;
    }

    .registration-form
    {
      display: flex;
    }

    .registration-box input[type="text"],
    .registration-box input[type="email"],
    .registration-box input[type="tel"],
    .registration-box input[type="number"],
    .registration-box input[type="password"],
     .registration-box select{
    width: 344px;
    padding: 7px;
    margin-left: 15px;
    margin-right: 15px;
    margin-bottom: 15px;
   border-color: rgb(82,176,210);
    background-color: rgb(82,176,210,0.1);
    border-width: 1px;
    border-style: double;
    border-radius: 5px;
    color: rgb(0,0,0);
    text-align: initial;
    }


    .registration-box input[type="text"]:focus,
    .registration-box input[type="email"]:focus,
    .registration-box input[type="tel"]:focus,
    .registration-box input[type="password"]:focus,
    .registration-box input[type="number"]:focus
    {
      background-color: rgb(120, 181, 225,0.5);
      outline: none;
      border-color:rgba(145, 204, 234, 0.992);
      color:rgb(0,0,0);
      box-shadow: 0 0 10px #9ecaed;
    }

    ::placeholder{
      color: rgb(0,0,0,0.8);
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }

    input[type=number]
     {
    -moz-appearance: textfield;
    }

   label
   {
    color: rgba(0,0,0);
    text-align: left;
    font-size: medium;
    display: block;
    margin-left: 20px;
   }


    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    .registration-form-left,
    .registration-form-right
    {
      flex-basis: 45%; 
    }

    option
    {
      background-color:rgb(0,0,0,0.7);
    }


    .registration-box button {
  background-color: rgb(255,216,21,0.9);
    border-radius: 5px;
    color: white;
    width: 180px;
    cursor: pointer;
    margin-top: 15px;
    padding: 8px 5px;
    border: none;
    font-size: 19px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 500;
    }


    .registration-box button:hover {
      background-color: rgb(83,178,212)
      transition: 0.2s;
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
  <div class="outercontainer">
    <div class="registration-box">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Register Vendor</div>
      <form action="" method="POST">
        <div class="registration-form">
          <div class="registration-form-left">
              <label for="firstName">Vendor name</label>
              <input type="text" id="firstName" name="name" placeholder="First name" required>

              <label for="phoneNumber">Phone number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter a 10 digit phone number" pattern="[0-9]{10}" required title="Enter a valid phone number">
 
              <label for="pincode">Pincode</label>
              <input type="text" id="pincode" name="pincode" placeholder="Enter 6 digit pincode" pattern="[0-9]{6}" required title="Enter a valid pincode">

            <label for="district">District</label>
            <input type="text" id="district" name="district" placeholder="District" required>

          
          </div>

          <div class="registration-form-right">

            <label for="email">Email / Username</label>
            <input type="email" id="email" name="email" placeholder="Email" required>

            <label for="houseName">Building name</label>
            <input type="text" id="buildingName" name="buildingName" placeholder="Building, apartment, suit, etc." required>
             
            <label for="address">Street</label>
             <input type="text" id="address" name="street" placeholder="Street" required>

             <label for="state">State</label>
            <input type="text" id="state" name="state" placeholder="State" required>

            </div>
        </div>
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>
  </div>
</body>
