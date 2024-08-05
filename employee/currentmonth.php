<?php 

include '../essentials/header.php';
include '../config/config.php';
// $_SESSION['last_activity'] = time();
// include '../essentials/sessions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paysleep_admin</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/styles1.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
   


<div class="update-profile">
 <?php
    $date = date('Y-m');
    
    $select = mysqli_query($conn, "SELECT * FROM salary WHERE emp_id = {$_SESSION['id']} AND DATE_FORMAT(date, '%Y-%m') = '$date'");
    
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
         // print_r($fetch);
         ?> 
    <form action="download.php" method="post" enctype="multipart/form-data" target="_blank">
    <p style="font-weight:bold;">Salary Slip for <?php echo date("F Y",strtotime($fetch['date']));?></p>
      <div class="flex">
         <div class="inputBox">
            <span>Basic Salary :</span>

            <!-- ADD THE INPUT FILEDS AS PER COLUMNS IN salary TABLE -->
            <input type="hidden" name="current_date" value="<?php echo  $date; ?>">

            <input type="text" name="update_bs" value="<?php echo $fetch['basic_salary']; ?>" class="box" readonly>
            
            <span>Basic+AGP:</span>
            <input type="number" name="update_bagp" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['basic_add_agp']; ?>" class="box" readonly>
            
            <span>PT:</span>
            <input type="number" name="update_pt" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pt']; ?>" class="box" readonly>
            <span>Add ded:</span>
            <input type="number" name="update_ad" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['add_ded']; ?>" class="box" readonly>
            <span>Net salary:</span>
            <input type="number" name="update_ns" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['net_salary']; ?>" class="box" readonly>
         </div>
         <div class="inputBox">
           
            <span>AGP:</span>
            <input type="number" name="update_agp" oninput="restrictuser(this)" value="<?php echo $fetch['agp']; ?>" class="box" readonly>
            <span>Days worked:</span>
            <input type="number" name="update_dw" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['days_worked']; ?>"  class="box" readonly>
           
            
            
           
            <span>HRA:</span>
            <input type="number" name="update_hra" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['hra']; ?>" class="box" readonly>
            <span>TA:</span>
            <input type="number" name="update_ta" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['ta']; ?>" class="box" readonly>
            
            
            <span>Income tax:</span>
            <input type="number" name="update_it" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['i_tax']; ?>" class="box" readonly>
            
         </div>
         <div class="inputBox">
         <span>Actual basic:</span>
            <input type="number" name="update_ab" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['actual_basic']; ?>"  class="box" readonly>
            <span>Exam rem:</span>
            <input type="number" name="update_er" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['exam_rem']; ?>" class="box" readonly>
            <span>Gross:</span>
            <input type="number" name="update_gross" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['gross']; ?>" class="box" readonly>
            <span>Special Pay:</span>
            <input type="number" name="update_sp" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['spl_pay']; ?>" class="box" readonly>
        
         </div>
         <div class="inputBox">
         <span>Actual AGP:</span>
            <input type="number" name="update_aagp" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['actual_agp']; ?>" class="box" readonly>
            <span>CLA:</span>
            <input type="number" name="update_cla" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['cla']; ?>" class="box" readonly>
            <span>Da :</span>
            <input type="number" name="update_da" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['da']; ?>" class="box" readonly>
            <span>PF:</span>
            <input type="number" name="update_pf" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pf']; ?>" class="box" readonly>
         
         
         </div>
      </div>
      <button type="submit" name="current_print" class="button">Print </button>
   </form>
   <?php
      }else{
        
            echo '<div class="message-box">
            <span>'.'Salary Slip for current month is not uploaded yet!'.'</span>
            </div>';
            
        
      }
   ?>
   
   
   
   
   <?php mysqli_close($conn); ?>
</body>
</html>

