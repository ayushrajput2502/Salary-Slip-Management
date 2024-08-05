<?php
include '../config/config.php';
include '../essentials/header.php';
// include '../essentials/header.php'; 




// CODE TO UPDATE THE ROLE STARTS
if (isset($_POST['update_role'])) {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $update_query = "UPDATE employee_details SET role='$role' WHERE emp_id='$emp_id'";
    $update_result = mysqli_query($conn, $update_query);
    
    if ($update_result) {
        $_SESSION['message'] = 'Role of employee id: ' . $emp_id . ' updated to ' . $role;
        header('location: role.php');
    } else {
        $_SESSION['message'] = 'Could not update';
        header('location: role.php');
    }
}
// CODE TO UPDATE THE ROLE ENDS
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles1.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Document</title>
</head>
<body>
    <!-- CODE TO DISPLAY ANY MESSAGE IF SET STARTS -->
<?php 
    if(isset($_SESSION['message'])){
        echo '<div class="message-box">
        <span>'.$_SESSION['message'].'</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        unset($_SESSION['message']);
    }
    ?>
<!-- CODE TO DISPLAY ANY MESSAGE IF SET ENDS -->
<div class='form-container'>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2 style="margin: 15px;">Filter by id</h2>
        <input type="number" placeholder="Enter an id" name="filt-id">
        <button type="submit" name="submit">Submit</button>
    </form>
</div>

<div class="table-container" style="box-shadow:none; border:none;width:100%;">
    
        <table class="content-table">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Department</td>
                    <td>Role</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $records_per_page = 10;

                // Get the current page from the URL parameter
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    $current_page = $_GET['page'];
                } else {
                    $current_page = 1;
                }
                
                // Calculate the offset for the SQL query
                $offset = ($current_page - 1) * $records_per_page;

                if (isset($_POST['submit'])) {
                    $id = mysqli_real_escape_string($conn, $_POST['filt-id']);
                    $query = "SELECT emp_id, name, department, role FROM employee_details";

                    if (!empty($id)) {
                        $query .= " WHERE emp_id = '$id'";
                    }

                    $result = mysqli_query($conn, $query);
                } else {
                    $query = "SELECT emp_id, name, department, role FROM employee_details LIMIT $records_per_page OFFSET $offset";
                    $result = mysqli_query($conn, $query);
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['emp_id'] == 5252) {
                            continue;
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <!-- Inside the <tbody> loop -->
                        <tr>
                            <td><?php echo $row['emp_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['department']; ?></td>
                            <td>
                                <select name="role" id="role">
                                    <option value="<?php echo $row['role']; ?>"> <?php echo $row['role']; ?></option>
                                    <!-- <option value="HR">HR</option> -->
                                    <option value="Employee">Employee</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="Left">Left</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="emp_id" value="<?php echo $row['emp_id']; ?>">
                                <button name="update_role" class="del" type="submit">Save</button>
                            </td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                 <tr><td colspan='5'>
                    <div class="pagination">
                        <!-- code for next and prev buttons -->
            <?php
            // Check if there are more records to display
            $next_page = $current_page + 1;
            $prev_page = $current_page - 1;
            
            // Display "Previous" button if not on the first page
            if ($current_page > 1) {
                echo "<a class='del'style='margin:5px;' href='{$_SERVER['PHP_SELF']}?page=$prev_page'>Previous</a>";
            }

            // Display "Next" button if there are more records
            if (mysqli_num_rows($result) == $records_per_page) {
                echo "<a class='del' href='{$_SERVER['PHP_SELF']}?page=$next_page'>Next</a>";
            }
            ?>
        </div>
                    </td></tr>
            </tbody>
        </table>
 
</div>
</body>
</html>
