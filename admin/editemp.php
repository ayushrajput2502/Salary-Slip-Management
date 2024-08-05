<?php
include '../config/config.php';
include '../essentials/header.php';

// Check if the user is logged in, otherwise redirect to the index page
// include '../essentials/sessions.php';

$emp_id = $_GET['id'];

// Handle form submission for updating salary details
if(isset($_POST['update_profile'])){
 // CREATE VARIABLES AS PER INPUT FIELDS SET 
   // example - $var_name = mysqli_real_escape_string($conn, $_POST['input_name']);
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_pno = mysqli_real_escape_string($conn, $_POST['update_pno']);
    $update_pan = mysqli_real_escape_string($conn, $_POST['update_pan']);
    $department = mysqli_real_escape_string($conn, $_POST['update_dept']);
    $update_pf = mysqli_real_escape_string($conn, $_POST['update_pf']);
    $update_acc = mysqli_real_escape_string($conn, $_POST['update_acc']);
    $update_uan = mysqli_real_escape_string($conn, $_POST['update_uan']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $update_doj = mysqli_real_escape_string($conn,$_POST['update_doj']);
    $update_doj = date("Y-m-d",strtotime($update_doj));
    

    if (isset($_POST['update_leave']) && !empty($_POST['update_leave'])) {
   $update_leave = mysqli_real_escape_string($conn,$_POST['update_leave']);
    $update_leave = date("Y-m-d",strtotime($update_leave));
  } else {
      $update_leave = NULL;
  }
    

   

 //  UPDATE THE QUERY AS PER THE VARIABLES AND COLUMN NAMES IN employee_details TABLE
    $update = "UPDATE employee_details SET name= '$update_name', phone_no='$update_pno', pf_no='$update_pf', pan_no='$update_pan', uan_no= '$update_uan', bank_acc= '$update_acc', department= '$department',gmail = '$update_email', joining_date='$update_doj',leaving_date = '$update_leave' WHERE emp_id = '$emp_id'";
    $result = mysqli_query($conn, $update);
    if($result){
      $_SESSION['message'] = "Details of ".$update_name." updated";
      $logger->log($_SESSION['name'] . ' updated employee details of emp: ' . $emp_id);
        header('location:show_emp.php');
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
   // QUERY TO RETRIVE EXISTING DATA IN INPUT FIELDS
      $select = mysqli_query($conn, "SELECT * FROM employee_details WHERE emp_id = '$emp_id' ") or die('query failed');
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

   <form action="" method="post" >
   <!-- ADD THE INPUT FILEDS AS PER COLUMNS IN employee_details TABLE -->
      <div class="flex">
         <div class="inputBox">
            <span>Name :</span>
            <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box" required>
            <span>Phone no:</span>
            <input type="number" name="update_pno" oninput="restrictuser(this)" value="<?php echo $fetch['phone_no']; ?>" class="box" required>
            <span>Pan No :</span>
            <input type="text" name="update_pan" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pan_no']; ?>"  class="box" required>
            <span>Department:</span>
            <input type="text" name="update_dept" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['department']; ?>"  class="box" required>
            <span>Date of joining:</span>
            <input type="date" name="update_doj" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['joining_date']; ?>"  class="box" required>
         </div>
         <div class="inputBox">
            <span>PF:</span>
            <input type="text" name="update_pf" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['pf_no']; ?>" class="box" required>
            <span>UAN:</span>
            <input type="text" name="update_uan" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['uan_no']; ?>" class="box" required>
            <span>Account number:</span>
            <input type="text" name="update_acc" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['bank_acc']; ?>" class="box" required>
            <span>Email:</span>
            <input type="email" name="update_email" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['gmail']; ?>" class="box" required>
            <span>Date of leaving:</span>
            <input type="date" name="update_leave" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch['leaving_date']; ?>"  class="box" >
         </div>
      </div>
      <!-- Checkbox to confirm import -->
      <br>
        <label  for="confirmImport"><b>Confirm Import</label>
        <input type="checkbox" id="confirmImport" required>
      <input type="submit" value="Update profile" name="update_profile" class="button">
      <a href="show_emp.php" class="button">Go back</a>
   </form>
   <?php 
mysqli_close($conn);
?>


   <script>
    // TO restrict the user from entering more than 10 numbers in phone no
    function restrictuser(input){
        var no = input.value.replace(/\D/g,'');
        if(no.length>10){
            no = no.slice(0,10);
        }
        input.value = no;
    }

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

</script>
</div>
</div>
</body>
</html>