
<?php
include ("connection.php");

session_start();
csrf_verify();
require_role(['AD','ST']);
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

if(isset($_POST['submit']))
{
  $firstName = trim((string)($_POST['firstName'] ?? ''));
  $lastName = trim((string)($_POST['lastName'] ?? ''));
  $email = trim((string)($_POST['email'] ?? ''));
  $gender = (string)($_POST['gender'] ?? '');
  $phoneNumber = trim((string)($_POST['phoneNumber'] ?? ''));
  $houseName = trim((string)($_POST['houseName'] ?? ''));
  $street = trim((string)($_POST['street'] ?? ''));
  $district = trim((string)($_POST['district'] ?? ''));
  $state = trim((string)($_POST['state'] ?? ''));
  $pincode = trim((string)($_POST['pincode'] ?? ''));
  $designation = trim((string)($_POST['designation'] ?? ''));
  $salary = (float)($_POST['salary'] ?? 0);
  $passwordright = (string)($_POST['passwordright'] ?? '');
  $passwordleft = (string)($_POST['passwordleft'] ?? '');

  if($passwordleft !== $passwordright)
  {
    echo "<script>alert('Passwords do not match!');</script>";
  }
  else if(strlen($passwordleft) < 8 || strlen($passwordleft) > 72)
  {
    echo "<script>alert('Password must be between 8 and 72 characters.');</script>";
  }
  else
  {
    $exists = (int) db_val("SELECT COUNT(*) FROM tbl_login WHERE Username = ?", [$email]);
    if($exists > 0)
    {
      echo "<script>alert('Username already exists!');</script>";
    }
    else
    {
      $conn->begin_transaction();
      try {
        db_exec(
          "INSERT INTO tbl_login (Username, Password, User_Type) VALUES (?, ?, 'ST')",
          [$email, password_hash($passwordleft, PASSWORD_DEFAULT)]
        );
        db_exec(
          "INSERT INTO tbl_staff
             (Staff_ID, Staff_Username, Staff_Fname, Staff_Lname, Staff_Phone, Staff_Gender,
              Staff_Hname, Staff_Street, Staff_Dist, State_Ut, Staff_Pin, Staff_Designation, Staff_Salary)
           VALUES (generate_staff_id(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
          [$email, $firstName, $lastName, $phoneNumber, $gender, $houseName, $street, $district, $state, $pincode, $designation, $salary]
        );
        $conn->commit();
        echo "<script>alert('Staff registered successfully!');</script>";
        if($usertype == 'AD' && $userId == 'ST00001') {
          header("location: admin.php");
        } else {
          header("location: staff.php");
        }
        exit;
      } catch (Throwable $e) {
        $conn->rollback();
        throw $e;
      }
    }
  }
}
?>
<html>
<head>
  
<link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">
  <title>Register Staff</title>
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
<!-- <script>
var jsMessage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php //echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script> -->
  <div class="outercontainer">
    <div class="registration-box">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Register Staff</div>
      <form action="" method="POST">
        <div class="registration-form">
          <div class="registration-form-left">
              <label for="firstName">Staff name</label>
              <input type="text" id="firstName" name="firstName" placeholder="First name" required>

              <label for="email">Email / Username</label>
              <input type="email" id="email" name="email" placeholder="Email" required>

              <label for="gender">Gender</label>
              <select id="gender" name="gender" required>
                <option value="" disabled selected hidden>Select gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
                <option value="O">Others</option>
              </select>
            
              <label for="address">Street</label>
              <input type="text" id="address" name="street" placeholder="Street" required>

              <label for="pincode">Pincode</label>
              <input type="text" id="pincode" name="pincode" placeholder="Enter 6 digit pincode" pattern="[0-9]{6}" required title="Enter a valid pincode">
              
              <label for="designation">Designation</label>
              <input type="text" id="designation" name="designation" placeholder="Staff designation" required>

              <label for="passwordright">Create password</label>
              <input type="password" id="passwordright" name="passwordright" placeholder="Enter password" minlength="8" maxlength="72" required title="Minimum 8 and maximum 72 characters required.">


          </div>

          <div class="registration-form-right">

            <label for="lastName"><!--Enter your name:--><br></label>
            <input type="text" id="lastName" name="lastName" placeholder="Last name">
  
            <label for="phoneNumber">Phone number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Enter a 10 digit phone number" pattern="[0-9]{10}" required title="Enter a valid phone number">

            <label for="houseName">House name</label>
            <input type="text" id="houseName" name="houseName" placeholder="House, apartment, suit, etc." required>
          
            <label for="district">District</label>
            <input type="text" id="district" name="district" placeholder="District" required>

            <label for="state">State/UT</label>
            <input type="text" id="state" name="state" placeholder="State / Union Territory" required>
            
            <label for="Salary">Salary</label>
            <input type="number" id="salary" name="salary" placeholder="Salary per month" required>

            <label for="passwordleft"><br></label>
            <input type="password" id="passwordleft" name="passwordleft" placeholder="Confirm password" minlength="8" maxlength="72" required title="Minimum 8 and maximum 72 characters required.">
           
          
            </div>
        </div>
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>
  </div>
</body>
