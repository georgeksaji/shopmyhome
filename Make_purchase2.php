<?php
include("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
$selectedAppliances = $_SESSION['Selected_Appliances'];
$count = count($selectedAppliances);
//echo $count;
foreach ($selectedAppliances as $selectedAppliance) {
  //echo $selectedAppliance;
}
//find profit percentage of each appliance

$appliance_profit_percentage = array();
for ($i = 0; $i < $count; $i++) {
  $sql = "SELECT Appliance_Profit_Percentage FROM tbl_appliance WHERE Appliance_ID='$selectedAppliances[$i]'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $appliance_profit_percentage[$i] = $row['Appliance_Profit_Percentage'];
  //echo $appliance_profit_percentage[$i];
}


if (isset($_POST['submit'])) {
  $vendor = $_POST['vendor'];
  $quantity = $_POST['quantity'];
  $price = $_POST['price'];
  // Cost_Per_Piece 	Selling_Price 	Total_Cost_Price 	
  $selling_price = array();
  $total_price_of_item = array();
  for ($i = 0; $i < $count; $i++) {
    $total_price_of_item[$i] = $quantity[$i] * $price[$i];
    $selling_price[$i] = $price[$i] + ($appliance_profit_percentage[$i] * ($price[$i] / 100));
  }




  $total_price = array_sum($total_price_of_item);
  // 	Purchase_Master_ID 	Vendor_ID 	Staff_ID 	Total_Amt 	Purchase_Date
  $sql = "INSERT INTO tbl_purchase_master (Purchase_Master_ID, Vendor_ID, Staff_ID, Total_Amt) VALUES (generate_purchase_master_id(), '$vendor', '$userId', '$total_price')";
  $result = mysqli_query($conn, $sql);
  //get purchase master id
  $sql = "SELECT Purchase_Master_ID FROM tbl_purchase_master WHERE Staff_ID='$userId' ORDER BY Purchase_Date DESC LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $purchase_master_id = $row['Purchase_Master_ID'];
  echo $purchase_master_id;
  // 	Purchase_Child_ID 	Purchase_Master_ID 	Appliance_ID 	Quantity 	Cost_Per_Piece 	Total_Cost_Price
  for ($i = 0; $i < $count; $i++) {
    $sql = "INSERT INTO tbl_purchase_child (Purchase_Child_ID, Purchase_Master_ID, Appliance_ID, Quantity, Balance_Stock, Cost_Per_Piece, Selling_Price, Total_Cost_Price) VALUES (generate_purchase_child_id(), '$purchase_master_id', '$selectedAppliances[$i]', '$quantity[$i]', '$quantity[$i]', '$price[$i]', '$selling_price[$i]', '$total_price_of_item[$i]')";
    $result = mysqli_query($conn, $sql);

  }
  if ($result) {
    unset($_SESSION['Selected_Appliances']);
    echo "<script>alert('Purchase made successfully!');</script>";
    header("location: admin.php");
  }
}
?>

<html>

<head>
  
<link rel="icon" type="image/x-icon" href="favicon.png">
  <title>Purchase</title>
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
      background-color: rgba(256, 256, 256, 0.9);
      padding: 18px;
      border-radius: 10px;
      width: min-content;
      max-height: 89%;
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
      height: 80px;
      width: 263px;

    }

    .registration-box-heading {
      font-family: Times New Roman, Times, serif;
      margin: auto;
      color: rgb(50, 131, 212);
      margin-bottom: 20px;
      font-weight: 800;
      font-size: 180%;
    }

    .registration-form {
      display: flex;
      flex-direction: column;
    }

    .registration-box select {
      width: 344px;
    }

    .registration-box input[type="number"],
    .registration-box select {
      padding: 7px;
      margin-left: 15px;
      margin-right: 15px;
      margin-bottom: 15px;
      border-color: rgb(82, 176, 210);
      background-color: rgb(82, 176, 210, 0.1);
      border-width: 1px;
      border-style: double;
      border-radius: 5px;
      color: rgb(0, 0, 0);
      text-align: initial;
    }


    ::placeholder {
      color: rgb(0, 0, 0, 0.8);
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type=number] {
      -moz-appearance: textfield;
    }

    label {
      color: rgba(0, 0, 0);
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
    .registration-form-right {
      flex-basis: 45%;
    }

    option {
      background-color: rgb(256, 256, 256, 0.7);
    }

    .registration-box button {
      background-color: rgb(255, 216, 21, 0.9);
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
      background-color: rgb(83, 178, 212) transition: 0.2s;
    }

    .view_table {
      background-color: rgb(256, 256, 256);
      width: 100%;
      table-layout: auto;

    }

    .view_table tr {
      height: 35px;
    }

    .view_table th {
      text-align: center;
      position: sticky;
      top: 0;
      background-color: rgb(42, 85, 229);
      color: rgb(256, 256, 256);
    }

    .view_table th,
    td {
      border-color: rgb(0, 0, 0, 0.5);
      border-style: solid;
      border-width: 1px;
      /*width:auto;
  white-space: nowrap;  Prevents text from wrapping in cells 
  overflow: hidden; /* Prevents content from overflowing cells 
  text-overflow: ellipsis; /* Shows ellipsis (...) for truncated text */
    }

    .view_table_wrapper {
      max-height: 48vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }
  </style>
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
      <div class="registration-box-heading">Purchase Details</div>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="registration-form">
          <?php
          echo "<label for='vendor'>Select Vendor</label>";
          echo "<select name='vendor' id='vendor' required>";
          $query = "SELECT * FROM tbl_vendor WHERE Vendor_Status=1";
          $vendor_list = $conn->query($query);
          echo '<option value="" disabled selected hidden>Select Vendor</option>';
          while ($row = mysqli_fetch_assoc($vendor_list)) {
            echo "<option value='" . $row['Vendor_ID'] . "'>" . $row['Vendor_Name'] . "</option>";
          }
          echo "</select>";
          ?>
          <div class="view_table_wrapper">
            <table class="table-bordered table-striped view_table">
              <tr>
                <th>Appliance ID</th>
                <th>Appliance Name</th>
                <th colspan="2">Images</th>
                <th>Quantity</th>
                <th>Price per Piece(₹)</th>
              </tr>
              <?php
              $query = "SELECT * FROM tbl_appliance WHERE Appliance_ID IN ('" . implode("','", $selectedAppliances) . "')";
              $appliance_list = $conn->query($query);
              while ($row = mysqli_fetch_assoc($appliance_list)) {
                $image1 = $row['Appliance_Image1'];
                $image2 = $row['Appliance_Image2'];
                echo "<tr>";
                echo "<td>" . $row['Appliance_ID'] . "</td>";
                $appliance_id = $row['Appliance_ID'];
                //select type and brand id and find its name from its table
                $query1 = "SELECT Type_ID, Brand_ID FROM tbl_appliance WHERE Appliance_ID='$appliance_id'";
                $result1 = mysqli_query($conn, $query1);
                $row1 = mysqli_fetch_assoc($result1);
                $appliance_type_id = $row1['Type_ID'];
                $appliance_brand_id = $row1['Brand_ID'];
                $query2 = "SELECT Type_Name FROM tbl_type WHERE Type_ID='$appliance_type_id'";
                $result2 = mysqli_query($conn, $query2);
                $row2 = mysqli_fetch_assoc($result2);
                $appliance_type_name = $row2['Type_Name'];
                //remove last word from type name string
                $appliance_type_name = substr($appliance_type_name, 0, strrpos($appliance_type_name, " "));
                $query3 = "SELECT Brand_Name FROM tbl_brand WHERE Brand_ID='$appliance_brand_id'";
                $result3 = mysqli_query($conn, $query3);
                $row3 = mysqli_fetch_assoc($result3);
                $appliance_brand_name = $row3['Brand_Name'];
                echo "<td>" . $appliance_brand_name . " " . $row['Appliance_Name'] . " " . $appliance_type_name . "</td>";
                echo "<td><img src='" . $image1 . "' alt='Image 1' height='50px' width='45px'></td>";
                echo "<td><img src='" . $image2 . "' alt='Image 1' height='50px' width='45px'></td>";
                //enter quantity
                echo "<td><input type='number' name='quantity[]' min='1' max='9999' required></td>";
                //enter price
                echo "<td><input type='number' name='price[]' min='1' max='9999999' required></td>";
                echo "</tr>";
              }
              ?>
            </table>
          </div>
        </div>
        <button type="submit" name="submit">Purchase</button>
      </form>
    </div>
  </div>
</body>

</html>

<?php
/*include("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
if(isset($_POST['submit']))
{
  $vendor = $_POST['vendor'];
  $appliance_name = $_POST['new_appliance_name'];
  $quantity = $_POST['new_quantity'];
  $price = $_POST['new_price'];
  $total_price_of_item = array();
  $count = count($appliance_name);
  for($i=0;$i<$count;$i++)
  {
    echo $total_price_of_item[$i]=$quantity[$i] * $price[$i];
  }
  $total_price = array_sum($total_price_of_item);
  // 	Purchase_Master_ID 	Vendor_ID 	Staff_ID 	Total_Amt 	Purchase_Date
  $sql = "INSERT INTO tbl_purchase_master (Purchase_Master_ID, Vendor_ID, Staff_ID, Total_Amt) VALUES (generate_purchase_master_id(), '$vendor', '$userId', '$total_price')"; 
  $result = mysqli_query($conn, $sql);
  //get purchase master id
  $sql = "SELECT Purchase_Master_ID FROM tbl_purchase_master WHERE Staff_ID='$userId' ORDER BY Purchase_Date DESC LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $purchase_master_id = $row['Purchase_Master_ID'];
  echo $purchase_master_id;
  // 	Purchase_Child_ID 	Purchase_Master_ID 	Appliance_ID 	Quantity 	Cost_Per_Piece 	Total_Cost_Price
  for($i=0;$i<$count;$i++)
  {
    $sql = "INSERT INTO tbl_purchase_child (Purchase_Child_ID, Purchase_Master_ID, Appliance_ID, Quantity, Cost_Per_Piece, Total_Cost_Price) VALUES (generate_purchase_child_id(), '$purchase_master_id', '$appliance_name[$i]', '$quantity[$i]', '$price[$i]', '$total_price_of_item[$i]')";
    $result = mysqli_query($conn, $sql);  
    //update Stock in tbl_appliance
    $sql = "UPDATE tbl_appliance SET Stock = Stock + '$quantity[$i]' WHERE Appliance_ID = '$appliance_name[$i]'";
    $result = mysqli_query($conn, $sql);
  } 
  if($result)
  {
    echo "<script>alert('Purchase made successfully!');</script>";
    header("location: admin.php");
  }
}
?>
*/