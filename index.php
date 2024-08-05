<!-- 
    ********************
    
    THIS PAGE HERE CONTAINS ALL THE LOGIN PROCEDURE

    ********************
  -->

<?php include 'config/config.php'; 
    include('smtp/PHPMailerAutoload.php');
session_start();
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
   <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    <!-- Page Title -->
    <h2 style="margin-bottom:10px; "> PayEngine</h2>
    <!-- Login Prompt -->
    <p >Please enter email to continue</p>
    
    <!-- Input field for Employee ID -->
    <div class="form-group">
        <input class="box" placeholder="Enter your email" type="email" id="id" name="email" required>
    </div>
    
    <!-- Login Button -->
    <button class="submit" name="log_submit" type="submit">Login</button>
    
    
    <!-- FORM TO ENTER LOGIN DETAILS ENDS -->
</div>



    <!-- Login Code -->
    <?php 

function smtp_mailer($to,$subject, $msg){
    // this is the library code please dont change it
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'tls'; 
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	//$mail->SMTPDebug = 2; 
    // CREATE AN EMAIL ACCOUNT AND PLACE THE NAME OF THAT ACCOUNT HERE
	$mail->Username = ""; // enter your mail here
    // CREATE A PASSWORD KEY BY LOGGING INTO THE ACCOUNT 
    // FIRST TURN ON 2step verification AND CREATE A PASSWORD KEY FROM THE GIVEN LINK
    // https://myaccount.google.com/u/4/apppasswords?pli=1&rapt=AEjHL4NeKlVZJJyVRj4-ijWW0e2BDZxm2j2GcBEaUK2sM6igF3Zw_YW8jBo-0o98kJTNCXev8HDKuRsHYQ5WVAp7hgKtqhb6jswwg2Zhzg3Y2U2CJFmixbc&pageId=none
    // AFTER GENERATEING PASSWORD KEY ENTER IT HERE 
	$mail->Password = "";
	$mail->SetFrom("email");
	$mail->Subject = $subject;
	$mail->Body =$msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	if(!$mail->Send()){
        $_SESSION['message'] = 'Could not send email '.$mail->ErrorInfo;
        header('location: index.php');
	}else{
		$_SESSION['message'] = "OTP is sent on your mail successfully";
        header('location:otp.php');
	}
}

if(isset($_POST['log_submit'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $check = "SELECT * FROM employee_details WHERE gmail = '$email' AND role!='Left'";
    $check_result = mysqli_query($conn,$check);
    if(mysqli_num_rows($check_result)>0){
        $row = mysqli_fetch_assoc($check_result);
        $emp_id = $row['emp_id'];
        $name = $row['name'];
        $email = $row['gmail'];
        $otp = sprintf("%06d", rand(0, 999999));
        $expiry = date("Y-m-d h:i:s",time() + 60 * 10); // EXPIRE THE LINK IN 5 Minutes
        $update_query = "UPDATE resetpass SET otp = '$otp',otp_expire='$expiry' WHERE emp_id = '$emp_id' ";
        $update_result = mysqli_query($conn,$update_query);
        if($update_result){
            // MESSAGE WHICH WILL BE DISPLAYED ON THE MAIL 
            $msg = "
            <div style='background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px 2px #ccc;'>
                <h2>Login OTP</h2>
                <p>Hello $name,</p>
                <p>Your One-Time Password (OTP) for login is: <strong>$otp</strong></p>
                <p>This OTP will expire in 10 minutes.</p>
                <p>If you did not request this OTP, please contact us immediately.</p>
                <p>Do not reply to this email. For any queries, please contact your admin.</p>
                <p>Thank you</p>
            </div>
            ";
            smtp_mailer($email, 'Login otp', $msg);
            // $_SESSION['message']='Otp has been sent on your email';
            $_SESSION['check_emp'] = $emp_id;
            // header('Location: otp.php');
        } else {
            // Handle the case where the update query fails
            $_SESSION['message']='Couldnt send email';
            header('location:index.php');
        }

    }else{
        echo '<script>alert("Email does not exist or your account was deleted");</script>';
    }
}



    
    mysqli_close($conn);
    ?>

<script>
    // Check if there's a message in the URL (added during automatic logout)
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');

    if (message) {
        // Display an alert with the message
        alert(message);
    }
</script>
</body>
</html>
