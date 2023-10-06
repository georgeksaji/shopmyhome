<?php
include("connection.php");
session_start();
$_SESSION['search'] = null;
$_SESSION['brand_id'] = null;
$_SESSION['category_id'] = null;
$_SESSION['type_id'] = null;
$product_detail_id = $_SESSION['product_detail_id'];

if ($_SESSION['User_ID'] && $_SESSION['User_Type'] != null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
} else {
  $userId = null;
  $usertype = null;
}

//search appliance
//search appliance
if (isset($_POST['submit'])) {
  $search = $_POST['search'];
  $_SESSION['search'] = $search;
  header("Location: list_products.php");
}
//login button
if (isset($_POST['login'])) {
  header("Location: Login.php");
}
//signup button
if (isset($_POST['signup'])) {
  header("Location: Customer_Sign_Up.php");
}
//admin button
if (isset($_POST['admin'])) {
  header("Location: admin.php");
}
//logout button
if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: index.php");
}
//category button
if (isset($_POST['category'])) {
  $category_id = $_POST['category'];
  $_SESSION['category_id'] = $category_id;
  header("Location: list_types.php");
}
//cart button
if (isset($_POST['cart'])) {
  header("Location: cart.php");
}
//find profit percentage
$sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$product_detail_id'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
  $appliance_profit_percentage = $row['Appliance_Profit_Percentage'];
}
//cart button
if (isset($_POST['add_to_cart'])) {
  if ($userId == null && $usertype == null) {
    // Use JavaScript to show a confirmation dialog
    echo '<script>';
    echo 'if(confirm("You are not logged in. Do you want to login?"))';
    echo '{';
    echo '  window.location.href = "Login.php";'; // Redirect if confirmed
    echo '}';
    echo 'else';
    echo '{';
    echo '  alert("You chose not to login.");'; // Provide an alert if canceled
    echo '}';
    echo '</script>';
  } else if ($userId != null && $usertype == 'CU') {
    $quantity = $_POST['quantity'];
    //select stock from tbl_purchase master where appliance_id = $product_detail_id
    $sql = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$product_detail_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      //Balance_Stock from tbl_purchase_child where appliance_id = $product_detail_id
      $row = mysqli_fetch_assoc($result);
      $appliance_stock = $row['Balance_Stock'];
      //if quantity > stock then alert stock is less and then set new quantity = stock
      if ($quantity > $appliance_stock) {
        $quantity_update_status = 1;
        $_SESSION['quantity_update_status'] = $quantity_update_status;
        $quantity = $appliance_stock;
      }
      else if($quantity <= $appliance_stock)
      {
        $quantity_update_status = 0;
        $_SESSION['quantity_update_status'] = $quantity_update_status;
      }
      $selling_price = $row['Selling_Price'];
      $total_price = $selling_price * $quantity;


      $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) == 0) {
        //insert into cart_master
        $sql = "INSERT INTO tbl_cart_master(CM_ID,Customer_ID,Cart_Status) VALUES (generate_cart_master_id(),'$userId','ASSIGNED')";
        $result = mysqli_query($conn, $sql);
        //select cart_master_id from tbl_cart_master where customer_id = $userId
        $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $cart_master_id = $row['CM_ID'];
        }
        //insert into cart_child
        $sql = "INSERT INTO tbl_cart_child (CC_ID,CM_ID,Appliance_ID,Quantity,Price) VALUES (generate_cart_child_id(),'$cart_master_id','$product_detail_id','$quantity','$total_price')";
        $result = mysqli_query($conn, $sql);
        //update total amount in tbl_cart_master
        //Total_Amount=Total_Amount+total_price
        $sql = "UPDATE tbl_cart_master SET Total_Amount = Total_Amount + '$total_price' WHERE CM_ID = '$cart_master_id'";
        $result = mysqli_query($conn, $sql);

        //**************** decrease quantity*****************
        header("Location: cart.php");
      } else if (mysqli_num_rows($result) == 1) {
        //select cart_master_id from tbl_cart_master where customer_id = $userId

        $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $cart_master_id = $row['CM_ID'];
        }
        //check if appliance_id already exists in cart_child with same cart_master_id
        $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cart_master_id' AND Appliance_ID = '$product_detail_id'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
          //update quantity and price in cart_child
          $sql = "UPDATE tbl_cart_child SET Quantity = Quantity + '$quantity', Price = Price + '$total_price' WHERE CM_ID = '$cart_master_id' AND Appliance_ID = '$product_detail_id'";
          $result = mysqli_query($conn, $sql);
          //update total amount in tbl_cart_master
          //Total_Amount=Total_Amount+total_price
          $sql = "UPDATE tbl_cart_master SET Total_Amount = Total_Amount + '$total_price' WHERE CM_ID = '$cart_master_id'";
          $result = mysqli_query($conn, $sql);
        } else if (mysqli_num_rows($result) == 0) {
          //insert into cart_child
          $sql = "INSERT INTO tbl_cart_child (CC_ID,CM_ID,Appliance_ID,Quantity,Price) VALUES (generate_cart_child_id(),'$cart_master_id','$product_detail_id','$quantity','$total_price')";
          $result = mysqli_query($conn, $sql);
          $sql = "UPDATE tbl_cart_master SET Total_Amount = Total_Amount + '$total_price' WHERE CM_ID = '$cart_master_id'";
          $result = mysqli_query($conn, $sql);
        }
        header("Location: cart.php");
      }
    } else if (mysqli_num_rows($result) == 0) {
      // JavaScript alert
      echo '<script>alert("This item is out of stock. Please try again later.");</script>';

      // JavaScript redirection after the alert
      echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
      exit();
    }
  } else if ($userId != null && $usertype != 'CU') {
    //alert you are not a customer
    echo '<script>alert("You are not a customer. Please login as a customer to add items to cart.")</script>';
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>shopmyhome</title>
  <link rel="icon" type="image/x-icon" href="favicon.png">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!-- 
  <script>
var jsM    essage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
var jsM    essage2 = <?php //echo json_encode($usertype); ?>;

// Disp    lay the PHP variable value as an     alert in JavaScript
alert(j    sMessage1);
alert(j    sMess    age2);
  </script> -->


  <!--script-->

  <style>
    body {
      padding: 0%;
      margin: 0%;
      background-color: rgba(255, 255, 255, 0.9);
      overflow-x: hidden;
      overflow-y: hidden;

    }

    .home-outer {
      width: 100%;
      height: 100vh;
    }

    .top-navigation {
      width: 100%;
      height: 10vh;
      background-color: rgba(101, 194, 240, 0.4);
      background-origin: content-box;
      display: -webkit-box;
      align-items: center;
    }

    .navigation-logo {
      background-image: url(Picture3.png);
      background-size: cover;
      margin-inline-start: 2vh;
      height: 100%;
      width: 33vh;
    }

    .navbar {
      width: 49%;
    }

    .container-fluid,
    .d-flex {
      width: 100%;

    }

    .form-control {
      width: 50%;
      padding-top: 1%;
      padding-bottom: 1%;
      background-color: rgb(6, 28, 100, 0.1);
      font-weight: 350;
      font-family: calibri;
      transition: 0.4s;
    }

    .form-control::-webkit-search-cancel-button {
      display: none;
    }


    .form-control:hover,
    .form-control:focus {
      background-color: rgba(255, 255, 255, 0.456);
      outline: none;
      box-shadow: none;
    }

    .btn {
      border-color: rgb(6, 28, 100);
      color: rgb(6, 28, 100);
      transition: 0.4s;
    }

    .btn:hover {
      background-color: rgb(6, 28, 100);
    }

    .navigation-item {

      height: 100%;
      width: 35%;
      padding-right: 2%;
    }

    .action-table {
      width: 100%;
      height: 100%;
      border-collapse: separate;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }

    .action table td {
      display: inline-block;
    }

    .login-box,
    .signup-box,
    .admin-box,
    .logout-box,
    .profile-box {
      border-style: none;
      background-color: rgba(255, 255, 255, 0.1);
      transition: 0.3s;
      font-size: small;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-weight: 550;
      color: rgb(0, 0, 0);
      display: flex;
      justify-content: center;
      align-items: center;
      min-width: 91px;
      height: 40px;
    }

    .login-box:hover,
    .signup-box:hover,
    .admin-box:hover {
      background-color: rgb(0, 0, 0);
      color: white;
    }

    .logout-box:hover {
      background-color: rgb(239, 51, 36);
      color: white;
    }

    .profile-box:hover {
      transition: 0.5s;
      transform: scale(1.2);
    }

    .action table .btn-primary,
    .action table .btn {
      border-style: none;
      background-color: rgb(6, 28, 100);
    }

    .action table .btn:hover {
      background-color: rgb(256, 256, 256);
    }


    .action-table td {
      width: auto;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    a {
      text-decoration: none;
    }

    .category-navigation {
      display: flex;
      background-color: rgb(120, 181, 225);
      justify-content: center;
      align-items: center;
    }

    .category-name {
      font-size: small;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: rgb(120, 181, 225);
      font-weight: 550;
      border-style: none;
      width: fit-content;
      padding-left: 10px;
      padding-right: 10px;
      height: 45px;
      display: flex;
      color: rgb(256, 256, 256);
      justify-content: center;
      align-items: center;
      transition: 0.5s;
    }

    .category-name:hover {
      background-color: rgba(255, 255, 255, 0.166);
      color: rgb(0, 0, 0);
      border-bottom-width: 3px;
      border-left-width: 0px;
      border-right-width: 0px;
      border-top-width: 0px;
      border-style: solid;
      border-color: rgb(255, 255, 255);
      padding-left: 13px;
      padding-right: 13px;
    }

    .carousel-item {
      transition: 0.6s;
    }

    .carousel-control-next-icon:hover,
    .carousel-control-prev-icon:hover {
      transition: 0.5s;
      background-color: rgba(0, 0, 0, 0.2);
      border-radius: 100%;
    }

    .product_outer {
      width: 100%;
      height: 85%;
      padding: 3%;
      display: flex;
      align-items: center;
    }

    .image_outer {
      width: 30%;
      height: 100%;
      float: left;
      border-style: solid 1px rgb(0, 0, 0);
      background-color: rgb(256, 256, 256);
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center center;
    }

    .product_details {
      width: 70%;
      height: 100%;
      padding-left: 5%;
      padding-right: 5%;
      padding-top: 2%;
      /*--background-color: rgb(2,256,256);*/
      border-style: solid 1px rgb(0, 0, 0);
    }

    .product_details h3,
    h5,
    h6 {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-weight: 550;
      color: rgb(0, 0, 0);
      margin: 0%;
      padding: 0%;
    }

    .brand_image {
      width: 100%;
      height: 20%;
      background-size: contain;
      background-repeat: no-repeat;
      float: left;
      margin-right: 2%;
    }

    .cart_form {
      width: 30%;
      height: 6vh;
      margin-top: 6%;
      margin-bottom: 2%;
      display: inline-flex;
      justify-content: space-between;
    }

    .add_to_cart_button {
      width: 68%;
      height: 100%;
      background-color: gold;
      border-style: none;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: 0.5s;
      font-size: 95%;
    }

    .add_to_cart_button:hover {
      transform: scale(1.01);
      background-color: #ffc64de9;
    }

    .details_bottom {
      width: 100%;
      height: 70%;
      margin-top: 6%;
      padding-bottom: 2%;
      align-items: flex-start;
    }
  </style>

</head>

<body>
  <div class="home-outer">

    <div class="top-navigation">
      <a href="index.php">
        <div class="navigation-logo"></div>
      </a>
      <!-- <div class="search-bar"><input type="search" placeholder="Search for home appliances"></div> -->
      <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
          <form class="d-flex" role="search" method="POST">
            <input class="form-control me-2" type="search" placeholder="Search  for appliances" aria-label="Search"
              name="search">
            <button class="btn btn-outline-success" type="submit" name="submit">Search</button>
          </form>
        </div>
      </nav>
      <div class="navigation-item">
        <table class="action-table">
          <?php
          // if userid and usertype null, display login and signup
          if ($userId == null && $usertype == null) {
            echo '<form action="" method="POST">';
            echo '<tr><td><button class="login-box" name="login">Login</button></td>';
            echo '<td><button class="signup-box" name="signup">Sign Up</button></td>';
            echo '</form>';
          }
          //if userid =ST00001 AND usertype=AD then a=admin.php
          if ($userId == 'ST00001' && $usertype == 'AD') {
            //admin panel page , logout
            echo '<form action="" method="POST">';
            echo '<tr><td><button class="admin-box" name="admin">Admin Dashboard</button></td>';
            echo '<td><button class="logout-box" name="logout">Logout</button></td>';
            echo '</form>';
          }
          //if userid!=ST00001 AND usertype=ST
          if ($userId != 'ST00001' && $usertype == 'ST') {
            //profile page,Staff Dashboard, logout
            echo '<form action="" method="POST">';
            //profile page
            echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
            echo '<td><button class="admin-box" name="admin">Staff Dashboard</button></td>';
            echo '<td><button class="logout-box" name="logout">Logout</button></td>';
            echo '</tr>';
            echo '</form>'; //    
          }
          //if usertype=CU
          if ($usertype == 'CU') {
            //profile page, logout
            echo '<form action="" method="POST">';
            echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
            //cart button
            $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              $row = mysqli_fetch_assoc($result);
              $cm_id = $row['CM_ID'];
              $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
              $result = mysqli_query($conn, $sql);
              $count = mysqli_num_rows($result);
              echo "<td><button type='submit' class='profile-box' name='cart'><img src='cart.png' height='30px' width='30px'><span style='font-size: 0.95em; color: rgb(239,51,36)' class='badge text-bg-secondary'>$count</span></button></td>";
            } else if (mysqli_num_rows($result) == 0) {
              echo "<td><button type='submit' class='profile-box' name='cart'><img src='cart.png' height='30px' width='30px'><span style='font-size: 0.95em; color: rgb(239,51,36)' class='badge text-bg-secondary'>0</span></button></td>";

            }
            echo '<td><button class="logout-box" name="logout">Logout</button></td>';
            echo '</tr>';
            echo '</form>';
          }
          ?>
        </table>
      </div>
    </div>
    <div class="category-navigation">
      <!--<button class="category-name">TV & Audio</button>-->
      <form action="" method="POST">
        <?php
        //category names
        $sql = "SELECT * FROM tbl_category";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $categoryname = $row['Cat_Name'];
          $categoryid = $row['Cat_ID'];
          echo "<button class='category-name' name='category' value='$categoryid' style='display: inline-block;'>$categoryname</button>";
        }
        ?>
      </form>
    </div>
    <?php
    //echo $_SESSION['product_detail_id'];?>

    <div class="product_outer">

      <?php
      $sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$product_detail_id'";
      $result = mysqli_query($conn, $sql);
      //Appliance_ID 	Appliance_Name 	Stock 	Type_ID 	Brand_ID 	Appliance_Description 	Appliance_Image1 	Appliance_Profit_Percentage
      while ($row = mysqli_fetch_assoc($result)) {
        $appliance_name = $row['Appliance_Name'];
        //$appliance_stock = $row['Stock'];
        $appliance_type_id = $row['Type_ID'];
        $appliance_brand_id = $row['Brand_ID'];
        $appliance_description = $row['Appliance_Description'];
        $appliance_image1 = $row['Appliance_Image1'];
        $appliance_profit_percentage = $row['Appliance_Profit_Percentage'];
      }
      echo "<div class='image_outer' style='background-image: url($appliance_image1);'>";
      echo '</div>';
      ?>
      <div class="product_details">
        <?php
        //select stock from tbl_purchase master where appliance_id = $product_detail_id
