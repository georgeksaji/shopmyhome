<?php
include ("connection.php");
    

session_start();
csrf_verify();

$login_error = '';

if (isset($_POST['loginbutton'])) {
  $email = trim((string) ($_POST['email'] ?? ''));
  $password = (string) ($_POST['password'] ?? '');

  $login = db_one("SELECT Username, Password, User_Type FROM tbl_login WHERE Username = ?", [$email]);
  $authenticated = false;

  if ($login !== null) {
    if (password_verify($password, (string) $login['Password'])) {
      $authenticated = true;
    } elseif (hash_equals((string) $login['Password'], $password)) {
      // Upgrade a legacy plaintext password on the first successful login.
      db_exec(
        "UPDATE tbl_login SET Password = ? WHERE Username = ?",
        [password_hash($password, PASSWORD_DEFAULT), $email]
      );
      $authenticated = true;
    }
  }

  if (!$authenticated) {
    $login_error = 'Invalid credentials!';
  } else {
    $user_type = $login['User_Type'];
    $user_id = null;
    $status = null;

    if ($user_type === 'CU') {
      $row1 = db_one("SELECT Cust_ID, Cust_Status FROM tbl_customer WHERE C_Username = ?", [$email]);
      if ($row1 !== null) {
        $user_id = $row1['Cust_ID'];
        $status = (string) $row1['Cust_Status'];
      }
      if ($user_id === null) {
        $login_error = 'No customer profile found for this account.';
      } elseif ($status === '0') {
        $login_error = 'Your account has been suspended.';
      }
    } elseif ($user_type === 'ST') {
      $row1 = db_one("SELECT Staff_ID, Staff_Status FROM tbl_staff WHERE Staff_Username = ?", [$email]);
      if ($row1 !== null) {
        $user_id = $row1['Staff_ID'];
        $status = (string) $row1['Staff_Status'];
      }
      if ($user_id === null) {
        $login_error = 'No staff profile found for this account.';
      } elseif ($status === '0') {
        $login_error = 'Your account has been suspended. Please contact the administrator.';
      }
    } elseif ($user_type === 'CR') {
      $row1 = db_one("SELECT Cour_ID, Cour_Status FROM tbl_courier WHERE Cour_Username = ?", [$email]);
      if ($row1 !== null) {
        $user_id = $row1['Cour_ID'];
        $status = (string) $row1['Cour_Status'];
      }
      if ($user_id === null) {
        $login_error = 'No courier profile found for this account.';
      } elseif ($status === '0') {
        $login_error = 'Your account has been suspended. Please contact the administrator.';
      } elseif ($status === '3') {
        $login_error = 'Your service has been terminated permanently. Please contact the administrator.';
      } elseif ($status !== '1' && $status !== '2') {
        $login_error = 'Your account is not active. Please contact the administrator.';
      }
    } elseif ($user_type === 'AD') {
      $row1 = db_one("SELECT Staff_ID FROM tbl_staff WHERE Staff_Username = ?", [$email]);
      if ($row1 !== null && $row1['Staff_ID'] === 'ST00001') {
        $user_id = 'ST00001';
      } else {
        $login_error = 'This account is not an administrator.';
      }
    } else {
      $login_error = 'Unknown account type.';
    }

    if ($login_error === '' && $user_id !== null) {
      session_regenerate_id(true);
      $_SESSION['User_ID'] = $user_id;
      $_SESSION['User_Type'] = $user_type;
      header('Location: index.php');
      exit;
    }
  }

  if ($login_error !== '') {
    echo '<script>alert(' . json_encode($login_error) . ');</script>';
  }
}

?>

<html>
<head>
  
<link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">
  <title>Customer Login</title>
  <style>
    body {
      background-image: url("assets/images/backgrounds/background.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      overflow: hidden;
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
            <input type="password" id="password" name="password" placeholder="Enter password" minlength="8" maxlength="72" required title="Minimum 8 and maximum 72 characters required.">
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