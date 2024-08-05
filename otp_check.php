<?php
include 'config/config.php';
require_once 'logger.php';
$logger = new Logger('log.txt');
session_start();

// Check whether someone has landed the page on entering a valid email id and not just the URL in a search engine
if (!isset($_SESSION['check_emp'])) {
    header('Location: index.php');
    exit; // Terminate the script after a header redirect
}

if (isset($_POST['otp_submit'])) {
    $otp_input = mysqli_real_escape_string($conn, $_POST['otp']);
    $emp_id = $_SESSION['check_emp'];

    $check_otp_query = "SELECT otp, otp_expire FROM resetpass WHERE emp_id='$emp_id'";
    $check_otp_query_result = mysqli_query($conn, $check_otp_query);

    if (mysqli_num_rows($check_otp_query_result) > 0) {
        $row = mysqli_fetch_assoc($check_otp_query_result);
        if (strtotime($row['otp_expire']) <= date("Y-m-d h:i:s",time())) {
            // The OTP is valid and not expired, you can proceed with the login
            if($row['otp']==$otp_input){
                $remove_otp = "UPDATE resetpass SET otp = NULL, otp_expire = NULL WHERE emp_id = '$emp_id'";
                $remove_otp_result = mysqli_query($conn, $remove_otp);
                $login_query = "SELECT * FROM employee_details WHERE emp_id = '$emp_id'";
                $login_result = mysqli_query($conn, $login_query);
                if(mysqli_num_rows($login_result)>0){

                    $login_row = mysqli_fetch_assoc($login_result);
                    $_SESSION['id'] = $login_row['emp_id'];
                    $_SESSION['name'] = $login_row['name'];
                    $_SESSION['designation'] = $login_row['designation'];
                    // GET CURRENT TIME AFTER SUCCESSFUL LOGIN 
                    $_SESSION['last_activity'] = time();

                    // Determine the role and set the session type accordingly
                    // if ($row['role'] == 'HR') {
                    //     $_SESSION['type'] = 'HR';
                    //     header('location: admin/admin.php');
                    // } 
                    if ($login_row['role'] == 'Accountant') {
                        $logger->log($_SESSION['name'] . ' logged in');
                        $_SESSION['type'] = 'accountant';
                        header('location: accountant/upload.php');
                    } elseif ($login_row['role'] == 'Employee') {
                        $_SESSION['type'] = 'employee';
                        header('location: employee/currentmonth.php');
                    }elseif ($login_row['role'] == 'SA') {
                        $_SESSION['type'] = 'SA';
                        header('location: SA/role.php');
                    } else {
                        echo '<script>alert("Invalid Role");</script>';
                    }
                }
          

            }else{
                echo $_SESSION['message'] = "OTP does not match"."<a href='index.php'> Send</a> again!";
                header('Location: otp.php');;
            }
            // echo 'success';
        } else {
            // echo 'unsuccess';
            // REMOVE THE OTP FROM THE TABLE IF TIME HAS EXPIRED
            $remove_otp = "UPDATE resetpass SET otp = NULL, otp_expire = NULL WHERE emp_id = '$emp_id'";
            $remove_otp_result = mysqli_query($conn, $remove_otp);
            $_SESSION['message'] = "OTP has already expired";
            unset($_SESSION['check_emp']);
            header('Location: index.php');
            exit;
        }
    } else {
        $_SESSION['message'] = "Invalid Employee Id";
        unset($_SESSION['check_emp']);
        header('Location: index.php');
        exit;
    }
}
?>
