<?php
include('connection.php');

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];
if(isset($_SESSION['Invoice_Number'])) {
    $invoice_number = $_SESSION['Invoice_Number'];
    // Your code to handle the $invoice_number goes here
} else {
    echo "Invoice Number is not set in the session."; // Handle the case when the Invoice_Number is not set
}


if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
}
if (isset($_POST['home'])) {
    header('location: index.php');
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Invoice</title>
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>



    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        

    <style>
        body {
            padding: 0%;
            margin: 0%;
            background-color: rgba(255, 255, 255, 0.9);
            overflow-y: scroll;
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
            background-color: rgba(256, 256, 256);
            display: list-item;
            display: flex;
            justify-content: center;
            padding-top: 4%;
        }

        .report-table {
            width: 85%;
            height: 85vh;
            border-collapse: collapse;
            border-style: none;
            background-color: transparent;
            transition: 0.3s;
            font-size: medium;
            font-family: 'Tines New Roman', Times, serif;
            color: rgb(0, 0, 0);
        }

        .report-table td,
          th {
            border-style: dotted;
    border-width: 1px;
    border-color: rgb(0, 0, 0);
    padding: 1vh;
    text-align: center;
    /* white-space: nowrap; */
        }
        
        .report-table th {
            background-color: rgb(42, 85, 229);
            color: rgb(256, 256, 256);
            font-weight: 400;
            font-family: fangsong;
            font-size:110%;
        }

        .logo {
            background-image: url(Picture3.png);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 10vh;
            width: 100%;
        }

        .print_button_outer {
            width: 100%;
            height: 10vh;
            display: flex;
            justify-content: center;
            align-items: top;
        }

        .add_buttons {
            background-color: rgb(0, 0, 0);
            color: rgb(256, 256, 256);
            border-style: none;
            border-radius: 5px;
            font-size: medium;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: 0.3s;
            cursor: pointer;
            text-align: center;
            margin:auto;
            width: 8%;
            height: 61%;
        }
        .signature{
            background-image: url(signature.jpg);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 10vh;
            width: 100%;
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
                    //if userid =ST00001 AND usertype=AD then a=admin.php
                    if($usertype == 'CU') {
                        //profile page, logout
                        echo '<form action="" method="POST">';
                        //echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
                        //cart button
                        $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'ASSIGNED'";
                        $result = mysqli_query($conn,$sql);
                        if(mysqli_num_rows($result) > 0)
                        {
                        $row = mysqli_fetch_assoc($result);
                        $cm_id = $row['CM_ID'];
                        $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$cm_id'";
                        $result = mysqli_query($conn,$sql);
                        $count = mysqli_num_rows($result);
                        echo "<td><button type='submit' class='profile-box' name='cart'><img src='cart.png' height='30px' width='30px'><span style='font-size: 0.95em; color: rgb(239,51,36)' class='badge text-bg-secondary'>$count</span></button></td>";
                        }
                        else if(mysqli_num_rows($result) == 0)
                        {
                          $cm_id = null;
                          echo "<td><button type='submit' class='profile-box' name='cart'><img src='cart.png' height='30px' width='30px'><span style='font-size: 0.95em; color: rgb(239,51,36)' class='badge text-bg-secondary'>0</span></button></td>"; 
                        }
                         echo '<td><button class="admin-box" name="home">Home</button></td>';
                         echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                        echo '</tr>';
                        echo '</form>';
                      }
                      ?>
                </table>
            </div>
        </div>
        <div id="report" class="content-section">
            <!--<div class="invoice-outer-section">-->
                <?php if ($usertype == 'CU') {
                    echo '<table class="report-table">';
                    echo '<tr rowspan="3">';
                    echo '<td colspan="2" style="text-align: center;">';
                    echo "<div class='logo'></div>";
                    echo '</td>';
                    echo '<td colspan="3" style="text-align: center;">';
                    echo '<h6>Tax Invoice / Bill of Supply / Cash Memo</h6>';
                    //echo current date
                    $date = date("d/m/Y");
                    echo "<br><h6>Invoice Date: $date</h6>";
                    echo '</tr>';
                    //seller and buyer details
                    echo '<tr>';
                    echo '<td colspan="2" style="text-align: center;">';
                    echo '<h6>BUYER ADDRESS</h6>';
                    //select customer details
                    // 	C_Username 	Cust_Fname 	Cust_Lname 	Cust_Phone 	Cust_Hname 	Cust_Street 	Cust_Dist 	State_Ut 	Cust_Pin
                    $sql = "SELECT C_Username,Cust_Fname,Cust_Lname,Cust_Phone,Cust_Hname,Cust_Street,Cust_Dist,State_Ut,Cust_Pin FROM tbl_customer WHERE Cust_ID = '$userId'";
                    $result = mysqli_query($conn,$sql);
                    $row = mysqli_fetch_assoc($result);
                    $cust_fname = $row['Cust_Fname'];
                    $cust_lname = $row['Cust_Lname'];
                    $cust_phone = $row['Cust_Phone'];
                    $cust_street = $row['Cust_Street'];
                    $cust_dist = $row['Cust_Dist'];
                    $state_ut = $row['State_Ut'];
                    $cust_pin = $row['Cust_Pin'];
                    echo "$cust_fname $cust_lname <br>$cust_phone <br>$cust_street, $cust_dist <br>$state_ut, $cust_pin";
                    echo '</td>';
                    echo '<td colspan="3" style="text-align: center;">';
                    echo "<h6>SELLER ADDRESS</h6>";
                    echo 'Rajagiri Valley P.O, <br>Kakkanad, Kochi, <br>Kerala, 682039';
                    echo '</td></tr>';
                    //order number and date from tbl_payment
                    $sql = "SELECT * FROM tbl_payment WHERE CM_ID = '$invoice_number'";
                    $result = mysqli_query($conn,$sql);
                    $row = mysqli_fetch_assoc($result);
                    $invoice_date = $row['Payment_Date'];
                    $invoice_id = $row['Payment_ID'];

                    echo '<tr rowspan="3">';
                    echo '<td colspan="2" style="text-align: center;">';
                    echo "<h6>Payment ID: $invoice_id</h6>";
                    echo '</td>';
                    echo '<td colspan="3" style="text-align: center;">';
                    echo "<h6>Order Date: $invoice_date</h6>";
                    echo '</td>';
                    //headings
                    echo '<tr>';
                    echo '<th>Sl.no.</th>';
                    echo '<th>Appliance</th>';
                    echo '<th>Unit Price</th>';
                    echo '<th>Quantity</th>';
                    echo '<th>Total</th>';
                    echo '</tr>';
                    //table data
                    $sql = "SELECT * FROM tbl_cart_master WHERE CM_ID = '$invoice_number'";
                    $result = mysqli_query($conn,$sql);
                    if(mysqli_num_rows($result) > 0)
                    {
                        $row = mysqli_fetch_assoc($result);
                        $invoice_total = $row['Total_Amount'];
                        $sql = "SELECT * FROM tbl_cart_child WHERE CM_ID = '$invoice_number'";
                        $result = mysqli_query($conn,$sql);
                        $count = mysqli_num_rows($result);
                        $i = 1;
                        while($row = mysqli_fetch_assoc($result))
                        {
                            $appliance_id = $row['Appliance_ID'];
                            $quantity = $row['Quantity'];  
                            $total = $row['Price'];
                            //select appliance name
                            $sql = "SELECT * FROM tbl_appliance WHERE Appliance_ID = '$appliance_id'";
                            $result1 = mysqli_query($conn,$sql);
                            $row1 = mysqli_fetch_assoc($result1);
                            $appliance_name = $row1['Appliance_Name'];
                            $brand_id = $row1['Brand_ID'];
                            $type_id = $row1['Type_ID'];
                            //select brand name
                            $sql = "SELECT Brand_Name FROM tbl_brand WHERE Brand_ID = '$brand_id'";
                            $result1 = mysqli_query($conn,$sql);
                            $row1 = mysqli_fetch_assoc($result1);
                            $brand_name = $row1['Brand_Name'];
                            //select type name
                            $sql = "SELECT Type_Name FROM tbl_type WHERE Type_ID = '$type_id'";
                            $result1 = mysqli_query($conn,$sql);
                            $row1 = mysqli_fetch_assoc($result1);
                            $type_name = $row1['Type_Name'];
                            //remove last letter from type name string
                            $type_name = substr($type_name, 0, -1);

                            
                            echo '<tr>';
                            echo "<td>$i</td>";
                            echo "<td>$brand_name $appliance_name $type_name</td>";
                            $unit_price = $total/$quantity;
                            echo "<td>₹ $unit_price</td>";
                            echo "<td>$quantity</td>";
                            echo "<td>₹ $total</td>";
                            echo '</tr>';
                            $i++;
                        }
                        echo '<tr style="background-color:rgb(255 220 51);color:rgb(0 0 0);">';
                        echo '<td colspan="4" style="text-align:right">Total</td>';
                        echo "<td>₹ $invoice_total</td>";
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td colspan="4">This invoice is authorised for Shopmyhome by George K Saji, CEO, Shopmyhome.</td>';
                        echo "<td><div class='signature'></div></td>";
                    }
                    echo '</table>';
                }   
                ?>
            </div>
            <div class="print_button_outer">
                <!--<form method='POST' style='height:100%;width:100%;display: flex;justify-content: center;'>
                <button class="add_buttons" id="downloadBtn">Download</button>
                </form>-->
            </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var element = document.getElementById('report'); // Replace 'report' with the actual ID of your table or container

    // Check if the element exists
    if (element) {
        html2pdf(element, {
            margin: 10,
            filename: 'Invoice.pdf',
            image: { type: 'png', quality: 1.0 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        });
    } else {
        console.error('Element not found.');
    }
});
</script>
</html>