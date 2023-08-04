<?php
require 'core.php';
include 'connection.php';
if (loggedin()){
echo "You are logged in";
}
else{
    include 'index.php';
}