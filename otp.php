<?php
include 'config/config.php';
session_start();


// Check whether someone has landed the page on entering valid email id and not just the url in search engine
if(!isset($_SESSION['check_emp'])){
    header('Location:index.php');
}

if(isset($_POST['otp_submit'])){
    $otp_input = mysqli_real_escape_string($conn,$_POST['otp']);
    $emp_id = $_SESSION['check_emp'];
    // Check that session id and id in the url matches
    if(isset($_GET['id']) && $emp_id == $_GET['id']){
        echo '$emp_id';
    }else{
        $_SESSION['message'] = "Invalid Employee Id";
        header('Location:index.php');
    }
}






?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Login Page</title>
</head>
<body style="padding-left: 0">
<!-- CODE FOR HEADER  -->
<header class="header">
    <section class="flex" style="justify-content:center;">
        <h3 class="logo">Salary Slip Management</h3>
    </section>
</header>
<!-- CODE FOR HEADER  -->

    <!-- CODE TO DISPLAY ANY MESSAGE STARTS -->
    <?php
if(isset($_SESSION['message'])){
    echo '<div class="message-box">
    <span>'.$_SESSION['message'].'</span>
    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>';
    unset($_SESSION['message']);
}
        ?>
<!-- CODE TO DISPLAY ANY MESSAGE ENDS -->

<div style="margin-top:100px;" class="emp-add">
   <!-- FORM TO ENTER LOGIN DETAILS STARTS -->
   <form action="otp_check.php" method="post">
    <!-- Page Title -->
    <h2 style="margin-bottom:10px; "> PayEngine</h2>
    <!-- Login Prompt -->
    <p >Please Enter OTP to continue</p>
    
    <!-- Input field for OTP -->
    <div class="form-group">
        <input class="box" placeholder="Enter your OTP" type="text" id="id" name="otp" maxlength="6" required>
    </div>
    
    <!-- Login Button -->
    <button class="submit" name="otp_submit" type="submit">Submit</button>
    
    </form>
    <!-- FORM TO ENTER LOGIN DETAILS ENDS -->
</div>

</body>
</html>