<?php 

include '../essentials/header.php';
include '../config/config.php';

// $_SESSION['last_activity'] = time();

    $emp_id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/styles1.css">
    
</head>
<body>
    

<div class="update-profile">

   <?php
      $select = mysqli_query($conn, "SELECT * FROM employee_details WHERE emp_id = '$emp_id' ") or die('query failed');
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

   <form action="" method="post" enctype="multipart/form-data">
     <p class="message"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You cannot edit the information  contact your admin</p>
      <div class="flex">
         <!-- INPUT FIELDS TO DISPLAY EMPLOYEES DETAILS -->
         <div class="inputBox">
            <span>Name :</span>
            <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box" readonly>
            <span>Phone no:</span>
            <input type="number" name="update_pno" oninput="restrictuser(this)" value="<?php echo $fetch['phone_no']; ?>" class="box" readonly>
            <span>Pan No :</span>
            <input type="text" name="update_pan" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pan_no']; ?>"  class="box" readonly>
            <span>Department:</span>
            <input type="text" name="update_dept" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['department']; ?>"  class="box" readonly>
            <span>Date of joining:</span>
            <input type="date" name="update_doj" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['joining_date']; ?>"  class="box" readonly>
         </div>
         
         <div class="inputBox">
            <!-- <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>"> -->
            <span>PF:</span>
            <input type="text" name="update_pf" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pf_no']; ?>" class="box" readonly>
            <span>UAN:</span>
            <input type="text" name="update_uan" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['uan_no']; ?>" class="box" readonly>
            <span>Account number:</span>
            <input type="text" name="update_acc" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['bank_acc']; ?>" class="box" readonly>
            <span>Email:</span>
            <input type="email" name="update_email" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['gmail']; ?>" class="box" readonly>
            <span>Date of leaving:</span>
            <input type="date" name="update_leave" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['leaving_date']; ?>"  class="box" readonly>
         </div>
      </div>
      <?php if($_SESSION['type']=='employee'){
         echo '<a href="currentmonth.php" class="button">Go back</a>';
      }elseif($_SESSION['type']=='accountant'){
         echo '<a href="../accountant/upload.php" class="button">Go back</a>';
      } ?>
      
   </form>


<?php mysqli_close($conn); ?>
</body>
</html>