//$sql = "SELECT Quantity FROM tbl_purchase_child WHERE Appliance_ID = '$product_detail_id'";
//$result = mysqli_query($conn,$sql);
//while($row = mysqli_fetch_assoc($result))
//{/
        // $appliance_stock = $row['Quantity'];
//}
        



        //select Brand_Name from tbl_brand where Brand_ID = $appliance_brand_id
        $sql = "SELECT Brand_Name FROM tbl_brand WHERE Brand_ID = '$appliance_brand_id'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $appliance_brand_name = $row['Brand_Name'];
        }
        //select Type_Name from tbl_type where Type_ID = $appliance_type_id
        $sql = "SELECT Type_Name FROM tbl_type WHERE Type_ID = '$appliance_type_id'";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
          $appliance_type_name = $row['Type_Name'];

          // Check if the string is not empty and has at least one character
          if (!empty($appliance_type_name)) {
            // Remove the last character
            $appliance_type_name = substr($appliance_type_name, 0, -1);
          }

          // Now $appliance_type_name contains the modified string without the last character
        }

        echo "<h3>$appliance_brand_name $appliance_name $appliance_type_name</h1>";
        //select Brand_Logo from tbl_brand where Brand_ID = $appliance_brand_id
        $sql = "SELECT Brand_Logo FROM tbl_brand WHERE Brand_ID = '$appliance_brand_id'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $appliance_brand_logo = $row['Brand_Logo'];
        }
        echo "<div class='brand_image' style='background-image: url($appliance_brand_logo);'></div>";
        //calculate price
