<?php
include("connection.php");
session_start();
$_SESSION['search'] = null;
$_SESSION['brand_id'] = null;
$_SESSION['category_id'] = null;
$_SESSION['type_id'] = null;
//$product_detail_id = $_SESSION['product_detail_id'];

if (isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
    $userId = $_SESSION['User_ID'];
    $usertype = $_SESSION['User_Type'];
} else {
    $userId = null;
    $usertype = null;
}


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

$quantity_update_status = 0;
$price_update_status = 0;



$cm_id = $_SESSION['cm_id'];
$card_id = $_SESSION['card_id'];


//if isset pay
if(isset($_POST['pay']))
{
    $card_number = $_POST['card-number'];
    $cvv = $_POST['cvv'];
    $sql = "SELECT CVV FROM tbl_card WHERE Card_ID = '$card_id' AND Card_No = '$card_number'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);
        if($row['CVV'] == $cvv)
        {

            //$purchase_child_ID new array
            $purchase_child_ID = array();
                
                //confirm products availabe in stock and price validation
                $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $item_id = $row['Appliance_ID'];
                        $quantity = $row['Quantity'];
                        $price = $row['Price'];
                        $price_of_one = $price / $quantity;
                        //find purchase child where appliance_id = item_id
                        
                        $sql = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
                        $result_pc = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result_pc) > 0)
                        {
                            $row_quantity = mysqli_fetch_assoc($result_pc);
                            $available_quantity = $row_quantity['Balance_Stock'];
                            $purchase_child_ID[] = $row_quantity['Purchase_Child_ID'];
                            if($quantity > $available_quantity)
                            {
                            $quantity_update_status = 1;
                            }
                        //check if selleing price has changed
                            $selling_price = $row_quantity['Selling_Price'];
                            //echo $selling_price;
                            //echo $price_of_one;
                            $round_selling_price = round($selling_price);
                            $round_price_of_one = round($price_of_one);
                            //echo $round_selling_price;
                            //echo $round_price_of_one;
                            //echo " ";
                            if($round_selling_price != $round_price_of_one)
                            {
                                $price_update_status = 1;
                            }
                        }
                        else
                        {
                            $quantity_update_status = 1;
                        }
                    }
                }
                //both quantity and price are valid
                if($quantity_update_status == 0 && $price_update_status == 0)
                {
                    
                    $temp=0;
                    $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
                    $cart_items = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($cart_items) > 0)
                    {
                        while($row = mysqli_fetch_assoc($cart_items))
                        {

                            $item_id = $row['Appliance_ID'];
                            //echo $item_id; 
                            //echo ", ";
                            $quantity = $row['Quantity'];
                            //echo $quantity;
                            //echo "  ";
                            //echo $purchase_child_ID[$temp];
                            $sql = "SELECT * FROM tbl_purchase_child WHERE Purchase_Child_ID = '$purchase_child_ID[$temp]'";
                            $result_purchase_child = mysqli_query($conn, $sql);
                            $row_purchase_child = mysqli_fetch_assoc($result_purchase_child);
                            $available_quantity = $row_purchase_child['Balance_Stock'];
                            $new_quantity = $available_quantity - $quantity;
                            //update balance stock of item
                            $sql = "UPDATE tbl_purchase_child SET Balance_Stock = '$new_quantity' WHERE Purchase_Child_ID = '$purchase_child_ID[$temp]'";
                            $update_balance_stock = mysqli_query($conn, $sql);
                            $temp++;
                        }
                    }
                    

                    /*if(mysqli_num_rows($result) > 0)
                    {
                        while($row = mysqli_fetch_assoc($result))
                        {
                            $item_id = $row['Appliance_ID'];
                            $quantity = $row['Quantity'];
                            $price = $row['Price'];
                            $price_of_one = $price / $quantity;
                            //find purchase child where appliance_id = item_id
                            //$sql = "SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$item_id' AND Balance_Stock > 0 ORDER BY Purchase_Child_ID ASC LIMIT 1";
                            //select from $purchase_child_id
                            $sql = "SELECT * FROM tbl_purchase_child WHERE Purchase_Child_ID = '$purchase_child_id'";
                            $result_pc = mysqli_query($conn, $sql);
                            //update balace stock of all iems from tbl_purchase_child
                            //balance stock = balance stock - quantity
                            $row_quantity = mysqli_fetch_assoc($result_pc);
                            $available_quantity = $row_quantity['Balance_Stock'];
                            $new_quantity = $available_quantity - $quantity;
                            $sql = "UPDATE tbl_purchase_child SET Balance_Stock = '$new_quantity' WHERE Purchase_Child_ID = '$purchase_child_id'";
                            $result = mysqli_query($conn, $sql);
                        }
                    }*/
                    //update cart status to paid
                    $sql = "UPDATE tbl_cart_master SET Cart_Status = 'PAID' WHERE CM_ID = '$cm_id'";
                    $result = mysqli_query($conn, $sql);
                    //insert into tbl_payment
                    $sql = "INSERT INTO tbl_payment(Payment_ID,CM_ID,Card_ID) VALUES (generate_payment_id(),'$cm_id','$card_id')";
                    $result = mysqli_query($conn, $sql);
                    echo '<script>alert("Payment Successfull");</script>'; 
                }

                else if($quantity_update_status == 1 && $price_update_status == 0)
                {
                    echo '<script>alert("Quantity of one or more items has changed. Please check your cart");</script>';
                }
                else if($quantity_update_status == 0 && $price_update_status == 1)
                {
                    echo '<script>alert("Price of one or more items has changed. Please check your cart");</script>';
                }
                else if($quantity_update_status == 1 && $price_update_status == 1)
                {
                    echo '<script>alert("Quantity and Price of one or more items has changed. Please check your cart");</script>';
                }
        }
            
        else
        {
            echo '<script>alert("Invalid CVV");</script>';
        }
    }
    else
    {
        echo '<script>alert("Invalid Card Details");</script>';
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Payment</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<!-- 
    <script>
        var jsMessage1 = <?php //echo json_encode($userId); ?>; // Embedding PHP variable in JavaScript
        var jsMessage2 = <?php //echo json_encode($usertype); ?>;

        // Display the PHP variable value as an alert in JavaScript
        alert(jsMessage1);
        alert(jsMessage2);
    </script> -->


    <!--script-->

    <style>
        body {
            padding: 0%;
            margin: 0%;
            background-color: rgba(255, 255, 255, 0.9);
            overflow: hidden;
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
            height: 90vh;
            background-color: transparent;
            display: inline-flex;
        }

        .content-section-left {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-outer {
            width: 40%;
            height: 60%;
            background-color: rgb(255, 255, 255);
            border-radius: 10px;
            justify-content: center;
            border-radius: 10px;
        }

        .card-heading {
            width: 100%;
            height: 10%;
            color: rgb(256, 256, 256);
            padding-left: 5%;
            background-color: #2a55e5;
            border-radius: 10px 10px 0px 0px;
            display: flex;
            align-items: center;
        }

        .input-card-details {
            width: 100%;
            height: 90%;
            display: inline-grid;
            align-items: center;
        }

        .card-type {
            width: 100%;
            height: 20%;
            background-color: rgb(255, 255, 255);
        }

        .card-type td {

            text-align: center;
            width: 50%;
        }

        .input-box {
            width: 100%;
            height: 10%;
            background-color: rgb(255, 255, 255);
            padding-block: 5%;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        input[type=text],
        input[type=number],
        input[type=disabled] {
            width: 50%;
            height: 6vh;
            border-radius: 5px;
            border-style: none;
            font-size: small;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 550;
            color: rgb(0, 0, 0);
            background-color: rgb(6, 28, 100, 0.1);
            transition: 0.4s;
        }

        input[type=number] {
            -webkit-appearance: none;
            -moz-appearance: textfield;
        }

        .card-details2 {
            width: 100%;
            height: 15%;
            background-color: rgb(255, 255, 255);
            margin: auto;
        }

        .card-details2 td {

            text-align: center;
        }

        .input-box button {
            background-color: rgb(255, 216, 21, 0.7);
            border-radius: 5px;
            color: white;
            width: 180px;
            cursor: pointer;
            margin: 20px;
            padding: 8px 5px;
            border: none;
            font-size: 19px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
            width: 241px;
        }
    </style>

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
                    //if userid!=ST00001 AND usertype=ST
                    /*if ($userId != 'ST00001' && $usertype == 'ST') {
                        //profile page,Staff Dashboard, logout
                        echo '<form action="" method="POST">';
                        //profile page
                        echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
                        echo '<td><button class="admin-box" name="admin">Staff Dashboard</button></td>';
                        echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                        echo '</tr>';
                        echo '</form>'; //    
                    }*/
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
            <div class="content-section-left">



                <form method="POST"
                    style="height:100%;width:100%;display:flex;justify-content:center;align-items:center;">
                    <div class="card-outer">
                        <div class="card-heading">Payment</div>
                        <div class="input-card-details">
                            <?php
                            //select card number from tbl_card
                            $sql = "SELECT Card_No FROM tbl_card WHERE Card_ID = '$card_id'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $card_no = $row['Card_No'];
                            ?>

                            <div class="input-box">
                                <label for="card-number">Card Number</label>
                                <input type="text" value="<?php echo 'XXXX-XXXX-XXXX-' . substr($row['Card_No'], -4) . ''; ?>" name="card-nmbr" required disabled>
                                <input type="hidden" value="<?php echo $card_no; ?>" name="card-number">

                            </div>
                            <div class="input-box">
                                <label for="card-holder-name">CVV</label>
                                <input style="margin-left: 11%;" type="number" name="cvv" min="100"
                                    max="9999" placeholder="Enter card CVV" required>
                            </div>
                            <div class="input-box"
                                style="border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                                <button type="submit" name="pay" style="border-radius: 10px;">Pay</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>

    </div>
    </div>

    </div>
</body>

</html>