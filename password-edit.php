<?php
include ("connection.php");

session_start();
csrf_verify();
require_login();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

$accountUsername = null;
if ($usertype === 'CU') {
    $accountUsername = db_val("SELECT C_Username FROM tbl_customer WHERE Cust_ID = ?", [$userId]);
} elseif ($usertype === 'ST' || $usertype === 'AD') {
    $accountUsername = db_val("SELECT Staff_Username FROM tbl_staff WHERE Staff_ID = ?", [$userId]);
} elseif ($usertype === 'CR') {
    $accountUsername = db_val("SELECT Cour_Username FROM tbl_courier WHERE Cour_ID = ?", [$userId]);
}

if ($accountUsername === null) {
    http_response_code(403);
    exit('Account not found.');
}

if (isset($_POST['submit'])) 
{
    $currentPassword = (string)($_POST['current_password'] ?? '');
    $password1 = (string)($_POST['password1'] ?? '');
    $password2 = (string)($_POST['password2'] ?? '');

    $storedHash = db_val("SELECT Password FROM tbl_login WHERE Username = ?", [$accountUsername]);

    if ($storedHash === null || !password_verify($currentPassword, (string) $storedHash)) {
        echo "<script>alert('Current password is incorrect.');</script>";
    } elseif ($password1 !== $password2) {
        echo "<script>alert('New passwords do not match.');</script>";
    } elseif (strlen($password1) < 8 || strlen($password1) > 72) {
        echo "<script>alert('Password must be between 8 and 72 characters.');</script>";
    } else {
        db_exec(
            "UPDATE tbl_login SET Password = ? WHERE Username = ?",
            [password_hash($password1, PASSWORD_DEFAULT), $accountUsername]
        );
        $redirect = $usertype === 'CR' ? 'courier.php' : ($usertype === 'CU' ? 'profile.php' : 'profile_staff.php');
        header('Location: ' . $redirect);
        exit;
    }
}

?>
<html>
<head>
  
<link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">
  <title>Login & Security</title>
  <style>
    body {
      background-image: url("assets/images/backgrounds/background.png");
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
      background-image: url(assets/images/banners/Picture3.png);
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
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" minlength="8" maxlength="72" required autocomplete="current-password">

            <label for="password">New Password</label>
            <input type="password" id="password" name="password1" placeholder="Enter a new password" minlength="8" maxlength="72" required autocomplete="new-password" title="Minimum 8 and maximum 72 characters.">

            <label for="password2">Confirm Password</label>
            <input type="password" id="password2" name="password2" placeholder="Confirm new password" minlength="8" maxlength="72" required autocomplete="new-password" title="Minimum 8 and maximum 72 characters.">

            <button type="submit" name="submit">Update</button>
          
            </div>
        </div>
       
      </form>
    </div>
  </div>
</body>
          </html>