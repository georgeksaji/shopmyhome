<?php
include ("connection.php");
session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

if (isset($_POST['submit'])) {
    if (isset($_POST['appliance']) && is_array($_POST['appliance'])) {
        foreach ($_POST['appliance'] as $selectedAppliance) {
            $selectedAppliances[] = $selectedAppliance;
        }
        
        // Display the selected appliance IDs using echo
        //echo "Selected Appliance IDs: ";
        //echo implode(', ', $selectedAppliances);
        $_SESSION['Selected_Appliances']=$selectedAppliances;
        header("location: Make_purchase2.php");
        
    } else {
        echo "No appliances were selected.";
    }
}

?>
<html>
<head>
  
<link rel="icon" type="image/x-icon" href="favicon.png">
  <title>Select Items</title>
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

    .registration-box input[type=checkbox] {

    line-height: normal;
    width: 98px;
    height: 25px;
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
    

    
.view_table
{
  background-color: rgb(256,256,256);
  width:100%;
  table-layout:auto;
  
}
.view_table tr{
  height: 35px;
}
.view_table th{
text-align:center;
position: sticky;
top: 0;
background-color:rgb(42,85,229);
color:rgb(256,256,256);
}
.view_table th,td
{
  border-color: rgb(0, 0, 0, 0.5);
  border-style: solid;
  border-width: 1px;
  /*width:auto;
  white-space: nowrap;  Prevents text from wrapping in cells 
  overflow: hidden; /* Prevents content from overflowing cells 
  text-overflow: ellipsis; /* Shows ellipsis (...) for truncated text */
}

.view_table_wrapper {
  max-height: 60vh; /* Set the desired maximum height */
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
    <div class="registration-box" style="width:max-content;">
      <div class="registration-box-logo"></div>
      <div class="registration-box-heading">Select Purchase Items</div>
      <form action="" method="POST">
        <div class="registration-form">
        <div class="view_table_wrapper">
              <table class="table-bordered table-striped view_table">
              <tr> 
                <th>Appliance ID</th> 
                <th>Appliance Name</th> 
                <th colspan="2">Images</th>
                <th>Purchase</th>
                </tr>
              <?php
                $query = "SELECT * FROM tbl_appliance";
                $appliance_list = $conn->query($query);
                while($row = mysqli_fetch_assoc($appliance_list))
                {
                    $image1 = $row['Appliance_Image1'];
                    $image2 = $row['Appliance_Image2'];
                  echo "<tr>";
                  echo "<td>".$row['Appliance_ID']."</td>";
                  echo "<td>".$row['Appliance_Name']."</td>";
                    echo "<td><img src='" . $image1 . "' alt='Image 1' height='50px' width='45px'></td>";
                    echo "<td><img src='" . $image2 . "' alt='Image 1' height='50px' width='45px'></td>";
                  //checkbox
                echo "<td><input type='checkbox' name='appliance[]' value='".$row['Appliance_ID']."'></td>";
                  echo "</tr>";
                }
                ?>
              </table>
              </div>
        </div>
        <button type="submit" name="submit">Proceed</button> 
      </form>
    </div>
  </div>
</body>
</html>