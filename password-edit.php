<?php
include ("connection.php");

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];


if (isset($_POST['submit'])) 
{
    $db_data1 = $_POST['submit'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
      if($password1 == $password2) 
      {
      $updatepas = "UPDATE tbl_login SET Password='$password1' WHERE Username='$db_data1'";
      mysqli_query($conn, $updatepas);
      
      echo "<script>alert('Password updated successfully!');</script>";
      header('Location: profile.php');
      }
      else 
      {
      echo "<script>alert('Error! Passwords not same.');</script>";
      }
  }

?>
<html>
<head>
  
<link rel="icon" type="image/x-icon" href="favicon.png">
  <title>Login & Security</title>
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
      <div class="registration-box-heading">Update Password</div>
      <form action="" method="POST">
        <div class="registration-form">
          <div class="registration-form-left">
            <?php
            if($usertype=='CU')
            {
                //select details from tbl_customer
                $updatequery = mysqli_query($conn, "SELECT * FROM tbl_customer WHERE Cust_ID = '$userId'");
                $db_data = mysqli_fetch_array($updatequery);
                $db_data1 = $db_data['C_Username'];
                //$db_phone = $db_data['Cust_Phone'];
                $passwordquery = mysqli_query($conn, "SELECT Password FROM tbl_login WHERE Username = '$db_data1'");
                $passwordData = mysqli_fetch_assoc($passwordquery);
                $db_password = $passwordData['Password'];
            }
            else if($usertype=='ST')
            {
                //select details from tbl_staff
                $updatequery = mysqli_query($conn, "SELECT * FROM tbl_staff WHERE Staff_ID = '$userId'");
                $db_data = mysqli_fetch_array($updatequery);
                $db_data1 = $db_data['Staff_Username'];
                //$db_phone = $db_data['Staff_Phone'];
                $passwordquery = mysqli_query($conn, "SELECT Password FROM tbl_login WHERE Username = '$db_data1'");
                $passwordData = mysqli_fetch_assoc($passwordquery);
                $db_password = $passwordData['Password'];
            }
            else if($usertype=='CR')
            {
                //select details from tbl_courier
                $updatequery = mysqli_query($conn, "SELECT * FROM tbl_courier WHERE Courier_ID = '$userId'");
                $db_data = mysqli_fetch_array($updatequery);
                $db_data1 = $db_data['Cour_Username'];
                //$db_phone = $db_data['Cour_Phone'];
                $passwordquery = mysqli_query($conn, "SELECT Password FROM tbl_login WHERE Username = '$db_data1'");
                $passwordData = mysqli_fetch_assoc($passwordquery);
                $db_password = $passwordData['Password'];
                
            }

?>
               <label for="password">Current Password</label>
              <input type="text"  value="<?php echo $db_password ?>" >


              <label for="password">New Password</label>
              <input type="password" id="password" name="password1" placeholder="Enter a new Password" value="<?php echo $db_password ?>" minlength="4" maxlength="9" required title="Minimum 4 and Maximum 9 characters.">

              <label for="password">Confirm Password</label>
              <input type="password" id="password" name="password2" placeholder="Confirm Password" value="<?php echo $db_password ?>" minlength="4" maxlength="9" required title="Minimum 4 and Maximum 9 characters.">

              <button type="submit"  value="<?php echo $db_data1 ?>"  name="submit">Update</button>
          
            </div>
        </div>
       
      </form>
    </div>
  </div>
</body>
          </html>