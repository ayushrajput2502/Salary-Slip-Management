<?php
include '../config/config.php';
include '../essentials/header.php';

// Check if the user is logged in, otherwise redirect to the index page
// include '../essentials/sessions.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles1.css">
    <title>Employee list</title>
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

    <?php 
   
    ?>
   <section class="details">
       <div class="form-container">
           <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="number" name="id" class="box" placeholder="Search By Id" >
            <input type="submit" class="box"  id="filter-submit" name="submit">
            <input type="submit" class="box" value="Reset" id="filter-submit" name="reset">
        </form>
    </div>
    <h1 class="heading">Employee list</h1>
    <div class="table-container" style="box-shadow:none;border:none;">

        <table class="content-table" style="width:100%;">
        <!-- SPECIFIED A FORM FOR DELETEING WHERE ON CLICKING DELETE IT WILL BE SUBMITTED TO deleteemp.php WHERE DELETION OF EMPLOYEES WILL BE PERFORMED -->
        <!-- REOMOVE THE COMMENTS  FOR FORM TO  DELETE -->
            <!-- <form action="deleteemp.php" method="post"> -->
            <thead>
                <tr style="position:sticky;top:0;">         
                    <td style="text-align:left;" colspan="5"><a id="employee-add" href="emp_add.php">Add Employee +</a></td>
                </tr>
                <tr style="position:sticky;top:0px;">
                    <td style="text-align:left;" colspan="5"><a id="employee-add" href="emp_add_excel.php">Add Employees using excel+</a></td>
                </tr>
                <tr style="position:sticky;top: 0px; border-top:1px solid grey;">
                <!-- COLUMN NAMES OF THE TABLE -->
                        <td>Emp Id</td>
                        <td>Name</td>
                        <td>Department</td>
                        <td>Action</td>
                        <!-- <td><button onclick="return confirm('Do you want to Delete?')" type="submit"  name="del-emp-button">Delete</button> <input type="checkbox" onclick='myFun()' style="width:100%;margin-top:5px;" id="selectall" ></td> -->
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
            
            if(isset($_POST['submit'])){
                $id = $_POST['id'];
                $query = "SELECT * FROM employee_details WHERE emp_id ='{$id}' ";
                $result = mysqli_query($conn,$query);
            }else{
                $query ="SELECT * FROM employee_details  LIMIT $records_per_page OFFSET $offset"
                ;
                $result = mysqli_query($conn,$query);
            }

                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        // To not show the super admin details
                        if($row['emp_id']==5252){
                            continue;
                        }
                        ?>
                    <tr>
                        <!-- HIDDEN INPUT IS USED IN ORDER TO GET THE EMPLOYEEID OF WHOM WE WANT TO UPDATE THE ROLE OF -->
                        <input type="hidden" name="emp_id" value="<?php echo $row['emp_id'];?>">

                        <!-- GET THE ROW DATA HERE 
                        eg: <td> <?php echo $row['column_name_in_employee_details_table']; ?> </td> 
                        -->
                        <td><?php echo $row['emp_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['department']; ?></td>
                        <td><a href="editemp.php?id=<?php echo $row['emp_id']; ?>" class="del">Edit</a></td>
                        <!-- <td><input onclick="datecheck()" type="checkbox" name="del-emp[]" value="<?php echo $row['emp_id']; ?>">
                        </td> -->
                    </tr>
                    <?php
                    }}else{
                        echo '<tr><td colspan="5"><p>Please enter an valid id </p></td></tr>';
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
                    </form>
            </table>

                   
</div>
</section> 
<?php 
mysqli_close($conn);
?>
   <script>
    function myFun(){
        let allcheckbox = document.getElementById("selectall");
        if(allcheckbox.checked == true){
            let checkboxes = document.getElementsByName("del-emp[]");
            for(var i=0; i<checkboxes.length; i++){
                checkboxes[i].checked = true;
            }
            }else{
                let checkboxes = document.getElementsByName("del-emp[]");
                for(var i=0; i<checkboxes.length; i++){
                    checkboxes[i].checked = false;
            }
        }
        
    }
           
   </script>
</body>
</html>