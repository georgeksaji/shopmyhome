<?php

session_start();
if(isset($_SESSION['User_ID']) && $_SESSION['User_ID'] !== null) {
  $userId = $_SESSION['User_ID'];
  $usertype = $_SESSION['User_Type'];
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

}

.top-navigation {
  width: 100%;
  background-color: rgba(101, 194, 240, 0.4);

  background-image: url(Picture3.png);
  background-size: contain;
  background-repeat: no-repeat;
  padding-left: 3%;
  background-origin: content-box;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navigation-logo {
  background-image: url(Picture3.png);
  background-size: cover;
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
x
}

.navigation-item {
  display: flex;
  padding-right:5%;
}

.login-box,
.signup-box {
  font-size: small;
  height: 40px;
  width: 80px;
  padding:2px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 550;
  color:rgb(0, 0, 0);
  display: flex;
  justify-content: center;
  align-items: center;
  transition: 0.3s;
}

.login-box:hover,
.signup-box:hover {
  font-size: small;
  padding:4px;
  background-color: rgb(0, 0, 0, 0.8);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 550;
  color: white;
  display: flex;
  justify-content: center;
  align-items: center;
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
  font-weight: 550;
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
</style>
    
  </head>
  <body>
 <div class="home-outer">
 
  <div class="top-navigation">
        <div class="navigation-logo"></div>
        <!-- <div class="search-bar"><input type="search" placeholder="Search for home appliances"></div> -->
        <nav class="navbar bg-body-tertiary">
          <div class="container-fluid">
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search  for home appliances" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </nav>
            <div class="navigation-item">
                <a href="Login.php" class="href"><div class="login-box">Login</div></a>
                <a href="Customer_Sign_Up.php" class="href"><div class="signup-box">Sign Up</div></a> 
            </div> 
  </div> 
  <div class="category-navigation">
    <a href="" class="href"><div class="category-name">TV & Audio</div></a>
    <a href="" class="href"><div class="category-name">AC & Cooler</div></a> 
    <a href="" class="href"><div class="category-name">Kitchen Appliances</div></a> 
    <a href="" class="href"><div class="category-name">Laundry Appliances</div></a> 
    <a href="" class="href"><div class="category-name">cat5</div></a> 
    <a href="" class="href"><div class="category-name">cat6</div></a> 
    <a href="" class="href"><div class="category-name">cat7</div></a> 
    <a href="" class="href"><div class="category-name">cat8</div></a> 
    <a href="" class="href"><div class="category-name">cat9</div></a> 
    <a href="" class="href"><div class="category-name">cat10</div></a>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>
</html>