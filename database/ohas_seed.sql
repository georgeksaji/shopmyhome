-- ============================================================================
-- shopmyhome / OHAS - demo seed data
--
-- Run AFTER database/ohas_schema.sql:
--   C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 < database\ohas_seed.sql
--
-- Demo accounts (all passwords are bcrypt-hashed):
--   Admin    : admin@shopmyhome.com    / Admin@123    (Staff_ID ST00001)
--   Staff    : staff@shopmyhome.com    / Staff@123
--   Courier  : courier@shopmyhome.com  / Courier@123
--   Customer : customer@shopmyhome.com / Customer@123
-- ============================================================================

USE ohas;

DELETE FROM tbl_delivery;
DELETE FROM tbl_courier_assign;
DELETE FROM tbl_card;
DELETE FROM tbl_payment;
DELETE FROM tbl_cart_child;
DELETE FROM tbl_cart_master;
DELETE FROM tbl_purchase_child;
DELETE FROM tbl_purchase_master;
DELETE FROM tbl_appliance;
DELETE FROM tbl_brand;
DELETE FROM tbl_type;
DELETE FROM tbl_category;
DELETE FROM tbl_vendor;
DELETE FROM tbl_courier;
DELETE FROM tbl_customer;
DELETE FROM tbl_staff;
DELETE FROM tbl_login;

-- ---------------------------------------------------------------------------
-- Login accounts
-- ---------------------------------------------------------------------------
INSERT INTO tbl_login (Username, Password, User_Type) VALUES
('admin@shopmyhome.com',    '$2y$10$8Vt5Qo.LOtCyW3iDB.jGWeLGG7gHu6QIv1nFjCyWOlwmDQ1lwvefm', 'AD'),
('staff@shopmyhome.com',    '$2y$10$PvspObxzi/FwwofzqjmMduEWIpZsO00JYxnk/PKPCuYaUzl9JFUk.', 'ST'),
('courier@shopmyhome.com',  '$2y$10$TGBnOKbDgm8xxrn1/gk3nuigW3OSC/THrYdbnGu7Mu7Hkx6vlfYAe', 'CR'),
('customer@shopmyhome.com', '$2y$10$IO6qKj0q2fUv3PR36bI8HeqXH3bzCDuDXHk8Ky/8CJeH57Dl.I1UG', 'CU');

-- ---------------------------------------------------------------------------
-- People
-- ---------------------------------------------------------------------------
INSERT INTO tbl_staff (Staff_ID, Staff_Username, Staff_Fname, Staff_Lname, Staff_Phone, Staff_Gender,
                       Staff_Hname, Staff_Street, Staff_Dist, State_Ut, Staff_Pin, Staff_Designation,
                       Staff_Salary, Staff_Status, Sjoining_Date) VALUES
('ST00001', 'admin@shopmyhome.com', 'Admin', 'User', '9876543210', 'Male',
 'Shopmyhome HQ', 'MG Road', 'Ernakulam', 'Kerala', '682001', 'Administrator', 60000.00, 1, NOW()),
('ST00002', 'staff@shopmyhome.com', 'Staff', 'Member', '9876543211', 'Female',
 'Shopmyhome HQ', 'MG Road', 'Ernakulam', 'Kerala', '682001', 'Sales Executive', 35000.00, 1, NOW());

INSERT INTO tbl_courier (Cour_ID, Cour_Username, Staff_ID, Cour_Name, Cour_Phone, Cour_Building_name,
                         Cour_Street, Cour_Dist, Cour_Pin, Cour_State_ut, Cour_Status, Cour_Joining_Date) VALUES
('CR00001', 'courier@shopmyhome.com', 'ST00001', 'Arjun Courier', '9876543212',
 'Courier Hub', 'Station Road', 'Ernakulam', '682001', 'Kerala', 1, NOW());

