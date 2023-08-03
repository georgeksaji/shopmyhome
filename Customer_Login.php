<?php
include ("connection.php");

if(isset($_POST['loginbutton']))
{
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM tbl_login WHERE Username = '$email' AND User_Type = 'CU'";
  $result1 = mysqli_query($conn, $sql);
  $num1 = mysqli_num_rows($result1);
  if($num1 == 1) 
  {
    $sql = "SELECT * FROM tbl_login WHERE Username = '$email' AND Password = '$password' AND User_Type = 'CU'";
    $result = mysqli_query($conn, $sql);
    $num2 = mysqli_num_rows($result);
    if($num2 == 1) 
    {
      echo "<script>alert('Login successful!');</script>";
      header("Location: Home_page.php");
    }
    else if($num2 == 0)
    {
      echo "<script>alert('Incorrect password!');</script>";
    } 
  }
  else
  {
    echo "<script>alert('Username doesn\'t exist!');</script>";
  } 
}
?>
<html>
<head>
  <title>Customer Login</title>
  <style>
    body {
      background-image: url("background1.jpg");
      background-size: cover;
      background-repeat: no-repeat;
    }

    .outercontainer {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .registration-box {
    background-color: rgb(0,0,0,0.5);
    padding: 18px;
    border-radius: 10px;
    width: fit-content;
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
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: auto;
      color: white;
      margin-bottom: 20px;
      font-weight: 500;
      font-size: 180%;
    }

    .registration-box input[type="password"],
    .registration-box input[type="email"]
    {
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
    color: white;
    text-align: initial;
    }



    .registration-box input[type="email"]:focus,
    .registration-box input[type="password"]:focus
    {
      background-color: rgb(0,0,0,0.5);
      outline: none;
      border-color:rgba(145, 204, 234, 0.992);
      box-shadow: 0 0 10px #9ecaed;
    }

    ::placeholder{
      color: white;
    }

   label
   {
    color: rgba(255, 255, 255, 0.9);
    text-align: left;
    font-size: medium;
    display: block;
    margin-left: 20px;
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


   .login-button {
    background-color: rgb(255,216,21,0.6);
    border-radius: 5px;
    color: white;
    width: 180px;
    cursor: pointer;
    margin: 20px;
    padding: 8px 5px;
    border: none;
    font-size: 19px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 500;
    width: 241px;
    }

    .login-button:hover {
      background-color: rgb(255,216,21,0.9);
      transition: 0.2s;
    }

    .signup-button {
    background-color: rgb(83,178,212,0.6);
    border-radius: 5px;
    color: white;
    width: 180px;
    cursor: pointer;
    padding: 8px 5px;
    border: none;
    font-size: 19px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 500;
    width: 241px;
    }

    .signup-button:hover {
      background-color: rgb(83,178,212,0.9);
      transition: 0.2s;
    }


    p{
      color: whitesmoke;
    }

  </style>
  </head>
<body>
  <div class="outercontainer">
    <div class="registration-box">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Login</div>
      <form action="" method="POST">
        <div class="registration-form">
          <div class="registration-form-left">
            
            <label for="email">Username</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
              
            <label for="passwordright">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" minlength="6" required title="Minimum 6 characters required.">
        </div>
        <button class="login-button" type="submit" name="loginbutton">Login</button>
        </form>
       </div>
    <div class="signup-redirect">
      <p>Dont have an account? Register here.</p>
      <a href="Customer_Sign_Up.php"><button class="signup-button" type="submit">Create your Account</button></a>
    </div>
  </div>
</body>
</html>