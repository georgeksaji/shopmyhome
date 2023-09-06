<?php
include("connection.php");

if (isset($_POST['update'])) {
    $courier_id = $_POST['Courier_id'];
    $couriername = $_POST['Couriername'];
    $username = $_POST['username'];
    $phone = $_POST['phone']; 
    $Oname = $_POST['Oname'];
    $street = $_POST['street'];
    $district = $_POST['district'];
    $pincode = $_POST['pincode'];
   /* $uid = $_SESSION['User_ID'];
    echo $uid;*/
    // Retrieve other edited attributes

    // Update the customer data in the database
    $update_query = "UPDATE tbl_Courier
                     SET Cs_name = '$couriername', username='$username',Cs_phno='$phone',Cs_office_name='$Oname',Cs_street='$street',
      Cs_dist='$district',Cs_pin='$pincode'
                     WHERE Courier_id = '$courier_id'";
    mysqli_query($conn, $update_query);
    // Redirect back to the customer details page
    header("Location: editcour.php");
    exit();
}
?>
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Courier</title>
    <style>
        body {
            font-family: times new roman ;
            background-color: skyblue;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>

</head>
<body>
    <h1>Edit Courier</h1>
    <form method="POST" action="update_courier.php">
        <input type="hidden" name="courier_id" value="<?php echo $courier_data['Courier_id']; ?>">
        First Name: <input type="text" name="couriername" value="<?php echo $courier_data['Cs_name']; ?>"><br>
        <input type="hidden" name="staff_id" value="<?php echo $courier_data['Staff_id']; ?>">
        Email ID: <input type="email" name="username" value="<?php echo $courier_data['username']; ?>"><br>
        Phone number: <input type="text" name="phone" maxlength="10" value="<?php echo $courier_data['Cs_phno']; ?>"><br>
        
        Office name: <input type="text" name="Oname" value="<?php echo $courier_data['Cs_office_name']; ?>"><br>
        Street: <input type="text" name="street" value="<?php echo $courier_data['Cs_street']; ?>"><br>
        District: <input type="text" name="district" value="<?php echo $courier_data['Cs_dist']; ?>"><br>
        pincode: <input type="text" name="pincode" value="<?php echo $courier_data['Cs_pin']; ?>"><br>
       joining date: <input type="text" name="j_date" value="<?php echo $courier_data['Cs_join_date']; ?>"><br>
        courier status: <input type="text" name="csstatus" value="<?php echo $courier_data['Courier_status']; ?>"><br>
        <!-- Other input fields for other attributes -->
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>