<?php
include ("connection.php");
session_start();
csrf_verify();
require_role(['AD','ST']);
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
if (empty($_SESSION['Update_ID'])) {
  header('Location: admin.php');
  exit;
}
$updateid = $_SESSION['Update_ID'];
//echo $updateid;

if(isset($_POST['submit']))
{
  $name = trim((string)($_POST['name'] ?? ''));
  $phoneNumber = trim((string)($_POST['phoneNumber'] ?? ''));
  $buildingName = trim((string)($_POST['buildingName'] ?? ''));
  $pincode = trim((string)($_POST['pincode'] ?? ''));
  $state = trim((string)($_POST['state'] ?? ''));
  $street = trim((string)($_POST['street'] ?? ''));
  $district = trim((string)($_POST['district'] ?? ''));
  $passwordright = (string)($_POST['passwordright'] ?? '');
  $passwordleft = (string)($_POST['passwordleft'] ?? '');

  $email = db_val("SELECT Cour_Username FROM tbl_courier WHERE Cour_ID = ?", [$updateid]);
  if ($email === null) {
    http_response_code(404);
    exit('Courier not found.');
  }

  $passwordOk = true;
  if ($passwordleft !== '' || $passwordright !== '') {
    if ($passwordleft !== $passwordright) {
      echo "<script>alert('Passwords do not match');</script>";
      $passwordOk = false;
    } elseif (strlen($passwordleft) < 8 || strlen($passwordleft) > 72) {
      echo "<script>alert('Password must be between 8 and 72 characters.');</script>";
      $passwordOk = false;
    }
  }

  if ($passwordOk) {
    $conn->begin_transaction();
    try {
      db_exec(
        "UPDATE tbl_courier
         SET Cour_Name = ?, Cour_Phone = ?, Cour_Building_name = ?, Cour_Pin = ?,
             Cour_State_ut = ?, Cour_Street = ?, Cour_Dist = ?
         WHERE Cour_ID = ?",
        [$name, $phoneNumber, $buildingName, $pincode, $state, $street, $district, $updateid]
      );

      if ($passwordleft !== '') {
        db_exec(
          "UPDATE tbl_login SET Password = ? WHERE Username = ?",
          [password_hash($passwordleft, PASSWORD_DEFAULT), $email]
        );
      }

      $conn->commit();
      echo "<script>alert('Courier updated successfully!');</script>";
      if($usertype == 'AD' && $userId == 'ST00001') {
        header("location: admin.php");
      } else if($usertype == 'ST') {
        header("location: staff.php");
      }
      exit;
    } catch (Throwable $e) {
      $conn->rollback();
      throw $e;
    }
  }
}
?>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">
  <title>Update Courier</title>
  <style>
    body {
      background-image: url("assets/images/backgrounds/background.png");
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
    ?>

              <input type="text" id="firstName" name="name" value="<?php echo e((string) $name); ?>" placeholder="Update Name" maxlength="15" required>

              <label for="phoneNumber">Phone number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" value="<?php echo e((string) $phoneNumber); ?>" placeholder="Enter a 10 digit phone number" pattern="[0-9]{10}" required title="Enter a valid phone number">
 
              <label for="pincode">Pincode</label>
              <input type="text" id="pincode" name="pincode" value="<?php echo e((string) $pincode); ?>" placeholder="Enter 6 digit pincode" pattern="[0-9]{6}" required title="Enter a valid pincode">

              
            <label for="district">District</label>
            <input type="text" id="district" name="district" value="<?php echo e((string) $district); ?>" placeholder="District" required>

            <label for="passwordright">New Password (optional)</label>
              <input type="password" id="passwordright" name="passwordright" placeholder="Leave blank to keep current password" minlength="8" maxlength="72" autocomplete="new-password" title="Minimum 8 and maximum 72 characters.">
          
          </div>

          <div class="registration-form-right">

            <label for="houseName">Building name</label>
            <input type="text" id="buildingName" name="buildingName" value="<?php echo e((string) $buildingName); ?>" placeholder="Building, apartment, suit, etc." required>

            <label for="address">Street</label>
             <input type="text" id="address" name="street" value="<?php echo e((string) $street); ?>" placeholder="Street" required>

            <label for="state">State</label>
            <input type="text" id="state" name="state" value="<?php echo e((string) $state); ?>" placeholder="State" required>

            <label for="passwordleft">Confirm New Password</label>
              <input type="password" id="passwordleft" name="passwordleft" placeholder="Confirm new password" minlength="8" maxlength="72" autocomplete="new-password" title="Minimum 8 and maximum 72 characters.">

              <button type="submit" name="submit">Submit</button>

            </div>
        </div>
       
      </form>
    </div>
  </div>
</body>
