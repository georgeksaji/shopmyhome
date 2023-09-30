<?php
include("connection.php");
session_start();
$_SESSION['search'] = null;
$_SESSION['brand_id'] = null;
$_SESSION['category_id'] = null;
$_SESSION['type_id'] = null;

$quantity_update_status = null;
if (isset($_SESSION['quantity_update_status']) && $_SESSION['quantity_update_status'] !== null) {
  $quantity_update_status = $_SESSION['quantity_update_status'];
  if ($quantity_update_status == 1) {
    $_SESSION['quantity_update_status'] = null;
  }
}

if (isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
} else {
  $userId = null;
  $usertype = null;
}




$cm_id = null;
$sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $cm_id = $row['CM_ID'];
}

if ($cm_id != null) {
  $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
  $result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);
  if ($count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $item_id = $row['Appliance_ID'];
      $quantity = $row['Quantity'];
      $previous_quantity = $quantity;
      $price = $row['Price'];
      $sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$item_id'";
      $result1 = mysqli_query($conn, $sql);
      $row1 = mysqli_fetch_assoc($result1);

      //$sql_quantity
      $select_tbl_pc = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
      $result_pc = mysqli_query($conn, $select_tbl_pc);
      if (mysqli_num_rows($result_pc) > 0) {
        $row_quantity = mysqli_fetch_assoc($result_pc);
        $available_quantity = $row_quantity['Balance_Stock'];
        if ($quantity > $available_quantity) {
          $deficit = $quantity - $available_quantity;
          $quantity = $available_quantity;
          $sql = "UPDATE tbl_cart_child SET Quantity = '$quantity' WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
          mysqli_query($conn, $sql);
          //select Total_Amount from tbl_cart_master
          $sql = "SELECT Total_Amount FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $total_amount = $row['Total_Amount'];
          //decrease price in tbl_cart_master
          $selling_price = $row_quantity['Selling_Price'];
          $decrease_price = $selling_price * $deficit;
          $total_amount = $total_amount - $decrease_price;
          $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
          mysqli_query($conn, $sql);
          //update price in tbl_cart_child
          $price = $selling_price * $quantity;
          $sql = "UPDATE tbl_cart_child SET Price = '$price' WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
          mysqli_query($conn, $sql);

          $quantity_update_status = 1;
        }
      }
    }
  }
}

if (isset($_POST['checkout'])) {
  try {
    if (!isset($_POST['card_id'])) {
      throw new Exception('Please select a card to checkout');
    }

    if (!isset($_POST['Master_ID'])) {
      throw new Exception('Cart is Empty');
    }

    $card_id = $_POST['card_id'];

    $cm_id = $_POST['Master_ID'];

    // Check if any row with cm_id exists in tbl_cart_child
    $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
      throw new Exception('Cart is Empty');
    }

    $canProceedToPayment = true;

    if ($cm_id != null) {
      while ($row = mysqli_fetch_assoc($result)) {
        $item_id = $row['Appliance_ID'];
        $quantity = $row['Quantity'];
        $select_quantity = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
        $result_quantity = mysqli_query($conn, $select_quantity);

        if (mysqli_num_rows($result_quantity) == 0) {
          // An item in the cart is out of stock
          $canProceedToPayment = false;
          break; // Exit the loop early
        }
      }
    }

    if ($canProceedToPayment) {
      $_SESSION['cm_id'] = $cm_id;
      $_SESSION['card_id'] = $card_id;
      header("Location: payment.php");
      echo "Go to payment page";
      // You can uncomment the header line if you want to redirect to the payment page
      // header("Location: Payment.php");
    } else {
      throw new Exception('An item in your cart is out of stock. Please remove it to proceed to checkout');
    }

  } catch (Exception $e) {
    // Handle the exception by displaying an alert
    echo "<script>alert('" . $e->getMessage() . "')</script>";
  }
}








///////////////////////////////////////////////////////////////////////////