//echo "<h5>Profit Percentage: $appliance_profit_percentage</h5>";
        
        $sql_quantity = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$product_detail_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
        $result_quantity = mysqli_query($conn, $sql_quantity);
        //if result is not null then display price
        if (mysqli_num_rows($result_quantity) > 0) {
          //Balance_Stock from tbl_purchase_child where appliance_id = $product_detail_id
          $row_quantity = mysqli_fetch_assoc($result_quantity);
          $appliance_stock = $row_quantity['Balance_Stock'];

          $sql_selling_price = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$product_detail_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
          $result_sp = mysqli_query($conn, $sql_selling_price);
          if (mysqli_num_rows($result_sp) > 0) {
            $row_sp = mysqli_fetch_assoc($result_sp);
            $selling_price = $row_sp['Selling_Price'];
            if ($selling_price == null) {
              echo "<div class='product_price' style='color:red;'>OUT OF STOCK</div>";

            } else if ($selling_price !== null) {
              echo "<div class='product_price'><h5 style='color:#109b5a;'>₹$selling_price/-</h5></div>";
              echo "<form action='' method='POST' class='cart_form'>";
              echo "<input type='number' name='quantity' value='1' min='1' max='$appliance_stock' width: 50%; height: 100%;'>";
              echo "<button type='submit' name='add_to_cart' class='add_to_cart_button'>ADD TO CART</button>";
              echo "</form>";
              echo "<h6>IN STOCK: $appliance_stock</h6>";
              echo "<div class='details_bottom'><h6>Description: $appliance_description</h6></div>";
            }
          } else if (mysqli_num_rows($result_sp) == 0) {
            echo "<div class='product_price' style='color:red;'>OUT OF STOCK</div>";
            echo "<div class='details_bottom'><h6>Description: $appliance_description</h6></div>";
          }



          /*$sql_cost_price="SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$product_detail_id'";
          $result_cost_price = mysqli_query($conn,$sql_cost_price);
          $row_cost_price = mysqli_fetch_assoc($result_cost_price);
          if($row_cost_price == null)
          {
            $cost_price = null;
            $price = null;
          }
          else
          {
            $cost_price = $row_cost_price['Cost_Per_Piece'];
            $cost_price = $row_cost_price['Cost_Per_Piece'];
            $price = $cost_price + ($cost_price * $appliance_profit_percentage)/100;
          
          }
          
          if($price==null)
          {
            echo "<div class='product_price' style='color:red;'>OUT OF STOCK</div>";
            echo "<div class='details_bottom'><h6>Description: $appliance_description</h6></div>";
          }
          else if($price!==null)
          {
            
            echo "<div class='product_price'><h5 style='color:#109b5a;'>₹$price/-</h5></div>";
            echo "<form action='' method='POST' class='cart_form'>";
            echo "<input type='number' name='quantity' value='1' min='1' max='$appliance_stock' width: 50%; height: 100%;'>";
            echo "<button type='submit' name='add_to_cart' class='add_to_cart_button'>ADD TO CART</button>";
            echo "</form>";
            echo "<h6>IN STOCK: $appliance_stock</h6>";
            echo "<div class='details_bottom'><h6>Description: $appliance_description</h6></div>";
          }*/
        } else if (mysqli_num_rows($result_quantity) == 0) {
          echo "<div class='product_price' style='color:red;'>OUT OF STOCK</div>";
          echo "<div class='details_bottom'><h6>Description: $appliance_description</h6></div>";
        }

        ?>
      </div>






    </div>



  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

</body>

</html>