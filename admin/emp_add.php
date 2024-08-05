<?php
include '../config/config.php';
include '../essentials/header.php';

// include '../essentials/sessions.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles1.css">
  
</head>
<body>
<?php
             if(isset($_SESSION['message'])){
                echo '<div class="message-box">
                <span>'.$_SESSION['message'].'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
             </div>';
                unset($_SESSION['message']);
            }
        
        ?>
<section class="emp-add">
    <form action="emp_add_code.php" method="post">
       
        <h2>Add Employee</h2>
        <!-- ADD INPUT FIELDS AS REQUIRED  
            AND UPDATE THE COLUMN NAMES IN emp_add_code.php file
        -->
        <input type="number" class="box"  name="id" placeholder="Enter Id" required>
        <input type="text" class="box"  name="name" placeholder="Enter Name" required>
        <input type="number"  class="box"  name="phn_no" oninput="restrictuser(this)" placeholder="Enter Phone no"  required>
        <input type="text"  class="box" name="pf_no" placeholder="Enter PF number" oninput="this.value = this.value.replace(/\s/g, '')"required>  <!-- this regex replaces whitespaces -->
        <input type="text"  class="box" name="pan_no" placeholder="Enter PAN number" oninput="this.value = this.value.replace(/\s/g, '')"required>
        <input type="text"  class="box" name="uan_no" placeholder="Enter UAN number" oninput="this.value = this.value.replace(/\s/g, '')" required>
        <input type="text"  class="box" name="bank_acc" placeholder="Enter Bank Acc no" oninput="this.value = this.value.replace(/\s/g, '')" required>
        <input type="email"  class="box" name="email" placeholder="Enter email id" oninput="this.value = this.value.replace(/\s/g, '')" required>
        <input type="text" name="department" class="box" placeholder="Enter Department" required>
        <input type="text" name="designation" class="box" placeholder="Enter Designation" required>
         <br>
        <label style="margin-left: 27px;display: flex;" for="doj">Date of Joning: </label>
        <input type="date" name="doj"  class="box" required>

        <!-- SUBMIT BUTTON TO PROCESS THE FORM -->
        <input type="submit" class="submit" name="add_employee" value="Add Employee">

    </form>
</section>

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

</script>

</body>
</html>

