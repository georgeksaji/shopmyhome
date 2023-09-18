<?php
include ("connection.php");
session_start();
$_SESSION['search'] = null;
$_SESSION['brand_id'] = null;
$_SESSION['category_id'] = null;
$_SESSION['type_id'] = null;
if(isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
}
else {
  $userId = null;
  $usertype = null;
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
//staff button
if(isset($_POST['staff']))
{
  header("Location: staff.php");
}
//profile button
if(isset($_POST['profile']))
{
  header("Location: profile.php");
}
//logout button
if(isset($_POST['logout']))
{
  session_destroy();
  header("Location: index.php");
}
//brand button
if(isset($_POST['brand']))
{
  $brand_id = $_POST['brand'];
  $_SESSION['brand_id'] = $brand_id;
  $_SESSION['search'] = null;
  $_SESSION['category_id'] = null;
  $_SESSION['type_id'] = null;
  header("Location: list_products.php");
}
//all brands
if(isset($_POST['all_brands']))
{
  echo 'all brands';
  header("Location: list_brands.php");
}
//search appliance
if(isset($_POST['submit']))
{
  $search = $_POST['search'];
  $_SESSION['search'] = $search;
  echo $search;
  $_SESSION['brand_id'] = null;
  $_SESSION['category_id'] = null;
  $_SESSION['type_id'] = null;
  header("Location: list_products.php"); 
}
//cart button
if(isset($_POST['cart']))
{
  echo 'cart';
  header("Location: cart.php");
}
//category button
if(isset($_POST['category']))
{
  $category_id = $_POST['category'];
  $_SESSION['category_id'] = $category_id;
  $_SESSION['search'] = null;
  $_SESSION['brand_id'] = null;
  $_SESSION['type_id'] = null;
  header("Location: list_types.php");
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
  display: flex;
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
/*brands for you*/
.brands-for-you
{
  width: 100%;
  height: 27vh;
}
.brands-for-you-text{
  width: 100%;
  height: 5vh;
  font-style:oblique;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 124%;
  color:dimgrey;
  text-align: center;
  padding-top: 0.4%;
}
.brand-image-box-outer
{
  width: 100%;
  height: 21vh;
  display: inline-flex;
  justify-content: center;
  align-items: center;
}
.brand-images-boxes{   
   width: 100%;
    height: 93%;
    margin-inline: 0.50%;
    background-size: contain;
    background-color: rgb(256,256,256);
    background-repeat: no-repeat;
    background-position: center center;
    border-style: none;
    font-size: medium;
    color: #000;
    transition: 0.5s;
}
.brand-image-box-outer form{
  width: 100%;
  height: 100%;
}
.brand-images-boxes:hover{
  transform: scale(1.1);
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); /* Adjust shadow values as needed */

}
/*types for you*/ 
.info-cards
{
  width: 100%;
  height: 20vh;
  background-color: rgba(256,256,256);
  padding-block: 2.5%;
}


.cards-outer
{
    display: flex;
    justify-content: center;
    align-items: center;
    height: auto;
    width: 100%;
}

.card-n-outer
{
    height: 16%;
    width: 18%;
    padding: 1%;
    margin-inline: 3.1%;
    background-color: rgb(120, 181, 225);
    border-radius: 9px;
    transition: 0.3s ease-in-out;
}
.card-n-outer:hover
{
  box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
}
.card-n-inner
{
    height: 100%;
    width: 100%;;
    background-color:none;
    transition: 0.3s ease-in-out;
}
.card-n-image{
  height: 10%;
    width: 5%;
    margin: -3.1%;
    background-size: contain;
    background-repeat: no-repeat;
    position: absolute;
    z-index: 1;
}

.card-text-heading
{
  font-size: 99%;
    color: rgb(256,256,256);
    text-align: center;
    margin: auto;
}
.about-outer
{
  width: 100%;
  height: 50%;
  background-color: rgb(233 232 228 / 39%);
}
.about-left
{
  width: 50%;
  height: 100%;
  float: left;
  background-color: transparent;
}
.about-right
{
  width: 50%;
  height: 100%;
  padding-top: 5%;
  padding-inline: 3%;
  display: grid;
  
  background-color: transparent;
  float: right;
}
.connect-with-us
{
  margin-top: 5%;
    display: inline-flex;
    width: 100%;
    height: 60%;
    flex-direction: column;
    flex-wrap: wrap;
    align-content: center;
}
.connect-with-us h5
{
  font-size: 99%;
    color: rgb(0,0,0);
    text-align: center;
}
.social-media th
{
  border-width: 75px;
  border-color: transparent;
  border-top:0px;
  border-bottom:0px;
}
</style>
    
  </head>
  <body>
 <div class="home-outer">
 
  <div class="top-navigation">
        <div class="navigation-logo"></div>
        <!-- <div class="search-bar"><input type="search" placeholder="Search for home appliances"></div> -->
        <nav class="navbar bg-body-tertiary">
          <div class="container-fluid">
            <form class="d-flex" role="search" method="POST">
              <input class="form-control me-2" type="search" placeholder="Search  for home appliances" aria-label="Search" name="search">
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
                  echo '<td><button class="admin-box" name="staff">Staff Dashboard</button></td>';
                  echo '<td><button class="logout-box" name="logout">Logout</button></td>';
                  echo '</tr>';
                  echo '</form>';//    
                }
                //if usertype=CU
                if($usertype == 'CU') {
                  //profile page, logout
                  echo '<form action="" method="POST">';
                  echo '<tr><td><button class="profile-box" name="profile"><img src="profile1.png" height="30px" width="30px"></button></td>';
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
  </div>
  <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 5"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 0"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active" data-bs-interval="5000">
        <img src="Picture1.jpg" class="d-block w-100">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="Picture2.jpg" class="d-block w-100">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="Picture3.jpg" class="d-block w-100">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="Picture4.jpg" class="d-block w-100">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="Picture5.jpg" class="d-block w-100">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
  </div>
  <div class="brands-for-you">
    <div class="brands-for-you-text">Brands for you</div>
    <div class="brand-image-box-outer">
      <?php
    //<div class="brand-images-boxes" style="background-image:url(brand_images/whirlpool.png);background-size:cover;"></div>
    //select any 10 brands from tbl_brand as random. then set background image as brand_images/brandname.png
    $sql = "SELECT * FROM tbl_brand ORDER BY RAND() LIMIT 8";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result))
    {
      $brand_id = $row['Brand_ID'];
      //Brand_Logo
      $brand_logo=$row['Brand_Logo'];
      //click on any brand logo to go to brand page where all products of that brand are displayed
      echo "<form action='' method='POST'>";
      echo "<button class='brand-images-boxes' name='brand' value='$brand_id' style=\"background-image:url('$brand_logo');\"></button>";
      echo "</form>";   
    }
  echo '<form action="" method="POST">';
       echo "<button class='brand-images-boxes' name='all_brands' style=\"background-image:url('more.png');\"></button>";
       echo "</form>";
    ?>
  </div> 
  </div>
  <div class="info-cards">
  <div class="cards-outer">
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('home_delivery.gif');"></div><div class="card-n-inner"><p class="card-text-heading">FAST HOME DELIVERY</p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('fast_and_reliable.gif');"></div><div class="card-n-inner"><p class="card-text-heading">FAST & RELIABLE</p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('secure_payment.gif');"></div><div class="card-n-inner"><p class="card-text-heading">SECURED PAYMENT</p></div></div>
          <div class="card-n-outer"><div class="card-n-image" style="background-image: url('lowest_guarantee.gif');"></div><div class="card-n-inner"><p class="card-text-heading">LOWEST PRICE GUARANTEED</p></div></div>
        </div>
        </div>

        <div class="about-outer">
          <div class="about-left">
            <div class="connect-with-us">
            
            <table class="social-media">
              <tr><td colspan="5"><h5>Follow us on</h5></td></tr>
              <tr>
            <th><a href="https://www.instagram.com/georgeksaji/"><img src="instagram.png" height="40px" width="40px"></a></th>
            <th><a href="https://twitter.com/georgeksaji"><img src="twitter-x.png" height="40px" width="40px"></a></th>
            <th><a href="https://www.facebook.com/george.ksaji.39"><img src="facebook.png" height="40px" width="40px"></a></th>
            <th><a href="https://www.threads.net/@georgeksaji"><img src="threads.png" height="40px" width="40px"></a></th>
            <th><a href="mailto:georgeksaji14@gmail.com"><img src="gmail.png" height="40px" width="40px"></a></th>
          </tr>
          <tr style="border-width: 24px;border-color:transparent;"><td colspan="5" style="text-align: center;font-family: fangsong;font-size: 106%;"><a href="tel:9495224376"><img src="phone.png" alt="Phone" height="40px" width="40px"></a></td></tr>
          <tr rowspan="3"><td colspan="5"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3929.2677553294766!2d76.35532087459478!3d9.994727373126292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b080d5991771765%3A0x3ae2017fa74af5d7!2sRajagiri%20College%20of%20Management%20and%20Applied%20Sciences!5e0!3m2!1sen!2sin!4v1693877364945!5m2!1sen!2sin" width="100%" height="25%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </td></tr>  
        
        </table>
            </div>




          </div>
          <div class="about-right">
            <p>
          Welcome to Shopmyhome! We're dedicated to simplifying your home life.
          Explore our wide range of top-quality appliances and tech solutions 
          designed to make everyday living more convenient and enjoyable.
          Discover the perfect balance of innovation and functionality with us.</p>
          <img src="picture3.png" alt="" height="50" style="margin-top:2%">

        </div>
        </div>


  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>
</html>