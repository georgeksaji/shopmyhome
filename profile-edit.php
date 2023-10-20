<?php
include ("connection.php");

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
echo $userId;
echo $usertype;

$updatequery = mysqli_query($conn, "SELECT * FROM tbl_customer WHERE Cust_ID = '$userId'");
$db_data = mysqli_fetch_array($updatequery);

if (isset($_POST['submit'])) 
{
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $phoneNumber = $_POST['phoneNumber'];
    $houseName = $_POST['houseName'];
    $street = $_POST['street'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    
      //$update1 = "UPDATE tbl_customer SET Cust_Fname='$firstName',Cust_Lname='$lastName' Cust_Phone='$phoneNumber',Cust_Gender='$gender',Cust_Hname='$houseName',Cust_Street='$street',Cust_Dist='$district',Cust_Ut='$state',Cust_Pin='$pincode' WHERE Cust_ID='$userId'";
      $update1 = "UPDATE tbl_customer SET Cust_Fname='$firstName', Cust_Lname='$lastName', Cust_Phone='$phoneNumber', Cust_Gender='$gender', Cust_Hname='$houseName', Cust_Street='$street', Cust_Dist='$district', State_Ut='$state', Cust_Pin='$pincode' WHERE Cust_ID='$userId';";
      mysqli_query($conn, $update1);

      echo "<script>alert('Customer details updated successfully!');</script>";
      header('Location: view-orders.php');
  }
?>


<html>
<head>
    <link rel="icon" type="image/x-icon" href="favicon.png">
  <title>Update Customer</title>
  <style>
    body {
      background-image: url("background.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-position:center;
      overflow-y: hidden;
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
    padding-bottom: 4px;
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
    .registration-box select {
    width: 335px;
    padding: 7px;
    margin-left: 16px;
    margin-right: 16px;
    margin-bottom: 11px;
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

   label
   {
    color: rgba(0,0,0);
    text-align: left;
    font-size: medium;
    display: block;
    margin-left: 20px;
   }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
   -webkit-appearance: none; 
    margin: 0; 
    }

    input[type=number] {
    -moz-appearance: textfield;
    }

    .registration-form-left,
    .registration-form-right
    {
      flex-basis: 45%; 
    }

    option
    {
      background-color:rgb(256,256,256,0.7);
    }


    .registration-box button {
    background-color: rgb(255,216,21,0.9);
    border-radius: 5px;
    color: rgb(250,250,250);
    width: 180px;
    margin-top: 3%;
    cursor: pointer;
    padding: 8px 5px;
    border: none;
    font-size: 19px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 600;
    width: 241px;
    
    }


    .registration-box button:hover {
      background-color: rgb(83,178,212)
      transition: 0.2s;
      
    }


  </style>
  </head>
<body>
  <div class="outercontainer">
    <div class="registration-box">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Update Profile</div>
      <form action="" method="POST">
        <div class="registration-form">
          <div class="registration-form-left">
              <label for="firstName">Customer Name</label>
              <input type="text" id="firstName" name="firstName" placeholder="First name" value="<?php echo $db_data['Cust_Fname']; ?>" required>

              <label for="gender">Gender</label>
              <select id="gender" name="gender" required>
                <option value="" disabled selected hidden>Select gender</option>
                <option value="M" <?php if ($db_data['Cust_Gender'] === 'M') echo 'selected'; ?>>Male</option>
                <option value="F" <?php if ($db_data['Cust_Gender'] === 'F') echo 'selected'; ?>>Female</option>
                <option value="O" <?php if ($db_data['Cust_Gender'] === 'O') echo 'selected'; ?>>Others</option>
              </select>

              <label for="address">Street</label>
              <input type="text" id="address" name="street" placeholder="Street" value="<?php echo $db_data['Cust_Street']; ?>" required>

              <label for="pincode">Pincode</label>
              <input type="number" id="pincode" name="pincode" value="<?php echo $db_data['Cust_Pin']; ?>" placeholder="Enter 6 digit pincode" pattern="[0-9]{6}" required title="Enter a valid pincode">
              
              <label for="state">State/UT</label>
            <input type="text" id="state" name="state" value="<?php echo $db_data['State_Ut']; ?>" placeholder="State / Union Territory" required>
 
            
              </div>

          <div class="registration-form-right">

            <label for="lastName"><!--Enter your name:--><br></label>
            <input type="text" id="lastName" name="lastName" value="<?php echo $db_data['Cust_Lname']; ?>" placeholder="Last name">
  
            <label for="phoneNumber">Phone number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" value="<?php echo $db_data['Cust_Phone']; ?>" placeholder="Enter a 10 digit phone number" pattern="[0-9]{10}" required title="Enter a valid phone number">

            <label for="houseName">House name</label>
            <input type="text" id="houseName" name="houseName" value="<?php echo $db_data['Cust_Hname']; ?>" placeholder="House, apartment, suit, etc." required>
          
            <label for="district">District</label>
            <input type="text" id="district" name="district" value="<?php echo $db_data['Cust_Dist']; ?>" placeholder="District" required>

           
    
            <button type="submit" name="submit">Update</button>
          
            </div>
        </div>
       
      </form>
    </div>
  </div>
</body>
