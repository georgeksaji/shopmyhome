<?php
include ("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
$updateid = $_SESSION['Update_ID'];
echo $updateid;

if(isset($_POST['submit']))
{
  $name = $_POST['name'];
  if(!empty($_FILES['image1']['name']))
  {
    $image1 = "brand_images/" . $_FILES['image1']['name'];
  }
  else
  {
    $image1 = "";
  }
  // Define the destination directory
  $upload_directory = "C:/xampp/htdocs/ohas_codes/brand_images/";

  // Move uploaded images to desired location
  if(!empty($_FILES['image1']['name']))
  {
    move_uploaded_file($_FILES['image1']['tmp_name'], $upload_directory . $_FILES['image1']['name']);
  }

  $sql = "UPDATE tbl_brand SET Brand_Name='$name' WHERE Brand_ID='$updateid'";
  $result = mysqli_query($conn, $sql);
  if(!empty($_FILES['image1']['name']))
  {
    $sql = "UPDATE tbl_brand SET Brand_Logo='$image1' WHERE Brand_ID='$updateid'";
    $result = mysqli_query($conn, $sql);
  }

  if($result)
  {
    unset($_SESSION['Update_ID']);
    echo "<script>alert('Brand updated successfully!');</script>";
    if($usertype == "AD" && $userId == "ST00001")
    {
      header("location: admin.php");
    }
    else if($usertype == "ST")
    {
      header("location: staff.php");
    }
  }

}

?>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="favicon.png">
  <title>Update Category Name</title>
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
      display: column;
    }

    
    .registration-box input[type="text"],
    .registration-box input[type="email"],
    .registration-box input[type="tel"],
    .registration-box input[type="file"],
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
    .registration-box input[type="file"]:focus,
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
<body><!--
<script>
var jsMessage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php //echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script>-->
  <div class="outercontainer">
    <div class="registration-box">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Update Category Name</div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="registration-form">
        <?php // update category name
        $sql = "SELECT * FROM tbl_brand WHERE Brand_ID = '$updateid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $name = $row['Brand_Name'];
        $image = $row['Brand_Logo'];

        // label for name
        echo "<label for='name'>Brand Name</label>";
        echo "<input type='text' name='name' value='$name' placeholder='Update Brand Name' required>";
        echo "<label for='name'>Brand Logo</label>";
        echo "<img src='$image' alt='Image 1' height='60px' width='60px' style='margin-right:5%'>";
        echo '<label for="Image">Update Logo</label>';
        echo  '<input type="file" name="image1" accept=".jpg, .jpeg, .png, .webp">';
          ?>

        </div>
        <button type="submit" name="submit">Submit</button>
      </form>
    </div>
  </div>
</body>
