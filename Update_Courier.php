<?php
include ("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
$updateid = $_SESSION['Update_ID'];
//echo $updateid;

if(isset($_POST['submit']))
{
  $name = $_POST['name'];
  $phoneNumber = $_POST['phoneNumber'];
  $buildingName = $_POST['buildingName'];
  $pincode = $_POST['pincode'];
  $state = $_POST['state'];
  $street = $_POST['street'];
  $district = $_POST['district'];
  $passwordright = $_POST['passwordright'];
  $passwordleft = $_POST['passwordleft'];
  //select Cour_Username from tbl courier with Cour_ID = $updateid
  $sql = "SELECT Cour_Username FROM tbl_courier WHERE Cour_ID = '$updateid'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $email = $row['Cour_Username'];


  if($passwordleft == $passwordright)
  {
    $sql = "SELECT * FROM tbl_login WHERE Username = '$email'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if($num == 1) //if email already exists
    {
      $sql = "UPDATE tbl_courier SET Cour_Name = '$name', Cour_Phone = '$phoneNumber', Cour_Building_name = '$buildingName', Cour_Pin = '$pincode', Cour_State_ut = '$state', Cour_Street = '$street', Cour_Dist = '$district' WHERE Cour_ID = '$updateid'";
      mysqli_query($conn, $sql);
      $sql = "UPDATE tbl_login SET Password = '$passwordleft' WHERE Username = '$email'";
      mysqli_query($conn, $sql);
      echo "<script>alert('Courier updated successfully!');</script>";
      if($usertype == "AD" && $userId == "ST00001")
      {
        header("location: admin.php");
      }
      else if($usertype == "ST")
      {
        header("location: staff.php");
      }
    }
    else //if email does not exist
    {
      echo "<script>alert('More than one user in same mail address');</script>";
    }
}
else
{
  echo "<script>alert('Passwords do not match');</script>";
}
}
?>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="favicon.png">
  <title>Update Courier</title>
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
      <div class="registration-box-heading">Update Courier Partner</div>
      <form action="" method="POST">
        <div class="registration-form">
          <div class="registration-form-left">
              <label for="Name">Courier Partner name</label>
              <?php
    //updateid
    $sql = "SELECT * FROM tbl_courier WHERE Cour_ID = '$updateid'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $name = $row['Cour_Name'];
    $phoneNumber = $row['Cour_Phone'];
    $buildingName = $row['Cour_Building_name'];
    $pincode = $row['Cour_Pin'];
    $state = $row['Cour_State_ut'];
    $email = $row['Cour_Username'];
    $street = $row['Cour_Street'];
    $district = $row['Cour_Dist'];
    //select password from login table
    $sql = "SELECT * FROM tbl_login WHERE Username = '$email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $password = $row['Password'];
    ?>

              <input type="text" id="firstName" name="name" value="<?php echo $name; ?>" placeholder="Update Name" maxlength="15" required>

              <label for="phoneNumber">Phone number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumber; ?>" placeholder="Enter a 10 digit phone number" pattern="[0-9]{10}" required title="Enter a valid phone number">
 
              <label for="pincode">Pincode</label>
              <input type="text" id="pincode" name="pincode" value="<?php echo $pincode; ?>" placeholder="Enter 6 digit pincode" pattern="[0-9]{6}" required title="Enter a valid pincode">

              
            <label for="district">District</label>
            <input type="text" id="district" name="district" value="<?php echo $district; ?>" placeholder="District" required>

            <label for="passwordright">Create password</label>
              <input type="password" id="passwordright" name="passwordright" value="<?php echo $password; ?>" placeholder="Enter password" minlength="4" maxlength="9" required title="Minimum 4 and Maximum 7 characters .">
s
          
          </div>

          <div class="registration-form-right">

            <label for="houseName">Building name</label>
            <input type="text" id="buildingName" name="buildingName" value="<?php echo $buildingName; ?>" placeholder="Building, apartment, suit, etc." required>

            <label for="address">Street</label>
             <input type="text" id="address" name="street" value="<?php echo $street; ?>" placeholder="Street" required>

            <label for="state">State</label>
            <input type="text" id="state" name="state" value="<?php echo $state; ?>" placeholder="State" required>

            <label for="passwordleft">Confirm password</label>
              <input type="password" id="passwordleft" name="passwordleft" value="<?php echo $password; ?>" placeholder="Confirm password" minlength="4" maxlength="9" required title="Minimum 4 and Maximum 7 characters required.">

              <button type="submit" name="submit">Submit</button>

            </div>
        </div>
       
      </form>
    </div>
  </div>
</body>
