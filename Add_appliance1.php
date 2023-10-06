<?php
include ("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
if(isset($_POST['submit']))
{
$cat_id = $_POST['category'];
$_SESSION['Cat_ID'] = $cat_id;
header("location: Add_appliance2.php");
}
?>
<html>
<head>
  <title>Add Appliance</title>
  <style>
    body {
      background-image: url("background.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: fixed;
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
    
    option
    {
      background-color:rgb(256,256,256,0.7);
    }



  </style>
  <link rel="icon" type="image/x-icon" href="favicon.png">
  </head>
<!-- <body>
<script>
var jsMessage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsMessage2 = <?php //echo json_encode($usertype); ?>;

// Display the PHP variable value as an alert in JavaScript
alert(jsMessage1);
alert(jsMessage2);
</script> -->
  <div class="outercontainer">
    <div class="registration-box">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Select Appliance Category</div>
      <form action="" method="POST">
        <div class="registration-form">
        <?php
        $query = "SELECT * FROM tbl_category";
        $category_list = $conn->query($query);
        echo '<select name="category" required>';
        echo '<option value="" disabled selected hidden>Select Category</option>';
         if ($category_list->num_rows > 0) {
             while ($row_cat = $category_list->fetch_assoc()) {
        
            echo '<option value="'.$row_cat['Cat_ID'].'">'.$row_cat['Cat_Name'].'</option>';     }}
            echo '</select>';
        ?>
        </div>
        <button type="submit" name="submit">Select</button> 
      </form>
    </div>
  </div>
</body>
