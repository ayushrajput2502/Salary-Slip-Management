<?php
$conn = mysqli_connect('localhost','root','','slip');
if(!$conn){
    echo "Connection failed";
}
date_default_timezone_set('Asia/Kolkata');

?>