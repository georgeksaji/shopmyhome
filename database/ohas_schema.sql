-- ============================================================================
-- shopmyhome / OHAS - database schema
-- MariaDB 10.4+ (XAMPP)
--
-- Usage (from the project root):
--   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < database\ohas_schema.sql
--   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < database\ohas_seed.sql
--
-- WARNING: this script drops and recreates the tables in the `ohas` database.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS ohas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE ohas;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS tbl_delivery;
DROP TABLE IF EXISTS tbl_courier_assign;
DROP TABLE IF EXISTS tbl_card;
DROP TABLE IF EXISTS tbl_payment;
DROP TABLE IF EXISTS tbl_cart_child;
DROP TABLE IF EXISTS tbl_cart_master;
DROP TABLE IF EXISTS tbl_purchase_child;
DROP TABLE IF EXISTS tbl_purchase_master;
DROP TABLE IF EXISTS tbl_appliance;
DROP TABLE IF EXISTS tbl_brand;
DROP TABLE IF EXISTS tbl_type;
DROP TABLE IF EXISTS tbl_category;
DROP TABLE IF EXISTS tbl_vendor;
DROP TABLE IF EXISTS tbl_courier;
DROP TABLE IF EXISTS tbl_customer;
DROP TABLE IF EXISTS tbl_staff;
DROP TABLE IF EXISTS tbl_login;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------------
-- Login credentials. User_Type: CU = customer, ST = staff, CR = courier, AD = admin
-- ---------------------------------------------------------------------------
CREATE TABLE tbl_login (
  Username  VARCHAR(100) NOT NULL,
  Password  VARCHAR(255) NOT NULL,
  User_Type CHAR(2)      NOT NULL,
  PRIMARY KEY (Username),
  KEY idx_login_type (User_Type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_customer (
  Cust_ID     CHAR(7)      NOT NULL,
  C_Username  VARCHAR(100) NOT NULL,
  Cust_Fname  VARCHAR(50)  NOT NULL,
  Cust_Lname  VARCHAR(50)  DEFAULT NULL,
  Cust_Phone  VARCHAR(15)  DEFAULT NULL,
  Cust_Gender VARCHAR(10)  DEFAULT NULL,
  Cust_Hname  VARCHAR(100) DEFAULT NULL,
  Cust_Street VARCHAR(100) DEFAULT NULL,
  Cust_Dist   VARCHAR(50)  DEFAULT NULL,
  State_Ut    VARCHAR(50)  DEFAULT NULL,
  Cust_Pin    VARCHAR(6)   DEFAULT NULL,
  Cust_Status TINYINT(1)   NOT NULL DEFAULT 1,
  Cust_Date   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Cust_ID),
  UNIQUE KEY uq_customer_username (C_Username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_staff (
  Staff_ID          CHAR(7)       NOT NULL,
  Staff_Username    VARCHAR(100)  NOT NULL,
  Staff_Fname       VARCHAR(50)   NOT NULL,
  Staff_Lname       VARCHAR(50)   DEFAULT NULL,
  Staff_Phone       VARCHAR(15)   DEFAULT NULL,
  Staff_Gender      VARCHAR(10)   DEFAULT NULL,
  Staff_Hname       VARCHAR(100)  DEFAULT NULL,
  Staff_Street      VARCHAR(100)  DEFAULT NULL,
  Staff_Dist        VARCHAR(50)   DEFAULT NULL,
  State_Ut          VARCHAR(50)   DEFAULT NULL,
  Staff_Pin         VARCHAR(6)    DEFAULT NULL,
  Staff_Designation VARCHAR(50)   DEFAULT NULL,
  Staff_Salary      DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Staff_Status      TINYINT(1)    NOT NULL DEFAULT 1,
  Sjoining_Date     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Staff_ID),
  UNIQUE KEY uq_staff_username (Staff_Username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_courier (
  Cour_ID            CHAR(7)      NOT NULL,
  Cour_Username      VARCHAR(100) NOT NULL,
  Staff_ID           CHAR(7)      DEFAULT NULL,
  Cour_Name          VARCHAR(50)  NOT NULL,
  Cour_Phone         VARCHAR(15)  DEFAULT NULL,
  Cour_Building_name VARCHAR(100) DEFAULT NULL,
  Cour_Street        VARCHAR(100) DEFAULT NULL,
  Cour_Dist          VARCHAR(50)  DEFAULT NULL,
  Cour_Pin           VARCHAR(6)   DEFAULT NULL,
  Cour_State_ut      VARCHAR(50)  DEFAULT NULL,
  Cour_Status        TINYINT(1)   NOT NULL DEFAULT 1,
  Cour_Joining_Date  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Cour_ID),
  UNIQUE KEY uq_courier_username (Cour_Username),
  KEY idx_courier_staff (Staff_ID),
  KEY idx_courier_status (Cour_Status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_vendor (
  Vendor_ID       CHAR(7)      NOT NULL,
  Vendor_Email    VARCHAR(100) NOT NULL,
  Vendor_Username VARCHAR(100) DEFAULT NULL,
  Staff_Id        CHAR(7)      DEFAULT NULL,
  Vendor_Name     VARCHAR(50)  NOT NULL,
  Vendor_Phno     VARCHAR(15)  DEFAULT NULL,
  Vendor_Hname    VARCHAR(100) DEFAULT NULL,
  Vendor_Street   VARCHAR(100) DEFAULT NULL,
  Vendor_Dist     VARCHAR(50)  DEFAULT NULL,
  State_Ut        VARCHAR(50)  DEFAULT NULL,
  Vendor_Pin      VARCHAR(6)   DEFAULT NULL,
  Vendor_Status   TINYINT(1)   NOT NULL DEFAULT 1,
  Vjoining_Date   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Vendor_ID),
  UNIQUE KEY uq_vendor_email (Vendor_Email),
  KEY idx_vendor_staff (Staff_Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_category (
  Cat_ID   CHAR(7)     NOT NULL,
  Cat_Name VARCHAR(50) NOT NULL,
  PRIMARY KEY (Cat_ID),
  UNIQUE KEY uq_category_name (Cat_Name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tbl_type (
  Type_ID   CHAR(7)     NOT NULL,
  Cat_ID    CHAR(7)     NOT NULL,
  Type_Name VARCHAR(50) NOT NULL,
  PRIMARY KEY (Type_ID),
  KEY idx_type_category (Cat_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tbl_brand (
  Brand_ID   CHAR(7)      NOT NULL,
  Brand_Name VARCHAR(50)  NOT NULL,
  Brand_Logo VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (Brand_ID),
  UNIQUE KEY uq_brand_name (Brand_Name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_appliance (
  Appliance_ID                CHAR(7)       NOT NULL,
  Appliance_Name              VARCHAR(100)  NOT NULL,
  Type_ID                     CHAR(7)       NOT NULL,
  Brand_ID                    CHAR(7)       NOT NULL,
  Appliance_Description       TEXT          DEFAULT NULL,
  Appliance_Image1            VARCHAR(255)  DEFAULT NULL,
  Appliance_Image2            VARCHAR(255)  DEFAULT NULL,
  Appliance_Profit_Percentage DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
  Stock                       INT           NOT NULL DEFAULT 0,
  PRIMARY KEY (Appliance_ID),
  KEY idx_appliance_type (Type_ID),
  KEY idx_appliance_brand (Brand_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_purchase_master (
  Purchase_Master_ID CHAR(7)       NOT NULL,
  Vendor_ID          CHAR(7)       NOT NULL,
  Staff_ID           CHAR(7)       NOT NULL,
  Total_Amt          DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Purchase_Date      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (Purchase_Master_ID),
  KEY idx_purchase_vendor (Vendor_ID),
  KEY idx_purchase_staff_date (Staff_ID, Purchase_Date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tbl_purchase_child (
  Purchase_Child_ID  CHAR(7)       NOT NULL,
  Purchase_Master_ID CHAR(7)       NOT NULL,
  Appliance_ID       CHAR(7)       NOT NULL,
  Quantity           INT           NOT NULL DEFAULT 0,
  Balance_Stock      INT           NOT NULL DEFAULT 0,
  Cost_Per_Piece     DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Selling_Price      DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Total_Cost_Price   DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (Purchase_Child_ID),
  KEY idx_pc_master (Purchase_Master_ID),
  KEY idx_pc_appliance_stock (Appliance_ID, Balance_Stock)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_cart_master (
  CM_ID        CHAR(7)       NOT NULL,
  Customer_ID  CHAR(7)       NOT NULL,
  Total_Amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Cart_Status  VARCHAR(30)   NOT NULL DEFAULT 'ASSIGNED',
  PRIMARY KEY (CM_ID),
  KEY idx_cart_customer_status (Customer_ID, Cart_Status),
  KEY idx_cart_status (Cart_Status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tbl_cart_child (
  CC_ID        CHAR(7)       NOT NULL,
  CM_ID        CHAR(7)       NOT NULL,
  Appliance_ID CHAR(7)       NOT NULL,
  Quantity     INT           NOT NULL DEFAULT 1,
  Price        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (CC_ID),
  KEY idx_cc_master (CM_ID),
  KEY idx_cc_appliance (Appliance_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_payment (
  Payment_ID                  CHAR(7)     NOT NULL,
  CM_ID                       CHAR(7)     NOT NULL,
  Card_ID                     CHAR(7)     NOT NULL,
  Payment_Date                DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Courier_Assignment_Status   TINYINT(1)  NOT NULL DEFAULT 0,
  PRIMARY KEY (Payment_ID),
  KEY idx_payment_cart (CM_ID),
  KEY idx_payment_assign (Courier_Assignment_Status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- NOTE: CVV is intentionally NOT stored. See payment.php.
CREATE TABLE tbl_card (
  Card_ID          CHAR(7)      NOT NULL,
  Customer_ID      CHAR(7)      NOT NULL,
  Card_No          VARCHAR(20)  NOT NULL,
  Card_Holder_Name VARCHAR(100) NOT NULL,
  Bank_Name        VARCHAR(100) NOT NULL,
  Card_Type        CHAR(1)      NOT NULL DEFAULT 'C',
  Expiry_Date      DATE         NOT NULL,
  Card_Status      TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (Card_ID),
  UNIQUE KEY uq_card_number (Card_No),
  KEY idx_card_customer (Customer_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
CREATE TABLE tbl_courier_assign (
  Courier_Assign_ID     CHAR(7)     NOT NULL,
  Courier_ID            CHAR(7)     NOT NULL,
  CM_ID                 CHAR(7)     NOT NULL,
  Customer_ID           CHAR(7)     NOT NULL,
  Courier_Assign_Date   DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Max_Delivery_Date     DATETIME    NOT NULL,
  Delivery_Status       VARCHAR(20) NOT NULL DEFAULT 'ASSIGNED',
  Accept_CA_Status      TINYINT(1)  NOT NULL DEFAULT 0,
  Delivery_Error_Status INT         NOT NULL DEFAULT 0,
  PRIMARY KEY (Courier_Assign_ID),
  KEY idx_ca_courier_status (Courier_ID, Delivery_Status),
  KEY idx_ca_cart (CM_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tbl_delivery (
  Delivery_ID       CHAR(7)      NOT NULL,
  Courier_Assign_ID CHAR(7)      NOT NULL,
  CM_ID             CHAR(7)      NOT NULL,
  Delivery_Date     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  Delivery_Details  VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (Delivery_ID),
  KEY idx_delivery_assign (Courier_Assign_ID),
  KEY idx_delivery_cart (CM_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- ID generators used by the PHP code
-- ============================================================================
DROP FUNCTION IF EXISTS generate_cust_id;
DROP FUNCTION IF EXISTS generate_staff_id;
DROP FUNCTION IF EXISTS generate_cour_id;
DROP FUNCTION IF EXISTS generate_vendor_id;
DROP FUNCTION IF EXISTS generate_cat_id;
DROP FUNCTION IF EXISTS generate_type_id;
DROP FUNCTION IF EXISTS generate_brand_id;
DROP FUNCTION IF EXISTS generate_appliance_id;
DROP FUNCTION IF EXISTS generate_purchase_master_id;
DROP FUNCTION IF EXISTS generate_purchase_child_id;
DROP FUNCTION IF EXISTS generate_cart_master_id;
DROP FUNCTION IF EXISTS generate_cart_child_id;
DROP FUNCTION IF EXISTS generate_card_id;
DROP FUNCTION IF EXISTS generate_payment_id;
DROP FUNCTION IF EXISTS generate_courier_assignment_id;
DROP FUNCTION IF EXISTS generate_delivery_id;

DELIMITER $$

CREATE FUNCTION generate_cust_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Cust_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_customer;
  RETURN CONCAT('CU', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_staff_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Staff_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_staff;
  RETURN CONCAT('ST', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_cour_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Cour_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_courier;
  RETURN CONCAT('CR', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_vendor_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Vendor_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_vendor;
  RETURN CONCAT('VN', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_cat_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Cat_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_category;
  RETURN CONCAT('CA', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_type_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Type_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_type;
  RETURN CONCAT('TY', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_brand_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Brand_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_brand;
  RETURN CONCAT('BR', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_appliance_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Appliance_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_appliance;
  RETURN CONCAT('AP', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_purchase_master_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Purchase_Master_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_purchase_master;
  RETURN CONCAT('PM', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_purchase_child_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Purchase_Child_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_purchase_child;
  RETURN CONCAT('PC', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_cart_master_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(CM_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_cart_master;
  RETURN CONCAT('CM', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_cart_child_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(CC_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_cart_child;
  RETURN CONCAT('CC', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_card_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Card_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_card;
  RETURN CONCAT('CD', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_payment_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Payment_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_payment;
  RETURN CONCAT('PY', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_courier_assignment_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Courier_Assign_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_courier_assign;
  RETURN CONCAT('CS', LPAD(n, 5, '0'));
END$$

CREATE FUNCTION generate_delivery_id() RETURNS CHAR(7)
READS SQL DATA
BEGIN
  DECLARE n INT;
  SELECT COALESCE(MAX(CAST(SUBSTRING(Delivery_ID, 3) AS UNSIGNED)), 0) + 1 INTO n FROM tbl_delivery;
  RETURN CONCAT('DL', LPAD(n, 5, '0'));
END$$

DELIMITER ;
