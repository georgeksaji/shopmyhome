<?php
include('connection.php');

session_start();
$userId = $_SESSION['User_ID'];
$usertype = $_SESSION['User_Type'];

if (isset($_POST['admin'])) {
    header('location:admin.php');
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('location:index.php');
}


?>

<!doctype html>
<html lang="en">

<head>
    <title>shopmyhome</title>
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
            min-height: 100%;
            background-color: rgba(256, 256, 256);
            display: list-item;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .report-table {
            width: 80%;
            height: 100%;
            border-collapse: collapse;
            border-style: none;
            background-color: transparent;
            transition: 0.3s;
            font-size: medium;
            font-family: 'Tines New Roman', Times, serif;
            color: rgb(0, 0, 0);
            white-space: nowrap;
        }

        .report-table td,
        th {
            border-style: solid;
            border-width: 1px;
            border-color: rgb(0, 0, 0);
            padding: 1%;
            text-align: center;
        }

        .report-table th {
            background-color: rgb(42, 85, 229);
            color: rgb(256, 256, 256);
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
            margin:auto

            width: 8%;
            height: 61%;

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
                    if ($userId == 'ST00001' && $usertype == 'AD') {
                        //admin panel page , logout
                        echo '<form action="" method="POST">';
                        echo '<tr><td><button class="admin-box" name="admin">Admin Dashboard</button></td>';
                        echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                        echo '</form>';
                    }
                    ?>
                </table>
            </div>
        </div>
        <div id="report" class="content-section">
            <!--<div class="invoice-outer-section">-->
                <?php if ($usertype == 'AD') {
                    echo '<table class="report-table">';
                    echo '<tr>';
                    echo '<td colspan="7" style="text-align: center;">';
                    echo "<div class='logo'></div>";
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr rowspan="4">';
                    echo '<td colspan="4">SALES REPORT</td>';
                    echo '<td colspan="3">';
                    echo 'Rajagiri Valley P.O, <br>Kakkanad, Kochi, <br>Kerala 682039';
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    $current_date_time = date("Y-m-d H:i:s");
                    echo "<td colspan='7' >Sales report upto: $current_date_time</td>";
                    echo '</tr>';
                    echo '<tr>';
                    echo "<td colspan='7'>Report Details</td>";
                    echo '</tr>';
                    //delivery details
                    echo '<tr>';
                    echo '<th>Payment ID</th>';
                    echo '<th>Cart Master ID</th>';
                    echo '<th>Customer ID</th>';
                    echo '<th>Order Date</th>';
                    echo '<th>Order Amount</th>';
                    echo '<th>Order Status</th>';
                    echo '</tr>';
                    $total_sales_amount = 0;
                    $query_d = "SELECT * FROM tbl_payment"; // Replace with your actual query
                    $delivery_num2 = $conn->query($query_d);
                    if (mysqli_num_rows($delivery_num2) > 0) {
                      while ($row_d = $delivery_num2->fetch_assoc()) {
                        $payment_id = $row_d['Payment_ID'];
                        $delivered_cart_id = $row_d['CM_ID'];
                        $order_date = $row_d['Payment_Date'];
                        //cart master details
                        $sql_cart_master_details = "SELECT Customer_ID,Total_Amount,Cart_Status FROM tbl_cart_master WHERE CM_ID='$delivered_cart_id'";
                        $result_cart_master_details = $conn->query($sql_cart_master_details);
                        $row_cart_master_details = $result_cart_master_details->fetch_assoc();
                        // 	CM_ID 	Customer_ID 	Total_Amount 	Cart_Status 	
                        $customer_id = $row_cart_master_details['Customer_ID'];
                        $total_amount = $row_cart_master_details['Total_Amount'];
                        $cart_status = $row_cart_master_details['Cart_Status'];
                        echo '<tr>';
                        echo '<td>' . $payment_id . '</td>';
                        echo '<td>' . $delivered_cart_id . '</td>';
                        echo '<td>' . $customer_id . '</td>';
                        echo '<td>' . $order_date . '</td>';
                        echo '<td>' . $total_amount . '</td>';
                        $total_sales_amount = $total_sales_amount + $total_amount;
                        echo '<td>' . $cart_status . '</td>';
                       echo '</tr>';
                      }
                      echo '<tr>';
                      echo '<td colspan="6" style="text-align:center">Total Sales : ₹ '.$total_sales_amount.'</td>';
                      echo '</tr>';
                    } else {
                      echo '<tr>';
                      echo '<td colspan="6" style="text-align:center">No Sales Available</td>';
                      echo '</tr>';
                    }
                }
                echo '</table>';
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
            filename: 'Sales report.pdf',
            image: { type: 'png', quality: 1.0 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        });
    } else {
        console.error('Element not found.');
    }
});
</script>
</html>