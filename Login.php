<?php
include ("connection.php");

if (isset($_POST['loginbutton'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM tbl_login WHERE Username = '$email' AND Password = '$password'";
  $result = mysqli_query($conn, $sql);
  $num = mysqli_num_rows($result);

  if ($num == 1) {
    $row = mysqli_fetch_assoc($result);
    $user_type = $row['User_Type'];

    if ($user_type == 'CU') {
      echo ('Customer');
      $sql1 = "SELECT Cust_ID FROM tbl_customer WHERE C_Username = '$email'";
      $result1 = mysqli_query($conn, $sql1);

      if ($result1) {
        $user_id = mysqli_fetch_assoc($result1)['Cust_ID'];
        $_SESSION['User_ID'] = $user_id;
        echo "<script>alert('Login successful!');</script>";
        header("Location: session.php");
        exit(); // It's a good practice to add an exit after redirection.
      }
    } else if ($user_type == 'ST') {
      echo ('Staff');
      $sql1 = "SELECT Staff_ID FROM tbl_staff WHERE Staff_Username = '$email'";
      $result1 = mysqli_query($conn, $sql1);

      if ($result1) {
        $user_id = mysqli_fetch_assoc($result1)['Staff_ID'];
        $_SESSION['User_ID'] = $user_id;
        echo "<script>alert('Login successful!');</script>";
        header("Location: session.php");
        exit(); // It's a good practice to add an exit after redirection.
      }
    } else if ($user_type == 'AD') {
      echo ('Admin');
      $sql1 = "SELECT Admin_ID FROM tbl_Admin WHERE Username = '$email'";
      $result1 = mysqli_query($conn, $sql1);

      if ($result1) {
        $user_id = mysqli_fetch_assoc($result1)['Admin_ID'];
        $_SESSION['User_ID'] = $user_id;
        echo "<script>alert('Admin Login successful!');</script>";
        header("Location: session.php");
        exit(); // It's a good practice to add an exit after redirection.
      }
    }
  } else {
    echo "<script>alert('Invalid Credentials!');</script>";
  }
}

?>

<html>
<head>
  <title>Customer Login</title>
  <style>
    body {
      background-image: url("background.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
    }

    .outercontainer {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .registration-box {
	background-color: rgba(256,256,256,0.9);
	padding: 55px;
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
      font-family:Times New Roman, Times, serif;
      margin: auto;
      color: rgb(50, 131, 212);
      margin-bottom: 20px;
      font-weight: 800;
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
    color:rgb(50, 131, 212);
  
    text-align: initial;
    }



    .registration-box input[type="email"]:focus,
    .registration-box input[type="password"]:focus
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

    .login-button:hover {
      background-color: rgb(255,216,21);
      transition: 0.2s;
    }

    .signup-button {
    background-color: rgb(83,178,212,0.7);
    border-radius: 5px;
    color: white;
    width: 180px;
    cursor: pointer;
    padding: 8px 5px;
    border: none;
    font-size: 19px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 600;
    width: 241px;
    }

    .signup-button:hover {
      background-color: rgb(83,178,212);
      transition: 0.2s;
    }


    p{
      color:rgb(50, 131, 212);
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