//logout button
if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: index.php");
}
//cart button
if (isset($_POST['cart'])) {
  $quantity = $_POST['quantity'];
  header("Location: cart.php");
}
if (isset($_POST['update_quantity'])) {
  $quantity = $_POST['quantity'];
  $item_id = $_POST['update_quantity'];
  $previous_quantity = $_POST['previous_quantity'];
  $cm_id = $_POST['Cart_Master_ID'];
  //drop item from cart child



  $sql = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $selling_price = $row['Selling_Price'];
  $decrease_price = $selling_price * $previous_quantity;
  //decrease price in tbl_cart_master
  $sql = "SELECT * FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $total_amount = $row['Total_Amount'];
  $total_amount = $total_amount - $decrease_price;
  $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
  mysqli_query($conn, $sql);
  //increase price in tbl_cart_master
  $increase_price = $selling_price * $quantity;
  //select updated total amount
  $sql = "SELECT * FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $total_amount = $row['Total_Amount'];
  $total_amount = $total_amount + $increase_price;
  $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
  mysqli_query($conn, $sql);
  //update quantity and price in tbl_child cart
  $sql = "UPDATE tbl_cart_child SET Quantity = '$quantity', Price = '$increase_price' WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  mysqli_query($conn, $sql);
  header("Location: cart.php");
  /*
    $decrease_price = $Cost_Per_Piece * $previous_quantity;
    //decrease price in tbl_cart_master
    $sql = "SELECT * FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    $total_amount = $row['Total_Amount'];
    $total_amount = $total_amount - $decrease_price;
    $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
    mysqli_query($conn,$sql);
    //increase price in tbl_cart_master
    $increase_price = $Cost_Per_Piece * $quantity;
    $total_amount = $total_amount + $increase_price;
    $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
    mysqli_query($conn,$sql);
    //update quantity and price in tbl_child cart

      */
}
if (isset($_POST['remove_item'])) {
  $item_id = $_POST['remove_item'];
  $cm_id = $_POST['Cart_Master_ID'];
  $sql = "SELECT Price FROM tbl_cart_child WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $price = $row['Price'];
  //echo $price;
  //delete row with item id in tbl_cart_child
  $sql = "DELETE FROM tbl_cart_child WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  mysqli_query($conn, $sql);
  //set total amount in tbl_cart_master to be subtracted from price
  $sql = "SELECT Total_Amount FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $total_amount = $row['Total_Amount'];
  $total_amount = $total_amount - $price;
  $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
  mysqli_query($conn, $sql);
  header("Location: cart.php");




  /*
  //set quantity in tbl_cart_child to 0
  $sql = "UPDATE tbl_cart_child SET Quantity = 0 WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  mysqli_query($conn,$sql);
  //set price in tbl_cart_child to 0
  $sql = "UPDATE tbl_cart_child SET Price = 0 WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
  mysqli_query($conn,$sql);
  //set total amount in tbl_cart_master to be subtracted from price
  $sql = "SELECT * FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($result);
  $total_amount = $row['Total_Amount'];
  $total_amount = $total_amount - $price;
  $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
  mysqli_query($conn,$sql);
  header("Location: cart.php");*/
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

  <script>
    var jsMessage1 = <?php echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
    var jsMessage2 = <?php echo json_encode($usertype); ?>;

    // Display the PHP variable value as an alert in JavaScript
    alert(jsMessage1);
    alert(jsMessage2);
  </script>


  <!--script-->

  <style>
    body {
      padding: 0%;
      margin: 0%;
      background-color: rgba(255, 255, 255, 0.9);
      overflow-y: hidden;
    }

    .home-outer {
      width: 100%;
      height: 100vh;
      background-color: rgb(211 247 255);
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
      background-size: contain;
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
      width: 80%;
      padding-right: 4%;
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

    .content-section {
      width: 100%;
      min-height: 90vh;
      background-color: transparent;
      display: flex;
      align-items: center;
    }

    .cart-left-outer {
      width: 60%;
      height: 90vh;
      background-color: transparent;
      display: grid;
      justify-content: center;
      align-items: center;
    }

    .items-outer {
      width: 100%;
      margin-inline-start: 2%;
      max-height: 72vh;
      /* Set the desired maximum height */
      overflow-y: scroll;
    }

    .each-item {
      width: 100%;
      table-layout: fixed;
      background-color: rgb(25, 2, 25);
      border-collapse: collapse;
    }

    .each-item tr {

      color: rgb(0, 0, 0);
    }

    .each-item tr td {
      background-color: rgb(255, 255, 255);
      padding: 1%;
      text-align: center;
    }

    .form_button input[type=number] {
      width: 35%;
      text-align: center;
      border: none;

    }

    .form_button button {
      width: 45%;
      height: 20%;
      text-align: center;
      border: none;
      border-radius: 5px;
      background-color: rgb(6, 28, 100);
      color: white;
    }

    .cart-right-outer {
      padding-inline-start: 2%;
      width: 40%;
      height: 90vh;
      background-color: transparent;
      display: inline-grid;
      align-items: center;
    }

    .cart-summary,
    .cart-summary-bottom,
    .cart-address,
    .cart-card {
      width: 98%;
      height: 28.5vh;
      background-color: rgb(256, 256, 256);
    }

    .cart-summary-table {
      width: 34vh;
      height: 100%;
      border-collapse: collapse;
    }

    .cart-summary-table tr td {
      padding: 1%;
      text-align: center;
    }

    .check-out-button {
      width: 80%;
      height: 100%;
      border-style: none;
      background-color: #fb641b;
      color: white;
      font-size: large;
      font-weight: 550;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      transition: 0.4s;
    }

    .items-top {
      width: 100%;
      color: white;
      display: flex;
      align-items: center;
      height: 5vh;
      background-color: #2874f0;
      font-family: inherit;
      font-weight: 480;
      position: sticky;
      top: 0;

    }

    .address-top {
      width: 100%;
      color: white;
      display: flex;
      align-items: center;
      height: 5vh;
      background-color: #2874f0;
      font-family: inherit;
      font-weight: 480;
    }

    .address-table {
      width: 100%;
      height: max-content;
      margin-top: 5%;
      margin-inline-start: 2%;
      font-family: serif;

    }


    .cards-list {
      width: 100%;
      height: 87%;
      background-color: rgb(256, 256, 256);
      margin-top: 2%;
      display: flex;
      justify-content: center;
      overflow-y: scroll;
    }

    .cards-outer {
      padding-top: 3%;
      width: 97%;
      height: max-content;
      table-layout: auto;
    }

    .cards-outer th {
      text-align: center;
      border: 1px solid black;
      border-collapse: collapse;
      color: rgb(256, 256, 256);
      background-color: rgb(0, 0, 0);
      font-weight: 400;
    }

    .cards-outer tbody tr:nth-child(odd) {
      background-color: rgb(0, 0, 0, 0.1);
      /* Set your desired background color */
      /* Set text color for better contrast */
    }

    .cards-outer td,
    th {
      text-align: center;
      color: rgb(0, 0, 0);
      border-collapse: collapse;
      height: 6vh;
    }


    .deactivate_button,
    .deactivate_button:hover,
    .deactivate_button:active {
      background-color: rgb(239 51 36);
      color: rgb(256, 256, 256);
      border-style: none;
      width: -webkit-fill-available;
      margin: auto;
    }

    .activate_button,
    .activate_button:hover,
    .activate_button:active {
      background-color: rgb(78 198 111);
      color: rgb(256, 256, 256);
      border-style: none;
      width: -webkit-fill-available;
      margin: auto;
    }
  </style>
  <script>
    var update_quantity_status = <?php echo json_encode($quantity_update_status); ?>;
    var jsMessage1 = "Quantity decreased to available stock as Stocks decreased.";
    if (update_quantity_status !== null && update_quantity_status == 1) {
      alert(jsMessage1);
    }
  </script>

</head>

<body>
  <div class="home-outer">

    <div class="top-navigation">
      <a href="index.php">
        <div class="navigation-logo"></div>
      </a>
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
              $cm_id = null;
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
    <div class="content-section">

      <div class="cart-left-outer">
        <div class="items-outer">
          <div class="items-top" style="padding-inline-start:2%;">CART ITEMS</div>
          <table class="each-item">
            <?php
            if ($cm_id != null) {
              $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
              $result = mysqli_query($conn, $sql);
              $count = mysqli_num_rows($result);
              if ($count > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  $item_id = $row['Appliance_ID'];
                  $quantity = $row['Quantity'];
                  $previous_quantity = $quantity;
                  $price = $row['Price'];
                  $sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$item_id'";
                  $result1 = mysqli_query($conn, $sql);
                  $row1 = mysqli_fetch_assoc($result1);
                  $product_name = $row1['Appliance_Name'];
                  $product_image = $row1['Appliance_Image1'];
                  echo "<tr>";
                  echo "<td><img src='$product_image' height='100vh'></td>";
                  echo "<td>$product_name</td>";

                  //$sql_quantity
                  $select_quantity = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
                  $result_quantity = mysqli_query($conn, $select_quantity);
                  if (mysqli_num_rows($result_quantity) > 0) {
                    $row_quantity = mysqli_fetch_assoc($result_quantity);
                    $available_quantity = $row_quantity['Balance_Stock'];
                    /*if ($quantity > $available_quantity) {
                      $deficit = $quantity - $available_quantity;
                      $quantity = $available_quantity;
                      $sql = "UPDATE tbl_cart_child SET Quantity = '$quantity' WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
                      mysqli_query($conn, $sql);
                      //select Total_Amount from tbl_cart_master
                      $sql = "SELECT Total_Amount FROM tbl_cart_master WHERE CM_ID = '$cm_id'";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result);
                      $total_amount = $row['Total_Amount'];
                      //decrease price in tbl_cart_master
                      $selling_price = $row_quantity['Selling_Price'];
                      $decrease_price = $selling_price * $deficit;
                      $total_amount = $total_amount - $decrease_price;
                      $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
                      mysqli_query($conn, $sql);
                      //update price in tbl_cart_child
                      $price = $selling_price * $quantity;
                      $sql = "UPDATE tbl_cart_child SET Price = '$price' WHERE Appliance_ID = '$item_id' AND CM_ID = '$cm_id'";
                      mysqli_query($conn, $sql);
                    }*/
                    //echo "<td>Available: ".$row_quantity['Balance_Stock']."</td>";
            
                    //$quantity increase and decrease button
                    echo "<td><form method='POST' class='form_button'><input type='hidden' value='$cm_id' name='Cart_Master_ID'><input type='hidden' value='$previous_quantity' name='previous_quantity'><input type='number' name='quantity' value='$quantity' min='1' max='$available_quantity'><button type='submit' name='update_quantity' value='$item_id'>Update</button></td>";
                    echo "<td>₹ $price</td>";
                    echo "<td><button name='remove_item' value='$item_id' style='background-color:transparent;border-style:none'><img src='remove.png' height='30px'></button></td>";
                    echo "</form></tr>";
                  } else if (mysqli_num_rows($result_quantity) == 0) {
                    echo "<form method='POST'>";
                    echo "<td style='color:red;'>OUT OF STOCK</td>";
                    echo "<td>₹ $price</td>";
                    echo "<td><input type='hidden' value='$cm_id' name='Cart_Master_ID'><button name='remove_item' value='$item_id' style='background-color:transparent;border-style:none'><img src='remove2.png' height='30px'></button></td>";
                    echo "</form></tr>";
                  }
                }
              } else {
                echo "<tr><td colspan='4'>No items in cart</td></tr>";
              }
            } else {
              echo "<tr><td colspan='4'>No items in cart</td></tr>";
            }
            ?>
          </table>
        </div>
      </div>

      <div class="cart-right-outer">
        <form method="POST" action="" style="height:100%;width:100%;display: contents;">


          <div class="cart-address">
            <div class="address-top" style="padding-inline-start:2%;">DELIVERY ADDRESS</div>
            <table class="address-table">
              <?php
              if ($userId != null) {
                $sql = "SELECT * FROM tbl_customer WHERE Cust_ID = '$userId'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                //C_Username 	Cust_Fname 	Cust_Lname 	Cust_Phone 	Cust_Gender 	Cust_Hname 	Cust_Street 	Cust_Dist 	State_Ut 	Cust_Pin
                $name = $row['Cust_Fname'] . " " . $row['Cust_Lname'];
                $phone = $row['Cust_Phone'];
                $address = $row['Cust_Hname'] . ", " . $row['Cust_Street'] . ", " . $row['Cust_Dist'] . ", " . $row['State_Ut'] . ", " . $row['Cust_Pin'];
                echo "<tr><td>$name</td></tr>";
                echo "<tr><td>$phone</td></tr>";
                echo "<tr><td>$address</td></tr>";

              } else {
                echo "<td colspan='6'>No address found</td>";
              }
              ?>


            </table>

          </div>

          <!--card details-->
          <div class="cart-card">
            <div class="address-top" style="padding-inline-start:2%;">CARD DETAILS</div>



            <div class="cards-list">
              <table class="cards-outer">


                <?php
                $query = "SELECT * FROM tbl_card WHERE Customer_ID = '$userId' AND Card_Status = 1";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                  echo "<tr>";
                  echo "<th></th>";
                  echo "<th>Card Number</th>";
                  echo "<th>Bank Name</th>";
                  echo "</tr>";
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='radio' name='card_id' value='" . $row['Card_ID'] . "'></td>";
                    //echo "<td>" . $row['Card_No'] . "</td>";
                    echo "<td>XXXX-XXXX-XXXX-" . substr($row['Card_No'], -4) . "</td>";
                    echo "<td>" . $row['Bank_Name'] . "</td>";
                    echo "<tr>";
                  }

                } else if ($result->num_rows == 0) {
                  echo "<tr>";
                  echo "<td colspan='2' style='text-align:center;'>No cards added yet!</td>";
                  echo "</tr>";
                }




                ?>

              </table>
            </div>









          </div>
          <!--cart summary-->
          <div class="cart-summary-bottom"
            style="height:18vh;background-color:transparent;display: grid;text-align: center;justify-content: center;align-items: center;">
            <div class="cart-summary-body" style="height: 64%;width: max-content;">
              <table class="cart-summary-table">
                <tr>
                  <?php
                  if ($cm_id != null) {
                    //select * from tbl_cart_child where cm_id = '$cm_id'
                    $sql = "SELECT Price FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
                    $result = mysqli_query($conn, $sql);
                    $total_amount = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                      $price = $row['Price'];
                      $total_amount = $total_amount + $price;
                    }
                    //update total amount in tbl_cart_master
                    $sql = "UPDATE tbl_cart_master SET Total_Amount = '$total_amount' WHERE CM_ID = '$cm_id'";
                    mysqli_query($conn, $sql);
                  } else {
                    $total_amount = 0;
                  }
                  echo "<td><h5 style='color:red;'>₹ $total_amount</h5></td>";
                  ?>
                </tr>
              </table>
            </div>
            <div class="cart-summary-footer" style="height:7vh">

              <?php
              echo "<input type='hidden' name='Master_ID' value='$cm_id'>";
              ?>
              <button type="submit" name="checkout" class="check-out-button">Checkout</button>
        </form>
      </div>

    </div>

    </form>


  </div>

  </div>

</body>
<?php
$quantity_update_status = null;
?>

</html>