INSERT INTO tbl_vendor (Vendor_ID, Vendor_Email, Vendor_Username, Staff_Id, Vendor_Name, Vendor_Phno,
                        Vendor_Hname, Vendor_Street, Vendor_Dist, State_Ut, Vendor_Pin, Vendor_Status,
                        Vjoining_Date) VALUES
('VN00001', 'vendor@shopmyhome.com', 'vendor@shopmyhome.com', 'ST00001', 'ABC Distributors', '9876543213',
 'ABC Warehouse', 'Industrial Estate', 'Ernakulam', 'Kerala', '682001', 1, NOW());

INSERT INTO tbl_customer (Cust_ID, C_Username, Cust_Fname, Cust_Lname, Cust_Phone, Cust_Gender, Cust_Hname,
                          Cust_Street, Cust_Dist, State_Ut, Cust_Pin, Cust_Status, Cust_Date) VALUES
('CU00001', 'customer@shopmyhome.com', 'Demo', 'Customer', '9876543214', 'Male',
 'Demo House', 'MG Road', 'Ernakulam', 'Kerala', '682001', 1, NOW());

-- ---------------------------------------------------------------------------
-- Catalogue
-- ---------------------------------------------------------------------------
INSERT INTO tbl_category (Cat_ID, Cat_Name) VALUES
('CA00001', 'Refrigerator'),
('CA00002', 'Washing Machine'),
('CA00003', 'Air Conditioner'),
('CA00004', 'Television'),
('CA00005', 'Microwave Oven'),
('CA00006', 'Kitchen Appliances'),
('CA00007', 'Home Appliances');

INSERT INTO tbl_type (Type_ID, Cat_ID, Type_Name) VALUES
('TY00001', 'CA00001', 'Single Door Refrigerator'),
('TY00002', 'CA00001', 'Double Door Refrigerator'),
('TY00003', 'CA00002', 'Front Load Washing Machine'),
('TY00004', 'CA00002', 'Top Load Washing Machine'),
('TY00005', 'CA00003', 'Split Air Conditioner'),
('TY00006', 'CA00003', 'Window Air Conditioner'),
('TY00007', 'CA00004', 'LED Television'),
('TY00008', 'CA00004', 'OLED Television'),
('TY00009', 'CA00005', 'Convection Microwave Oven'),
('TY00010', 'CA00005', 'Solo Microwave Oven'),
('TY00011', 'CA00006', 'Water Purifier'),
('TY00012', 'CA00006', 'Induction Cooktop'),
('TY00013', 'CA00007', 'Air Purifier');

INSERT INTO tbl_brand (Brand_ID, Brand_Name, Brand_Logo) VALUES
('BR00001', 'Samsung',   'assets/uploads/brand_images/samsung.png'),
('BR00002', 'LG',        'assets/uploads/brand_images/lg.png'),
('BR00003', 'Whirlpool', 'assets/uploads/brand_images/whirlpool.png'),
('BR00004', 'Bosch',     'assets/uploads/brand_images/bosch.png'),
('BR00005', 'IFB',       'assets/uploads/brand_images/ifb.jpg'),
('BR00006', 'Voltas',    'assets/uploads/brand_images/voltas.jpg'),
('BR00007', 'Daikin',    'assets/uploads/brand_images/daikin.png'),
('BR00008', 'Sony',      'assets/uploads/brand_images/sony.png'),
('BR00009', 'Honeywell', 'assets/uploads/brand_images/honeywell.png'),
('BR00010', 'Kent',      'assets/uploads/brand_images/kent.png'),
('BR00011', 'Prestige',  'assets/uploads/brand_images/prestige.png');

INSERT INTO tbl_appliance (Appliance_ID, Appliance_Name, Type_ID, Brand_ID, Appliance_Description,
                           Appliance_Image1, Appliance_Image2, Appliance_Profit_Percentage, Stock) VALUES
