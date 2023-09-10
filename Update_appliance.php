<?php
include("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
$updateid=$_SESSION['Update_ID'];

if(isset($_POST['submit']))
{
  $name = $_POST['name'];
  $profit = $_POST['decimal_value'];
  $description = $_POST['description'];
  if(!empty($_FILES['image1']['name']))
  {
    $image1 = "product_images/" . $_FILES['image1']['name'];
  }
  else
  {
    $image1 = "";
  }
  if(!empty($_FILES['image2']['name']))
  {
    $image2 = "product_images/" . $_FILES['image2']['name'];
  }
  else
  {
    $image2 = "";
  }
  // Define the destination directory
  $upload_directory = "C:/xampp/htdocs/ohas_codes/product_images/";

  // Move uploaded images to desired location
  if(!empty($_FILES['image1']['name']))
  {
    move_uploaded_file($_FILES['image1']['tmp_name'], $upload_directory . $_FILES['image1']['name']);
  }
  if(!empty($_FILES['image2']['name']))
  {
  move_uploaded_file($_FILES['image2']['tmp_name'], $upload_directory . $_FILES['image2']['name']);
  }
  
  $sql = "UPDATE tbl_appliance SET Appliance_Name='$name', Appliance_Description='$description', Appliance_Profit_Percentage='$profit' WHERE Appliance_ID='$updateid'";
  $result = mysqli_query($conn, $sql);
  if(!empty($_FILES['image1']['name']))
  {
    $sql = "UPDATE tbl_appliance SET Appliance_Image1='$image1' WHERE Appliance_ID='$updateid'";
    $result = mysqli_query($conn, $sql);
  }
  if(!empty($_FILES['image2']['name']))
  {
    $sql = "UPDATE tbl_appliance SET Appliance_Image2='$image2' WHERE Appliance_ID='$updateid'";
    $result = mysqli_query($conn, $sql);
  }


  if($result)
  {
    unset($_SESSION['Update_ID']);
    echo "<script>alert('Appliance updated successfully!');</script>";
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
  <title>Update product</title>
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

    .registration-box input[type="text"],
    .registration-box input[type="email"],
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
      background-color:rgb(256,256,256,0.7);
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
      <div class="registration-box-heading">Update Appliance</div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="registration-form">
          <div class="registration-form-left">
          <?php
          //Appliance_ID 	Appliance_Name 	Type_ID 	Brand_ID 	Appliance_Description 	Appliance_Image1 	Appliance_Image2 	Appliance_Profit_Percentage
          $sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$updateid'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $type_id = $row['Type_ID'];
          $brand_id = $row['Brand_ID'];
          $appliance_id = $row['Appliance_ID'];
          $appliance_name = $row['Appliance_Name'];
          $appliance_description = $row['Appliance_Description'];
          $image1 = $row['Appliance_Image1'];
          $image2 = $row['Appliance_Image2'];
          $appliance_profit_percentage = $row['Appliance_Profit_Percentage'];
          


          $sql1 = "SELECT * FROM tbl_type WHERE Type_ID = '$type_id'";
          $result1 = mysqli_query($conn, $sql1);
          $row1 = mysqli_fetch_assoc($result1);
          $cat_id = $row1['Cat_ID'];
          ?>
              
          <label for="product_category">Product Category</label>
          <input type='text' id='product_category' name='product_category' value='<?php echo $cat_id;?>' disabled>

            <label for="product_type">Product Type</label>
            <input type='text' id='product_type' name='product_type' value='<?php echo $type_id;?>' disabled>


          <label for="brand">Brand</label>
          <input type='text' id='product_brand' name='product_brand' value='<?php echo $brand_id;?>' disabled>

          
          <label for="Name">Appliance name</label>
          <input type="text" id="Name" name="name" placeholder="Name" maxlength="35" value='<?php echo $appliance_name;?>' required>

         
            <label for="Image">Current Images</label>
            <?php if (!empty($image1) && ($image2)): ?>
            <img src="<?php echo $image1; ?>" alt="Image 1" height="50px" width="45px" style="margin-right:5%">
            <img src="<?php echo $image2; ?>" alt="Image 1" height="50px" width="45px">
            <?php endif; ?>
            <br>
            <label for="Image">Update Image 1</label>
            <input type="file" name="image1" accept=".jpg, .jpeg, .png">
            

          
          </div>

          <div class="registration-form-right">

          <label for="Image">Update Image 2</label>
            <input type="file" name="image2" accept=".jpg, .jpeg, .png">


            <label for="profit">Profit Percentage</label>
            <input type="number" step="0.01" min="0.00" max="999.99" name="decimal_value" placeholder="Enter Profit percentage as decimal(3,2)" value='<?php echo $appliance_profit_percentage;?>' required>

            <label for="description">Description</label>
            <input type="text" name="description" maxlength="50" placeholder="Description of Appliance" value='<?php echo $appliance_description;?>' required>
            <button type="submit" name="submit">Submit</button>
            </div>
            
        </div>
        
      </form>
    </div>
  </div>
</body>
