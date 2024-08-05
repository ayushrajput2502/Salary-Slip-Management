<?php 
include '../config/config.php'  ;
include '../essentials/header.php';

// include '../essentials/sessions.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $display = True;
    // Retrieve filter parameters from the form
    $id = $_POST['e-id'];
    $fromDate = $_POST['from'];
    $toDate = $_POST['to'];

    // Build the initial SQL query with a base condition
    $sql = "SELECT * FROM salary AS s INNER JOIN employee_details AS e ON s.emp_id = e.emp_id WHERE 1 = 1";

    // Add a condition to filter by Employee ID if it is not empty
    if (!empty($id)) {
        $sql .= " AND e.emp_id = '$id'";
    }

    // Add a condition to filter by Date Range if both 'from' and 'to' are not empty
    if (!empty($fromDate) && !empty($toDate)) {
        $sql .= " AND DATE_FORMAT(s.date, '%Y-%m') BETWEEN '$fromDate' AND '$toDate'";
    }

    // Add an ORDER BY clause to sort the results by Employee ID
    $sql .= " ORDER BY s.emp_id";

    // Execute the SQL query
    $rows = mysqli_query($conn, $sql);
} else {
    // If the form has not been submitted, retrieve the data without any filters
    $display = False;
    // $rows = mysqli_query($conn, "SELECT * FROM salary AS s INNER JOIN employee_details AS e ON s.emp_id = e.emp_id ORDER BY s.emp_id");
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
<style>
  .table-download{
    background-color: #5a1b1b3b;
    color: #ffffff;
    text-align: left;
    padding: 6px 10px;
    font-weight: bolder;
    border-radius: 2px;
  }

    </style>
<body>
<?php
// SHOW ANY MESSAGE IF SET
            if(isset($_SESSION['message'])){
                echo '<div class="message-box">
                <span>'.$_SESSION['message'].'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>';
                unset($_SESSION['message']);
            }
        
?>
<!-- <div class="table-container"> -->

<section class="filter-container">

<div class='form-container'>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2 style="margin: 15px;">Filter by ID or Date Range</h2>
            <input type="number" name="e-id" placeholder="Search By ID" min="1">
            <label for="fromdate">From Month: </label>
            <input type="month"  name="from"  max="<?php echo date('Y-m'); ?>" min="<?php echo date('Y-m', strtotime('-1 year')); ?>">
            <label for="to">To Month: </label>
            <input type="month" name="to"  max="<?php echo date('Y-m'); ?>" min="<?php echo date('Y-m', strtotime('-1 year')); ?>">
            <button type="submit" name="filter-submit">Apply Filters</button>
        </form>
</div>  
        </section>   
        
    <div class="table-container" style="box-shadow:none; border:none;">
        <table class="content-table">
            <form action="verified.php" method="post" target="_blank">
                <thead>
                    <tr>
                        <!-- Column names of the table-->
                        <td>Id</td>
                        <td>Name</td>
                        <td>Designation</td>
                        <td>Days 
                            worked
                        </td>
                        <td>Earnings</td>
                        <td>Deductions</td>
                        <td>Net
                            salary</td>
                            <td>Date</td>
                            <td><button target="_blank" class="table-download"  type="submit"  name="down-sal-button">Download</button> 
                            <input type="checkbox" onclick='myFun()' style="width:50%;margin-top:5px;" id="selectall" >
                            </td>
                            
            </tr>
        </thead>
            <tbody>
                <?php
               
                      
                        if($display){
                        if(mysqli_num_rows($rows)>0){
                            while($row = mysqli_fetch_assoc($rows)){
                                ?>
                <tr>
                    <!-- SPECIFY THE DATA AS PER NEED FROM THE salary TABLE
                    eg- <td> <?php  echo $row['column_name'];  ?></td>
                    -->
                    <td> <?php echo $row['emp_id']; ?> </td>
                    <td> <?php echo $row["name"]; ?> </td>
                    <td> <?php echo $row["designation"]; ?> </td>
                    <td> <?php echo $row["days_worked"]; ?> </td>
                    <td> <?php echo $row['actual_basic']+$row['actual_agp']+$row['da']+$row['hra']+$row['cla']+$row['ta']+$row['spl_pay']+$row['exam_rem']; ?></td>
                    <td> <?php echo $row['pt']+$row['pf']+$row['i_tax']+$row['add_ded'];?></td>
                    <td> <?php echo $row["net_salary"]; ?> </td>
                    <td> <?php echo date("F Y",strtotime($row["date"])); ?> </td>
                    <td> <input onclick="datecheck()" type="checkbox" name="download-salary[]" value="<?php echo $row['emp_id']; ?>">
                    <input type="checkbox" id="sid" name="sid[]" value="<?php echo $row['id']; ?>">
                </td>
                
            </tr>
           
            <?php }
                      }else{
                         echo '<tr><td  colspan="9"><p style="font-size:20px;">Please enter an valid id</p></td></tr>';
                        }
                    }else{
                            echo '<tr><td  colspan="9"><p style="font-size:20px;">Please enter an id</p></td></tr>';

                        } ?>
                  </tbody>
                </form>
                </table>
            
        </div>
            

<script>
     function myFun(){
        let allcheckbox = document.getElementById("selectall");
        if(allcheckbox.checked == true){
            let checkboxes = document.getElementsByName("download-salary[]");
            for(var i=0; i<checkboxes.length; i++){
                checkboxes[i].checked = true;
            }
            }else{
                let checkboxes = document.getElementsByName("download-salary[]");
                for(var i=0; i<checkboxes.length; i++){
                    checkboxes[i].checked = false;
            }
        }
        datecheck();
    }
    function datecheck(){
        let datebox =  document.getElementsByName("sid[]");
        let checkboxes = document.getElementsByName("download-salary[]");
        for(let i=0;i<checkboxes.length;i++){
            if(checkboxes[i].checked==true){
                datebox[i].checked=true;
            }else{
                datebox[i].checked=false;
            }
        }
    }
</script>
</body>
</html>