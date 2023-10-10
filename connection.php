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

    //reassigning courier
    $sql_select_max_delivery_date="SELECT * FROM tbl_courier_assign WHERE Max_Delivery_Date < CURDATE() AND Delivery_Status != 'REASSIGNED' AND Delivery_Status != 'DELIVERED'";
    $result_select_max_delivery_date = mysqli_query($conn, $sql_select_max_delivery_date);
    $count_select_max_delivery_date = mysqli_num_rows($result_select_max_delivery_date);
    //set delivery status to reassigned 
    if($count_select_max_delivery_date > 0){
        while($row_select_max_delivery_date = mysqli_fetch_assoc($result_select_max_delivery_date)){
            $Courier_Assign_ID = $row_select_max_delivery_date['Courier_Assign_ID'];
            $cm_id = $row_select_max_delivery_date['CM_ID'];
            $cour_id = $row_select_max_delivery_date['Courier_ID'];
            $delivery_error_status = $row_select_max_delivery_date['Delivery_Error_Status'];


                
            //select courierpartner id and save it in reassigned courier id

            

            $sql_update_delivery_status = "UPDATE tbl_courier_assign SET Delivery_Status = 'REASSIGNED' WHERE Courier_Assign_ID = '$Courier_Assign_ID'";
            $result_update_delivery_status = mysqli_query($conn, $sql_update_delivery_status);
            //set  Cart_Status 	= Reassigned in tbl cart where $cm_id
            $sql_update_cart_status = "UPDATE tbl_cart_master SET Cart_Status = 'REASSIGNED' WHERE CM_ID = '$cm_id'";
            $result_update_cart_status = mysqli_query($conn, $sql_update_cart_status);

            
            //set Courier_Assignment_Status  in tbl payment to 0 where $cm_id
            //$sql_update_courier_assignment_status = "UPDATE tbl_payment SET Courier_Assignment_Status = '0' WHERE CM_ID = '$cm_id'";
            //$result_update_courier_assignment_status = mysqli_query($conn, $sql_update_courier_assignment_status);
            //set Courier_Assignment_Status  in tbl customer to 0 where $cm_id
            if($delivery_error_status < 3){   
                //// Assuming $cour_id is coming from user input or any other sourcE

                $sql_ca_status = "SELECT * FROM tbl_courier_assign WHERE Courier_ID = '$cour_id' AND (Delivery_Status = 'ASSIGNED' OR Delivery_Status = 'SHIPPED')";
                //$sql_ca_status = "SELECT * FROM tbl_courier_assign WHERE Courier_ID = $cour_id AND (Delivery_Status = 'ASSIGNED' OR Delivery_Status = 'SHIPPED')";
                $result_ca_status = mysqli_query($conn, $sql_ca_status);
                $ca_status = mysqli_num_rows($result_ca_status);
                if ($ca_status > 0) { 
                    //terminate courier
                    $sql_update_status = "UPDATE tbl_courier SET Cour_Status  = '2' WHERE Cour_ID = '$cour_id'";
                   $result_update_status = mysqli_query($conn, $sql_update_status);
                 
                } else { 
                       //in tbl courier set status to 3 courier can log in but no new orders will be assigned
                       $sql_update_status = "UPDATE tbl_courier SET Cour_Status  = '3' WHERE Cour_ID = '$cour_id'";
                       $result_update_status = mysqli_query($conn, $sql_update_status);
                   
                }   
          
            }

        }
    }

    
    //set status to 2 for expired cards
      $select_all_cards="SELECT * FROM tbl_card WHERE Expiry_Date < CURDATE() AND Card_Status != '2'";
        $result_select_all_cards = mysqli_query($conn, $select_all_cards);
        $count_select_all_cards = mysqli_num_rows($result_select_all_cards);
        if($count_select_all_cards > 0){
            while($row_select_all_cards = mysqli_fetch_assoc($result_select_all_cards)){
                $card_id = $row_select_all_cards['Card_ID'];
                $sql_update_card_status = "UPDATE tbl_card SET Card_Status = '2' WHERE Card_ID = '$card_id'";
                $result_update_card_status = mysqli_query($conn, $sql_update_card_status);
            }
        }


?>  