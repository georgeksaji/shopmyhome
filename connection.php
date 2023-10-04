<?php  
$hostname = "localhost";  
$username = "root";  
$password = "";
$db_name="ohas";
//create connection
$conn = mysqli_connect($hostname, $username, $password, $db_name);  
//check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    //echo '<script>alert("Connected Successfully")</script>';
}



//Full texts
	//Courier_Assign_ID 	Courier_ID 	CM_ID 	Customer_ID 	Courier_Assign_Date 	Max_Delivery_Date 	Accept_CA_Status 	Delivery_Error_Status 	Delivery_Status 
    $sql_select_max_delivery_date="SELECT * FROM tbl_courier_assign WHERE Max_Delivery_Date < CURDATE() AND Delivery_Status = 'NOT DELIVERED' AND Delivery_Status != 'REASSIGNED'";
    $result_select_max_delivery_date = mysqli_query($conn, $sql_select_max_delivery_date);
    $count_select_max_delivery_date = mysqli_num_rows($result_select_max_delivery_date);
    //set delivery status to reassigned 
    if($count_select_max_delivery_date > 0){
        while($row_select_max_delivery_date = mysqli_fetch_assoc($result_select_max_delivery_date)){
            $Courier_Assign_ID = $row_select_max_delivery_date['Courier_Assign_ID'];
            $cm_id = $row_select_max_delivery_date['CM_ID'];
            $cour_id = $row_select_max_delivery_date['Courier_ID'];
            $sql_update_delivery_status = "UPDATE tbl_courier_assign SET Delivery_Status = 'REASSIGNED' WHERE Courier_Assign_ID = '$Courier_Assign_ID'";
            $result_update_delivery_status = mysqli_query($conn, $sql_update_delivery_status);

            
            //set Courier_Assignment_Status  in tbl payment to 0 where $cm_id
            $sql_update_courier_assignment_status = "UPDATE tbl_payment SET Courier_Assignment_Status = '0' WHERE CM_ID = '$cm_id'";
            $result_update_courier_assignment_status = mysqli_query($conn, $sql_update_courier_assignment_status);
            //set Courier_Assignment_Status  in tbl customer to 0 where $cm_id
            $sql_update_status = "UPDATE tbl_courier SET Cour_Status  = '0' WHERE Cour_ID = '$cour_id'";
            $result_update_status = mysqli_query($conn, $sql_update_status);


        }
    }

?>  