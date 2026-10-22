<?php
require_once __DIR__ . '/bootstrap.php';

$conn = db();

/**
 * Housekeeping that used to run inline on every page load:
 *  - reassign couriers whose maximum delivery date has passed
 *  - terminate / suspend couriers with repeated delivery failures
 *  - mark expired cards as expired (Card_Status = 2)
 */
function run_maintenance(mysqli $conn): void
{
    $sql_select_max_delivery_date = "SELECT * FROM tbl_courier_assign WHERE Max_Delivery_Date < CURDATE() AND Delivery_Status != 'DELIVERED'";
    $result_select_max_delivery_date = mysqli_query($conn, $sql_select_max_delivery_date);
    $count_select_max_delivery_date = mysqli_num_rows($result_select_max_delivery_date);

    if ($count_select_max_delivery_date > 0) {
        while ($row_select_max_delivery_date = mysqli_fetch_assoc($result_select_max_delivery_date)) {
            $Courier_Assign_ID = $row_select_max_delivery_date['Courier_Assign_ID'];
            $cm_id = $row_select_max_delivery_date['CM_ID'];

            $checkifdelivered = "SELECT * FROM tbl_courier_assign WHERE CM_ID = '$cm_id' AND Delivery_Status = 'DELIVERED'";
            $result_checkifdelivered = mysqli_query($conn, $checkifdelivered);
            $count_checkifdelivered = mysqli_num_rows($result_checkifdelivered);

            if ($count_checkifdelivered == 0) {
                $cour_id = $row_select_max_delivery_date['Courier_ID'];
                $delivery_error_status = $row_select_max_delivery_date['Delivery_Error_Status'];

                $sql_update_delivery_status = "UPDATE tbl_courier_assign SET Delivery_Status = 'REASSIGNED' WHERE Courier_Assign_ID = '$Courier_Assign_ID'";
                mysqli_query($conn, $sql_update_delivery_status);

                $sql_update_cart_status = "UPDATE tbl_cart_master SET Cart_Status = 'REASSIGNED' WHERE CM_ID = '$cm_id'";
                mysqli_query($conn, $sql_update_cart_status);

                if ($delivery_error_status < 3) {
                    $sql_ca_status = "SELECT * FROM tbl_courier_assign WHERE Courier_ID = '$cour_id' AND (Delivery_Status = 'ASSIGNED' OR Delivery_Status = 'SHIPPED')";
                    $result_ca_status = mysqli_query($conn, $sql_ca_status);
                    $ca_status = mysqli_num_rows($result_ca_status);

                    if ($ca_status > 0) {
                        // terminate courier
                        $sql_update_status = "UPDATE tbl_courier SET Cour_Status = '2' WHERE Cour_ID = '$cour_id'";
                        mysqli_query($conn, $sql_update_status);
                    } else {
                        // courier can log in but no new orders will be assigned
                        $sql_update_status = "UPDATE tbl_courier SET Cour_Status = '3' WHERE Cour_ID = '$cour_id'";
                        mysqli_query($conn, $sql_update_status);
                    }
                }
            }
        }
    }

    // Mark expired cards.
    $select_all_cards = "SELECT * FROM tbl_card WHERE Expiry_Date < CURDATE() AND Card_Status != '2'";
    $result_select_all_cards = mysqli_query($conn, $select_all_cards);
    $count_select_all_cards = mysqli_num_rows($result_select_all_cards);

    if ($count_select_all_cards > 0) {
        while ($row_select_all_cards = mysqli_fetch_assoc($result_select_all_cards)) {
            $card_id = $row_select_all_cards['Card_ID'];
            $sql_update_card_status = "UPDATE tbl_card SET Card_Status = '2' WHERE Card_ID = '$card_id'";
            mysqli_query($conn, $sql_update_card_status);
        }
    }
}

run_maintenance($conn);
