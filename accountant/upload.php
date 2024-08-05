
<?php
// Include necessary configuration and header files
include '../config/config.php'; // Include the configuration file that connects to the database
include '../essentials/header.php'; // Include header related essentials

// include '../essentials/sessions.php';

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

if (isset($_SESSION['successfulEmpIDs']) || isset($_SESSION['unsuccessfulEmpIDs'])) {
    echo '<div class="error-container">
    <i class="fas fa-times" onclick="confirmAndRemove(this);"></i>
        <h2>Details of uploaded data</h2>
        <div class="scrollable-container">
            <div class="error-textarea">';
            
            if (isset($_SESSION['unsuccessfulEmpIDs'])) {
                echo "<br><h3><span>Unsuccessfully Uploaded Employee IDs:</span></h3>"."<br>";
                foreach ($_SESSION['unsuccessfulEmpIDs'] as $emp_id) {
                    echo "<span>Unsuccessful Emp id: " . $emp_id . "</span><br>";
                }
            }
    if (isset($_SESSION['successfulEmpIDs'])) {
        echo "<h3>Successfully Uploaded Employee IDs:</h3>"."<br>";
        foreach ($_SESSION['successfulEmpIDs'] as $emp_id) {
            echo "Successful Emp id: " . $emp_id . "<br>";
        }
    }


   
    echo '</div>
        </div>
    </div>';
}

unset($_SESSION['successfulEmpIDs']);
unset($_SESSION['unsuccessfulEmpIDs']);
?>



<!-- The following HTML code creates a form for uploading a file and specifying a month -->
<div style="height: 100vh; margin-top:-120px;" class='form-container'>
    <!-- Form for uploading a file and specifying a month -->
    <form action="upload_code.php" method="post" enctype="multipart/form-data" onsubmit="return confirmImport()">
        <p>Specify Month</p> <!-- Prompt the user to specify a month -->
        <input type="month" name="up-date" max="<?php echo date('Y-m'); ?>" required> <!-- Input field for specifying the month -->

        <!-- Input field for uploading a file (Excel file) -->
        <input style='margin-top: 50px;' type="file" name="excel" required >
        
        <!-- Checkbox to confirm import -->
        <br>
        <label  for="confirmImport"><b>Confirm Import</label>
        <input type="checkbox" id="confirmImport" required>
        
        <!-- Button to submit the form and trigger the import process -->
        <button type="submit" name="import">Import</button>

        <!-- Button to download the predefined format for the Excel file -->
        <a href="format.php"><button type="button">Download Format</button></a>


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