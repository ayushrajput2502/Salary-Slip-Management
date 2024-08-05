<?php  

session_start();
if(!isset($_SESSION['id'])){
header("location:../index.php");
}

if($_SESSION['type']=='employee'){
    $inactivity_timeout = 600; //10 minutes
}elseif($_SESSION['type']=='accountant'){
    $inactivity_timeout = 1200; //20 minutes
}elseif($_SESSION['type']=='SA'){
    $inactivity_timeout = 600; //10 minutes
}

// Check for user inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactivity_timeout) {
    // User has been inactive for too long; destroy the session
    session_unset();  // Unset all session variables
    session_destroy(); // Destroy the session
    header('location: ../index.php?message=You have been logged out due to inactivity. Please log in again.'); // Redirect the user to the login page
    exit;
}
?>