<?php
include ("connection.php");
$search = null;
$brand_id = null;
$type_id=null;
$category_id = null;
session_start();
if(isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
}
else {
  $userId = null;
  $usertype = null;
}

  if($_SESSION['search'] != null)
  {
    $search = $_SESSION['search'];
  }
  if($_SESSION['brand_id'] != null)
  {
    $brand_id = $_SESSION['brand_id'];
  }
  if($_SESSION['category_id'] != null)
  {
    $category_id = $_SESSION['category_id'];
  }
  if($_SESSION['type_id'] != null)
  {
    $type_id = $_SESSION['type_id'];
  }

//search appliance
if(isset($_POST['submit']))
{
  $search = $_POST['search'];
  $_SESSION['search'] = null;
  $_SESSION['search'] = $search;
  $_SESSION['brand_id'] = null;
  $_SESSION['category_id'] = null;
  header("Location: list_products.php"); 
}
//login button
if(isset($_POST['login']))
{
  header("Location: Login.php");
}
//signup button
if(isset($_POST['signup']))
{
  header("Location: Customer_Sign_Up.php");
}
//admin button
if(isset($_POST['admin']))
{
  header("Location: admin.php");
}
//logout button
if(isset($_POST['logout']))
{
  session_destroy();
  header("Location: index.php");
}

