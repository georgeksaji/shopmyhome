<?php
include("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
$cat_id = $_SESSION['Cat_ID'];

if(isset($_POST['submit']))
{
  $name = $_POST['name'];
  $type = $_POST['product_type'];
  $brand = $_POST['brand'];
  $profit = $_POST['decimal_value'];
  $description = $_POST['description'];
  $image1 = "product_images/" . $_FILES['image1']['name'];
  $image2 = "product_images/" . $_FILES['image2']['name'];

  // Define the destination directory
  $upload_directory = "C:/xampp/htdocs/ohas_codes/product_images/";

  // Move uploaded images to desired location
  move_uploaded_file($_FILES['image1']['tmp_name'], $upload_directory . $_FILES['image1']['name']);
  move_uploaded_file($_FILES['image2']['tmp_name'], $upload_directory . $_FILES['image2']['name']);

  $sql = "INSERT INTO tbl_appliance (Appliance_ID, Appliance_Name, Type_ID, Brand_ID, Appliance_Description, Appliance_Image1, Appliance_Image2, Appliance_Profit_Percentage) VALUES (generate_appliance_id(),'$name','$type','$brand','$description','$image1','$image2','$profit')";
  $result = mysqli_query($conn, $sql);

  if($result)
  {
    unset($_SESSION['Cat_ID']); 
    echo "<script>alert('Appliance added successfully!');</script>";
    header("location: admin.php");
  }
}
?>
<!-- Rest of your HTML code -->




<html>
<head>
  <title>Add product</title>
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
      <div class="registration-box-heading">Add new Appliance</div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="registration-form">
          <div class="registration-form-left">
              
          <label for="product_category">Product Category</label>
              <?php
              $sql_cat = "SELECT Cat_Name FROM tbl_category WHERE Cat_ID = '$cat_id'";
              $result_cat = mysqli_query($conn, $sql_cat);
              $category = mysqli_fetch_assoc($result_cat);
              echo "<input type='text' id='product_category' name='product_category' value='".$category['Cat_Name']."' readonly>";
              ?>

            <label for="product_type">Product Type</label>
              <select name="product_type" required>
              <option value="" disabled selected hidden>Select Type</option>
                <?php
                $sql_typ = "SELECT * FROM tbl_type WHERE Cat_ID = '$cat_id'";
                $result_typ = mysqli_query($conn, $sql_typ);
                $num_typ = mysqli_num_rows($result_typ);
                if($num_typ > 0) 
                {
                  while($row_typ = mysqli_fetch_assoc($result_typ))
                  {
                    echo "<option value=".$row_typ['Type_ID'].">".$row_typ['Type_Name']."</option>";
                  }
                  $type = $row_typ['Type_ID'];
                }
                ?>
                </select>

                <label for="brand">Brand</label>
                <select name="brand" required>
                <option value="" disabled selected hidden>Select Brand</option>
                <?php
                $sql_brd = "SELECT * FROM tbl_brand ";
                $result_brd = mysqli_query($conn, $sql_brd);
                $num_brd = mysqli_num_rows($result_brd);
                if($num_brd > 0) 
                {
                  while($row_brd = mysqli_fetch_assoc($result_brd))
                  {
                    echo "<option value=".$row_brd['Brand_ID'].">".$row_brd['Brand_Name']."</option>";
                  }
                }
                ?>
                </select>

          
          <label for="Name">Appliance name</label>
            <input type="text" id="firstName" name="name" placeholder="Name" maxlength="100" required>

         
            <label for="Image">Image 1</label>
            <input type="file" name="image1" accept=".jpg, .jpeg, .png, .webp" required>

          
          </div>

          <div class="registration-form-right">

          <label for="Image">Image 2</label>
            <input type="file" name="image2" accept=".jpg, .jpeg, .png, .webp" required>


            <label for="profit">Profit Percentage</label>
            <input type="number" step="0.01" min="0.00" max="999.99" name="decimal_value" placeholder="Enter Profit percentage as decimal(3,2)" required>

            <label for="description">Description</label>
            <input type="text" name="description" maxlength="500" placeholder="Description of Appliance"required>
            <button type="submit" name="submit">Submit</button>
            </div>
            
        </div>
        
      </form>
    </div>
  </div>
</body>