('AP00001', 'LG 190 L 4 Star Single Door Refrigerator', 'TY00001', 'BR00002',
 'Direct-cool single door refrigerator with smart inverter compressor, 4 star energy rating and 190 litre capacity. Ideal for small families.',
 'assets/uploads/product_images/51zWd3VfKeL._SX522_.jpg', 'assets/uploads/product_images/513k9oIRdbL._SX522_.jpg', 15.00, 20),
('AP00002', 'Whirlpool 500 L Double Door Refrigerator', 'TY00002', 'BR00003',
 'Frost-free double door refrigerator with 500 litre capacity, IntelliSense inverter and Zeolite freshness technology.',
 'assets/uploads/product_images/whirlpool_refri1.jpg', 'assets/uploads/product_images/whirlpool_refri1.2.jpg', 18.00, 20),
('AP00003', 'Samsung 32 L Convection Microwave Oven', 'TY00009', 'BR00001',
 '32 litre convection microwave with ceramic enamel cavity, slim fry and auto cook menus. Includes grill and combi modes.',
 'assets/uploads/product_images/32 L Convection Microwave Oven1.webp', 'assets/uploads/product_images/32 L Convection Microwave Oven2.webp', 20.00, 20),
('AP00004', 'IFB 7 kg 5 Star Front Load Washing Machine', 'TY00003', 'BR00005',
 'Fully automatic front load washing machine with Aqua Energie, 3D wash system and 7 kg drum capacity.',
 'assets/uploads/product_images/41Qhm1NWI2L.jpg', 'assets/uploads/product_images/51Pw9Vd50IL._SY741_.jpg', 15.00, 20),
('AP00005', 'Bosch 8 kg Front Load Washing Machine', 'TY00003', 'BR00004',
 'Series 6 front load washing machine with EcoSilence drive, anti-tangle and 8 kg capacity.',
 'assets/uploads/product_images/51ogk3WWjWL._SY879_.jpg', 'assets/uploads/product_images/51sqULUneKL._SL1500_.jpg', 16.00, 20),
('AP00006', 'Voltas 1.5 Ton 3 Star Split Air Conditioner', 'TY00005', 'BR00006',
 'Adjustable inverter split AC with 1.5 ton cooling, turbo mode and anti-dust filter.',
 'assets/uploads/product_images/61axzJbR0KL._SX679_.jpg', 'assets/uploads/product_images/61EGAS3x77L._SX679_.jpg', 22.00, 20),
('AP00007', 'Daikin 1.5 Ton 5 Star Inverter Split AC', 'TY00005', 'BR00007',
 '5 star inverter split air conditioner with Coanda airflow, PM 2.5 filter and 1.5 ton capacity.',
 'assets/uploads/product_images/61gaskR5anL._SX679_.jpg', 'assets/uploads/product_images/61ZzcguzB1L._SL1500_.jpg', 20.00, 20),
('AP00008', 'Sony Bravia 55 inch 4K Ultra HD LED TV', 'TY00007', 'BR00008',
 '55 inch 4K HDR smart television with Google TV, Dolby Vision and X-Reality PRO picture engine.',
 'assets/uploads/product_images/71Etg8yYLeL._SX679_.jpg', 'assets/uploads/product_images/71h7fLNEQPL._SX522_.jpg', 18.00, 20),
('AP00009', 'Samsung 43 inch Crystal 4K Smart LED TV', 'TY00007', 'BR00001',
 '43 inch Crystal 4K smart TV with PurColour, Q-Symphony and built-in voice assistants.',
 'assets/uploads/product_images/71mfkY78bUL._SL1500_.jpg', 'assets/uploads/product_images/71msFUl565L._SX679_.jpg', 20.00, 19),
('AP00010', 'Honeywell Air Purifier for Large Rooms', 'TY00013', 'BR00009',
 'HEPA air purifier with 3-stage filtration, auto mode and coverage up to 1000 sq ft.',
 'assets/uploads/product_images/71uuK212OlL._SX522_.jpg', 'assets/uploads/product_images/71YVhrFnCJL._SX679_.jpg', 25.00, 20),