//cart button
if(isset($_POST['cart']))
{
  echo 'cart';
  header("Location: cart.php");
}
//search appliance
/*if(isset($_POST['submit']))
{
  $search = $_POST['search'];
  $_SESSION['search'] = $search;
  unset($_SESSION['brand_id']);
  unset($_SESSION['category_id']);
  header("Location: list_products.php"); 
}*/
//category button
if(isset($_POST['category']))
{
  $category_id = $_POST['category'];
  $_SESSION['category_id'] = $category_id;
  $_SESSION['brand_id']=null;
  $_SESSION['search']=null;
  $_SESSION['type_id']=null;
  header("Location: list_types.php");
}
//select product button
if(isset($_POST['select_product']))
{
  $product_detail_id = $_POST['select_product'];
  $_SESSION['product_detail_id'] = $product_detail_id;
  header("Location: product_details.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   
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
  overflow-x: hidden;

}
.home-outer {
  width: 100%;
  height: 100vh;
}

.top-navigation {
  width: 100%;
  height:10vh;
  background-color: rgba(101, 194, 240, 0.4);
  background-origin: content-box;
  display: -webkit-box;
  align-items: center;
}

.navigation-logo {
  background-image: url(Picture3.png);
    background-size: cover;
    margin-inline-start: 2vh;
    height: 100%;
    width: 33vh;
}

.navbar{
  width: 49%;
}
.container-fluid,
.d-flex
{
  width: 100%;

}

.form-control{
  width: 50%;
  padding-top: 1%;
  padding-bottom: 1%;
  background-color: rgb(6, 28, 100,0.1);
  font-weight: 350;
  font-family:calibri;
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

.btn
{
border-color:rgb(6, 28, 100);
color: rgb(6, 28, 100);
transition: 0.4s;
}

.btn:hover
{
background-color:rgb(6, 28, 100);
}

.navigation-item {
  
    height: 100%;
    width: 35%;
    padding-right: 2%;
}
.action-table {
  width: 100%;
  height: 100%;
  border-collapse: separate;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}
.action table td{
  display: inline-block;
}
.login-box,.signup-box,.admin-box,.logout-box,.profile-box{
  border-style: none;
  background-color: rgba(255, 255, 255, 0.1);
  transition: 0.3s;
  font-size: small;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 550;
  color:rgb(0, 0, 0);
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 91px;
  height: 40px;
}
.login-box:hover,.signup-box:hover,.admin-box:hover{
  background-color: rgb(0, 0, 0);
  color: white;
}
.logout-box:hover{
  background-color: rgb(239,51,36);
  color: white;
}
.profile-box:hover{
  transition: 0.5s;
  transform: scale(1.2);
}
.action table .btn-primary,.action table .btn
{
  border-style: none;
  background-color: rgb(6, 28, 100);
}
.action table .btn:hover {
    background-color: rgb(256,256,256);
}


.action-table td
{
  width:auto;
  white-space: nowrap; 
  overflow: hidden;  
  text-overflow: ellipsis;
}

a {
  text-decoration: none;
}

.category-navigation {
  display: flex;
  background-color:rgb(120, 181, 225);
  justify-content: center;
  align-items: center;
}

.category-name
{
  font-size: small;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color:rgb(120, 181, 225);
  font-weight: 550;
  border-style:none;
  width: fit-content;
  padding-left: 10px;
  padding-right: 10px;
  height: 45px;
  display: flex;
  color: rgb(256,256,256);
  justify-content: center;
  align-items: center;
  transition: 0.5s;
}

.category-name:hover{
  background-color:rgba(255, 255, 255, 0.166);
  color: rgb(0,0,0);
  border-bottom-width:3px;
  border-left-width: 0px;
  border-right-width: 0px;
  border-top-width: 0px;
  border-style:solid;
  border-color:rgb(255, 255, 255);
  padding-left: 13px;
  padding-right: 13px;
}

.carousel-item
{
  transition: 0.6s;
}

.carousel-control-next-icon:hover,
.carousel-control-prev-icon:hover
{
  transition: 0.5s;
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 100%;

}
.products_list
{
    width: 100%;
    /* height: initial; */
    display: table-caption;
    /* justify-content: center; */
    padding-bottom: 1.3%;
    display: flex;
justify-content: flex-start;
        flex-wrap: wrap;
    /* padding: 20px; */
    height: auto;
}
.product_card_button
{
  
  border-style: none;
  background-color:transparent;
    width: 17.35%;
    height: 42vh;
    display: flex;
    margin-top: 1.3%;
    margin-inline: 1.3%;
    padding: 0%;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    
}
.product_card_outer
{
  width: 100%;
    height: 100%;
    display: flex;
    margin:0%;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    background-color: white;
}
.product_card_outer:hover
{
  transition: 0.2s;
  transform: scale(1.01);
  box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
}
.product_image
{
  width: 100%;
    height: 70%;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center center;
}
.product_name
{
  width: 100%;
    height: 18%;
    padding-top: 6%;
    font-size: 96%;
    overflow: hidden;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 550;
    color: rgb(57 162 211);
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.product_price
{
  width: 100%;
    height: 7%;
    font-size: 97%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 550;
    color: #109b5a;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}
</style>
    
  </head>
  <body>
 <div class="home-outer">
 
  <div class="top-navigation">
        <a href="index.php"><div class="navigation-logo"></div></a>
        <!-- <div class="search-bar"><input type="search" placeholder="Search for home appliances"></div> -->
        <nav class="navbar bg-body-tertiary">
          <div class="container-fluid">
            <form class="d-flex" role="search" method="POST">
              <input class="form-control me-2" type="search" placeholder="Search  for appliances" aria-label="Search" name="search">
              <button class="btn btn-outline-success" type="submit" name="submit">Search</button>
            </form>
          </div>
        </nav>
            <div class="navigation-item">
            <table class="action-table">
              <?php
              // if userid and usertype null, display login and signup
              if($userId == null && $usertype == null) {
                echo '<form action="" method="POST">';
                echo '<tr><td><button class="login-box" name="login">Login</button></td>';
                echo '<td><button class="signup-box" name="signup">Sign Up</button></td>';
                echo '</form>';
                }
                //if userid =ST00001 AND usertype=AD then a=admin.php
                if($userId == 'ST00001' && $usertype == 'AD') {
                  //admin panel page , logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><button class="admin-box" name="admin">Admin Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</form>';
                }
                //if userid!=ST00001 AND usertype=ST
                if($userId != 'ST00001' && $usertype == 'ST') {
                  //profile page,Staff Dashboard, logout
                  echo '<form action="" method="POST">';
                  //profile page
                  echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
                  echo '<td><button class="admin-box" name="admin">Staff Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</tr>';
                  echo '</form>';//    
                }
                //if usertype=CU
                if($usertype == 'CU') {
                  //profile page, logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><a href="profile.php"><button class="profile-box" name="login"><img src="profile1.png" height="30px" width="30px"></button></a></td>';
                  //cart button
                  $sql = "SELECT * FROM tbl_cart_master WHERE Customer_ID = '$userId' AND Cart_Status = 'AS'";
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
  <div class="category-navigation">
    <!--<button class="category-name">TV & Audio</button>-->
   <form action="" method="POST">
    <?php
    //category names
    $sql = "SELECT * FROM tbl_category";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result))
    {
      $categoryname = $row['Cat_Name'];
      $categoryid = $row['Cat_ID'];
      echo "<button class='category-name' name='category' value='$categoryid' style='display: inline-block;'>$categoryname</button>";
    }
    ?>
    </form>
    <form action="" method='POST'>
  </div>
  <div class="products_list">
  <!--<div class="product_card_outer"><div class="product_image" style="background-image: url('./product_images/41Qhm1NWI2L.jpg');"></div>
<div class="product_name">text</div>
<div class="product_price">price</div>-->
<?php
if($search != null)
{
    //$search = $_SESSION['search'];
    //search from appliance name,appliance description
    //$sql2="SELECT * FROM tbl_appliance WHERE Appliance_Name LIKE '%$search%' UNION SELECT * FROM tbl_appliance WHERE Appliance_Description LIKE '%$search%'";

    
    $sql2="SELECT * FROM tbl_appliance WHERE Appliance_ID IN (SELECT Appliance_ID FROM tbl_appliance WHERE Appliance_Name LIKE '%$search%' UNION SELECT Appliance_ID FROM tbl_appliance WHERE Brand_ID IN (SELECT Brand_ID FROM tbl_brand WHERE Brand_Name LIKE '%$search%') UNION SELECT Appliance_ID FROM tbl_appliance WHERE Type_ID IN (SELECT Type_ID FROM tbl_type WHERE Type_Name LIKE '%$search%' UNION SELECT Type_ID FROM tbl_type WHERE Cat_ID IN (SELECT Cat_ID FROM tbl_category WHERE Cat_Name LIKE '%$search%')))";
    $result2 = mysqli_query($conn,$sql2);
    while($row2 = mysqli_fetch_assoc($result2))
    {
      //fetching brand name
      $brand_id = $row2['Brand_ID'];
      $sql_brandname = "SELECT Brand_Name FROM tbl_brand WHERE Brand_ID = '$brand_id'";
      $result_brandname = mysqli_query($conn,$sql_brandname);
      $row_brandname = mysqli_fetch_assoc($result_brandname);
      $brandname = $row_brandname['Brand_Name'];
      $productid = $row2['Appliance_ID'];
      $productname = $row2['Appliance_Name'];
      $productprice = $row2['Appliance_Profit_Percentage'];
      $productimage = $row2['Appliance_Image1'];
      echo "<button class='product_card_button' name='select_product' value='" . $productid . "'><div class='product_card_outer'><div class='product_image' style='background-image: url($productimage);'></div>";
      echo "<div class='product_name'>$brandname $productname</div>";
      $sql_profit_percent="SELECT * FROM tbl_appliance WHERE Appliance_ID = '$productid'";
      $result_profit_percent = mysqli_query($conn,$sql_profit_percent);
      $row_profit_percent = mysqli_fetch_assoc($result_profit_percent);
      $profit_percent = $row_profit_percent['Appliance_Profit_Percentage'];
      $sql_cost_price="SELECT * FROM tbl_purchase_child WHERE Appliance_ID = '$productid'";
      $result_cost_price = mysqli_query($conn,$sql_cost_price);
      $row_cost_price = mysqli_fetch_assoc($result_cost_price);
      if($row_cost_price == null)
      {
        $cost_price = null;
        $price = null;
      }
      else
      {
        $cost_price = $row_cost_price['Cost_Per_Piece'];
        $cost_price = $row_cost_price['Cost_Per_Piece'];
      $price = $cost_price + ($cost_price * $profit_percent)/100;
      }
      
      if($price==null)
      {
        echo "<div class='product_price'>OUT OF STOCK</div>";
      }
      else if($price!==null)
      {
        echo "<div class='product_price'>₹$price</div>";
      }
      echo "</div></button>";
      $brand_id=null;
      $type_id=null;
    }
    if(mysqli_num_rows($result2) == 0)
    {
      echo "<div style='width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; font-size: 2em; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 550; color: rgb(57 162 211);'>No results found</div>";
    }
  }
  if($brand_id != null)
  {
    $sql3="SELECT * FROM tbl_appliance WHERE Brand_ID = '$brand_id'";
    $result3 = mysqli_query($conn,$sql3);
    while($row3 = mysqli_fetch_assoc($result3))
    {
      //fetching brand name
      $brand_id = $row3['Brand_ID'];
      $sql_brandname = "SELECT Brand_Name FROM tbl_brand WHERE Brand_ID = '$brand_id'";
      $result_brandname = mysqli_query($conn,$sql_brandname);
      $row_brandname = mysqli_fetch_assoc($result_brandname);
      $brandname = $row_brandname['Brand_Name'];

      $productid = $row3['Appliance_ID'];
      $productname = $row3['Appliance_Name'];
      $productprice = $row3['Appliance_Profit_Percentage'];
      $productimage = $row3['Appliance_Image1'];
      echo "<button class='product_card_button' name='select_product' value='" . $productid . "'><div class='product_card_outer'><div class='product_image' style='background-image: url($productimage);'></div>";
      echo "<div class='product_name'>$brandname $productname</div>";
      echo "<div class='product_price'>₹br</div>";
      echo "</div></button>";
    }
    if(mysqli_num_rows($result3) == 0)
    {
      echo "<div style='width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; font-size: 2em; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 550; color: rgb(57 162 211);'>No results found</div>";
    }
  }
  //type id list all products of type id
  if($type_id != null)
  {
    $sql4="SELECT * FROM tbl_appliance WHERE Type_ID = '$type_id'";
    $result4 = mysqli_query($conn,$sql4);
    while($row4 = mysqli_fetch_assoc($result4))
    {
      //fetching brand name
      $brand_id = $row4['Brand_ID'];
      $sql_brandname = "SELECT Brand_Name FROM tbl_brand WHERE Brand_ID = '$brand_id'";
      $result_brandname = mysqli_query($conn,$sql_brandname);
      $row_brandname = mysqli_fetch_assoc($result_brandname);
      $brandname = $row_brandname['Brand_Name'];

      $productid = $row4['Appliance_ID'];
      $productname = $row4['Appliance_Name'];
      $productprice = $row4['Appliance_Profit_Percentage'];
      $productimage = $row4['Appliance_Image1'];
      echo "<button class='product_card_button' name='select_product' value='" . $productid . "'><div class='product_card_outer'><div class='product_image' style='background-image: url($productimage);'></div>";
      echo "<div class='product_name'>$brandname $productname</div>";
      echo "<div class='product_price'>₹typ</div>";
      echo "</div></button>";
    }
    if(mysqli_num_rows($result4) == 0)
    {
      echo "<div style='width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; font-size: 2em; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 550; color: rgb(57 162 211);'>No results found</div>";
    }
  }
?>
</form>
</div>
</div>
</div>





 </div>
  </body>
</html>