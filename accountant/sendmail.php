
<?php
// Include necessary configuration and header files
include '../config/config.php'; // Include the configuration file that connects to the database
include '../essentials/header.php'; // Include header related essentials
include('../smtp/PHPMailerAutoload.php');


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
	//$mail->SMTPDebug = 2; 
    // CREATE AN EMAIL ACCOUNT AND PLACE THE NAME OF THAT ACCOUNT HERE
	$mail->Username = ""; //enter from gmail name here
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
		echo $mail->ErrorInfo;
	}else{
		$_SESSION['message'] = "Email has been sent successfully";
        header('location:../accountant/sendmail.php');
	}
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles1.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Document</title>
</head>
<body>
<?php
            if(isset($_SESSION['message'])){
                // echo $_SESSION['message'];
                echo '<div class="message-box">
                <span>'.$_SESSION['message'].'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>';
                unset($_SESSION['message']);
            }

        
?>


<?php

if(isset($_POST['send_mail'])){
    $selectedMonth = $_POST['selected_month'];
    // CHECK WHETHER an employee for the specified month has the salary sheet and if yes then get the gmail from employee_details table
    $getEmployeesQuery = "SELECT ed.emp_id, ed.name, ed.gmail,s.date 
                          FROM employee_details ed
                          INNER JOIN salary s ON s.emp_id = ed.emp_id
                          WHERE DATE_FORMAT(s.date, '%Y-%m') = '$selectedMonth'";

    $getEmployeesResult = mysqli_query($conn, $getEmployeesQuery);

    if(mysqli_num_rows($getEmployeesResult)>0){
        // To store info in log file that accountant has sent the email
        $logger->log($_SESSION['name'] . ' sent email successfully ');
        while($row = mysqli_fetch_assoc($getEmployeesResult)){
            $email = $row['gmail'];
            $name = $row['name'];

            // MESSAGE WHICH WILL BE DISPLAYED IN THE EMAIL
            $msg = "
            <div style='background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px 2px #ccc;'>
            <h2>Salary Details Uploaded</h2>
            <p>Hello $name,</p>
            <p>We are pleased to inform you that your salary details for the current month have been successfully uploaded on APSIT PayEngine.</p>
            <p>You can view your salary details by logging into your account on our system.</p>
            <p>For any queries or assistance, please feel free to contact your accountant.</p>
            <p><a href='localhost/mainproject/' style='text-decoration: none; color: #007BFF;'>Click here to visit our website</a></p>
        </div>
            ";
            
            // Call the smtp_mailer function to send the email
            smtp_mailer($email, 'APSIT PayEngine Salary Sheet', $msg);
        }
    }else{
        $_SESSION['message'] = "Salary sheet for ".date("F Y",strtotime($selectedMonth))." has not been uploaded yet!";
        header("Location:../accountant/sendmail.php");
    }
}
?>

<!-- The following HTML code creates a form for uploading a file and specifying a month -->

<div class='form-container'>
        <div style="width:680px;" class="instructions">
        <h2><i class="fa-solid fa-triangle-exclamation"></i> Instruction:</h2>
        <ul>
            <li style="font-size:17px;"><strong>Please send mail only after the all employees salary sheet is uploaded successfully! </strong> </li>
        </ul>
        </div>
    </div>

    <div style="height: 100vh; margin-top:-200px;" class='form-container'>
        <!-- Form for uploading a file and specifying a month -->
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return confirmImport()">
            <p>Specify Month</p> <!-- Prompt the user to specify a month -->
            <input type="month" name="selected_month" max="<?php echo date('Y-m'); ?>" required> <!-- Input field for specifying the month -->
    
            <!-- Checkbox to confirm import -->
            <br>
            <label  for="confirmImport"><b>Confirm Mail</label>
            <input type="checkbox" id="confirmImport" required>
            
            <!-- Button to submit the form and trigger the import process -->
            <button type="submit" name="send_mail">Send</button>
    
    
        </form>
    </div>


<script>
    function confirmImport() {
        if (!document.getElementById('confirmImport').checked) {
            alert('Please confirm the import by checking the checkbox.');
            return false; // Prevent form submission
        }
        else {
            alert('Import successful!');
            return true; // Allow form submission
        }
    }

    function confirmAndRemove(element) {
            // Show a confirm dialog
            var result = confirm("Are you sure you want to remove details of uploaded data?");
            
            // If the user clicks "OK" in the confirm dialog, remove the element
            if (result) {
                element.parentElement.remove();
            }
        }
</script>



    <?php
    mysqli_close($conn);
    ?>
</div>
</body>
</html>