('AP00011', 'Kent Grand Plus RO Water Purifier', 'TY00011', 'BR00010',
 'RO + UV + UF water purifier with 8 litre storage, TDS control and zero water wastage.',
 'assets/uploads/product_images/81dZ9jEnzSL._SX522_.jpg', 'assets/uploads/product_images/81kHlStmheL._SX522_.jpg', 20.00, 20),
('AP00012', 'Prestige Induction Cooktop 2000 W', 'TY00012', 'BR00011',
 '2000 watt induction cooktop with preset menus, anti-magnetic wall and automatic voltage regulator.',
 'assets/uploads/product_images/81nnTR6hfEL._SX679_.jpg', 'assets/uploads/product_images/81OnFolEvpL._SL1500_.jpg', 30.00, 20);

-- ---------------------------------------------------------------------------
-- Purchase (stock + cost/selling prices used by the storefront)
-- ---------------------------------------------------------------------------
INSERT INTO tbl_purchase_master (Purchase_Master_ID, Vendor_ID, Staff_ID, Total_Amt, Purchase_Date) VALUES
('PM00001', 'VN00001', 'ST00001', 6290000.00, NOW());

INSERT INTO tbl_purchase_child (Purchase_Child_ID, Purchase_Master_ID, Appliance_ID, Quantity, Balance_Stock,
                                Cost_Per_Piece, Selling_Price, Total_Cost_Price) VALUES
('PC00001', 'PM00001', 'AP00001', 20, 20, 18000.00, 20700.00, 360000.00),
('PC00002', 'PM00001', 'AP00002', 20, 20, 32000.00, 37760.00, 640000.00),
('PC00003', 'PM00001', 'AP00003', 20, 20, 12000.00, 14400.00, 240000.00),
('PC00004', 'PM00001', 'AP00004', 20, 20, 26000.00, 29900.00, 520000.00),
('PC00005', 'PM00001', 'AP00005', 20, 20, 34000.00, 39440.00, 680000.00),
('PC00006', 'PM00001', 'AP00006', 20, 20, 33000.00, 40260.00, 660000.00),
('PC00007', 'PM00001', 'AP00007', 20, 20, 45000.00, 54000.00, 900000.00),
('PC00008', 'PM00001', 'AP00008', 20, 20, 55000.00, 64900.00, 1100000.00),
('PC00009', 'PM00001', 'AP00009', 20, 19, 30000.00, 36000.00, 600000.00),
('PC00010', 'PM00001', 'AP00010', 20, 20, 15000.00, 18750.00, 300000.00),
('PC00011', 'PM00001', 'AP00011', 20, 20, 12000.00, 14400.00, 240000.00),
('PC00012', 'PM00001', 'AP00012', 20, 20, 2500.00, 3250.00, 50000.00);

-- ---------------------------------------------------------------------------
-- Demo payment card + one paid order awaiting courier assignment
-- ---------------------------------------------------------------------------
INSERT INTO tbl_card (Card_ID, Customer_ID, Card_No, Card_Holder_Name, Bank_Name, Card_Type,
                      Expiry_Date, Card_Status) VALUES
('CD00001', 'CU00001', '4111111111111111', 'Demo Customer', 'HDFC Bank', 'C', '2030-12-31', 1);

INSERT INTO tbl_cart_master (CM_ID, Customer_ID, Total_Amount, Cart_Status) VALUES
('CM00001', 'CU00001', 36000.00, 'PAID');

INSERT INTO tbl_cart_child (CC_ID, CM_ID, Appliance_ID, Quantity, Price) VALUES
('CC00001', 'CM00001', 'AP00009', 1, 36000.00);

INSERT INTO tbl_payment (Payment_ID, CM_ID, Card_ID, Payment_Date, Courier_Assignment_Status) VALUES
('PY00001', 'CM00001', 'CD00001', NOW(), 0);
