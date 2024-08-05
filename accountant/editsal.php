<?php
// Include necessary configuration and header files
include '../config/config.php';
include '../essentials/header.php';

// include '../essentials/sessions.php';



// Retrieve employee ID and date from the URL
$emp_id = $_GET['id'];
$date = $_GET['date'];

// Handle form submission for updating salary details
if(isset($_POST['update_sal'])){

   // CREATE VARIABLES AS PER INPUT FIELDS SET 
   // example - $var_name = mysqli_real_escape_string($conn, $_POST['input_name']); 
    $update_bs = mysqli_real_escape_string($conn, $_POST['update_bs']);
    $update_agp = mysqli_real_escape_string($conn, $_POST['update_agp']);
    $update_dw = mysqli_real_escape_string($conn, $_POST['update_dw']);
    $update_ab = mysqli_real_escape_string($conn, $_POST['update_ab']);
    $update_cla = mysqli_real_escape_string($conn, $_POST['update_cla']);
    $update_er = mysqli_real_escape_string($conn, $_POST['update_er']);
    $update_gross = mysqli_real_escape_string($conn, $_POST['update_gross']);
    $update_ad = mysqli_real_escape_string($conn, $_POST['update_ad']);
    $update_pt = mysqli_real_escape_string($conn, $_POST['update_pt']);
    $update_aagp = mysqli_real_escape_string($conn, $_POST['update_aagp']);
    $update_bagp = mysqli_real_escape_string($conn, $_POST['update_bagp']);
    $update_da = mysqli_real_escape_string($conn, $_POST['update_da']);
    $update_hra = mysqli_real_escape_string($conn, $_POST['update_hra']);
    $update_ta = mysqli_real_escape_string($conn, $_POST['update_ta']);
    $update_sp = mysqli_real_escape_string($conn, $_POST['update_sp']);
    $update_pf = mysqli_real_escape_string($conn, $_POST['update_pf']);
    $update_it = mysqli_real_escape_string($conn, $_POST['update_it']);
    $update_ns = mysqli_real_escape_string($conn, $_POST['update_ns']);

   //  UPDATE THE QUERY AS PER THE VARIABLES AND COLUMN NAMES IN salary TABLE
    $update = "UPDATE salary SET basic_salary= '$update_bs', agp='$update_agp', days_worked='$update_dw', actual_basic='$update_ab', actual_agp= '$update_aagp', basic_add_agp= '$update_bagp', da= '$update_da',hra = '$update_hra',cla='$update_cla',ta='$update_ta', exam_rem='$update_er', spl_pay = '$update_sp', gross='$update_gross', pf='$update_pf', pt='$udpate_pt', i_tax='$update_it', add_ded='$update_ad', net_salary='$update_ns'  WHERE emp_id = '$emp_id' AND date='$date'";
    $result = mysqli_query($conn, $update);
    if($result){
      // Store info in logger that accountant has update someones salary sheet
      $logger->log($_SESSION['name'] . ' updated salary details of emp: ' . $emp_id);
      $_SESSION['message'] = "Salary details of ".$emp_id." updated for ". date("F Y",strtotime($date));
        header('Location:view.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles1.css">
    <title>Document</title>
</head>
<body>

<div class="update-profile">
    <?php
      $select = mysqli_query($conn, "SELECT * FROM salary WHERE emp_id = '$emp_id' AND date = '$date'; ") or die('query failed');
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
      }
   ?> 

   <form action="" method="post" enctype="multipart/form-data">
    <p>Salary Slip of emp: <?php echo $fetch['emp_id'] ?> for <?php echo date("F Y",strtotime($fetch['date']));?></p>
      <div class="flex">
         <div class="inputBox">
            <span>Basic Salary :</span>

            <!-- ADD THE INPUT FILEDS AS PER COLUMNS IN salary TABLE -->

            <input type="text" name="update_bs" value="<?php echo $fetch['basic_salary']; ?>" class="box" required>
            <span>AGP:</span>
            <input type="number" name="update_agp" oninput="restrictuser(this)" value="<?php echo $fetch['agp']; ?>" class="box" required>
            <span>Days worked :</span>
            <input type="number" name="update_dw" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['days_worked']; ?>"  class="box" required>
            <span>Actual basic:</span>
            <input type="number" name="update_ab" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['actual_basic']; ?>"  class="box" required>
            <span>CLA:</span>
            <input type="number" name="update_cla" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['cla']; ?>" class="box" required>
            <span>Exam rem:</span>
            <input type="number" name="update_er" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['exam_rem']; ?>" class="box" required>
            <span>Gross:</span>
            <input type="number" name="update_gross" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['gross']; ?>" class="box" required>
            <span>PT:</span>
            <input type="number" name="update_pt" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pt']; ?>" class="box" required>
            <span>Add ded:</span>
            <input type="number" name="update_ad" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['add_ded']; ?>" class="box" required>
         </div>
         <div class="inputBox">
            <!-- <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>"> -->
            <span>Actual AGP:</span>
            <input type="number" name="update_aagp" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['actual_agp']; ?>" class="box" required>
            <span>Basic + AGP:</span>
            <input type="number" name="update_bagp" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['basic_add_agp']; ?>" class="box" required>
            <span>Da :</span>
            <input type="number" name="update_da" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['da']; ?>" class="box" required>
            <span>HRA:</span>
            <input type="number" name="update_hra" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['hra']; ?>" class="box" required>
            <span>TA:</span>
            <input type="number" name="update_ta" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['ta']; ?>" class="box" required>
            <span>Special Pay:</span>
            <input type="number" name="update_sp" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['spl_pay']; ?>" class="box" required>
            <span>PF:</span>
            <input type="number" name="update_pf" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pf']; ?>" class="box" required>
            <span>Income tax:</span>
            <input type="number" name="update_it" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['i_tax']; ?>" class="box" required>
            <span>Net salary:</span>
            <input type="number" name="update_ns" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['net_salary']; ?>" class="box" required>
         </div>
      </div>
      <!-- Checkbox to confirm import -->
      <br>
        <label  for="confirmImport"><b>Confirm update</label>
        <input type="checkbox" id="confirmImport" required>
      <input type="submit" value="Update" name="update_sal" class="button">
      <a href="view.php" class="button">Go back</a>
      <button type="button" id="resetButton" class="button">Reset Fields</button>
   </form>

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
    // Function to reset all input fields to 0
    document.getElementById('resetButton').addEventListener('click', function() {
        // Get all input fields and set their values to 0
        var inputFields = document.querySelectorAll('.box');
        for (var i = 0; i < inputFields.length; i++) {
            inputFields[i].value = 0;
        }
    });
   </script>
   <?php 
mysqli_close($conn);
?>