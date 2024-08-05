<?php
// Include necessary configuration and header files
include '../config/config.php';
include '../essentials/header.php';

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
<body>
<?php
if (isset($_SESSION['message'])) {
    echo '<div class="message-box">
            <span>' . $_SESSION['message'] . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
          </div>';
    unset($_SESSION['message']);
}
?>

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

<div class="table-container">
    <table class="content-table">
        <thead>
            <tr>
                <!-- COLUMN NAMES OF THE TABLE DISPLAYED -->
                <td>Id</td>
                <td>Name</td>
                <td>Designation</td>
                <td>Basic
                    salary
                </td>
                <td>Agp</td>
                <td>Days 
                    worked</td>
                    <td>Actual
                        basic
                </td>
                <td>Actual
                    AGP
                </td>
                <td>Basic 
                    +
                    Agp
                </td>
                <td>Da</td>
                <td>Hra</td>
                <td>Cla</td>
                <td>Ta</td>
                <td>Exam
                    rem</td>
                <td>Spl
                    pay</td>
                <td>Gross</td>
                <td>Pf</td>
                <td>Pt</td>
                <td>inc
                    tax</td>
                <td>Add
                    ded</td>
                <td>Net
                    salary</td>
                <td>Date</td>
                <!-- <td><button onclick="return confirm('Do you want to Delete?')" type="submit"  name="del-sal-button">Delete</button> <input type="checkbox" onclick='myFun()' style="width:50%;margin-top:5px;" id="selectall" ></td> -->
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php
                if($display){
            if (mysqli_num_rows($rows) > 0) {

                
                while ($row = mysqli_fetch_assoc($rows)) {
                        ?>
                <tr>
                    <!-- ADD ROW NAMES AS PER REQUIRED FROM salary TABLE
                    eg <td> <?php echo $row['column_name_in_salary_table']; ?> </td>
                    
                    -->
                    <td> <?php echo $row['emp_id']; ?> </td>
                    <td> <?php echo $row["name"]; ?> </td>
                    <td> <?php echo $row["designation"]; ?> </td>
                    <td> <?php echo $row["basic_salary"]; ?> </td>
                    <td> <?php echo $row["agp"]; ?> </td>
                    <td> <?php echo $row["days_worked"]; ?> </td>
                    <td> <?php echo $row["actual_basic"]; ?> </td>
                    <td> <?php echo $row["actual_agp"]; ?> </td>
                    <td> <?php echo $row["basic_add_agp"]; ?> </td>
                    <td> <?php echo $row["da"]; ?> </td>
                    <td> <?php echo $row["hra"]; ?> </td>
                    <td> <?php echo $row["cla"]; ?> </td>
                    <td> <?php echo $row["ta"]; ?> </td>
                    <td> <?php echo $row["exam_rem"]; ?> </td>
                    <td> <?php echo $row["spl_pay"]; ?> </td>
                    <td> <?php echo $row["gross"]; ?> </td>
                    <td> <?php echo $row["pf"]; ?> </td>
                    <td> <?php echo $row["pt"]; ?> </td>
                    <td> <?php echo $row["i_tax"]; ?> </td>
                    <td> <?php echo $row["add_ded"]; ?> </td>
                    <td> <?php echo $row["net_salary"]; ?> </td>
                    <td> <?php echo date("F Y",strtotime($row["date"])); ?> </td>
                    <!-- <td> <input onclick="datecheck()" type="checkbox" name="del-salary[]" value="<?php echo $row['emp_id']; ?>">
                        <input type="checkbox" id="sid" name="sid[]" value="<?php echo $row['id']; ?>">
                    </td> -->
                    <td><a href="editsal.php?id=<?php echo $row['emp_id']; ?>&date=<?php echo $row['date']; ?>" class="del">Edit</a></td>
                </tr>
                      <?php }
                }else{
                    echo '<tr><td colspan="23"><p>Please select an ID or specify a date range.</p></td></tr>';
                }
            } else {
                echo '<tr><td colspan="23"><p>Please select an ID or specify a date range.</p></td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function myFun(){
        let allcheckbox = document.getElementById("selectall");
        if(allcheckbox.checked == true){
            let checkboxes = document.getElementsByName("del-salary[]");
            for(var i=0; i<checkboxes.length; i++){
                checkboxes[i].checked = true;
            }
            }else{
                let checkboxes = document.getElementsByName("del-salary[]");
                for(var i=0; i<checkboxes.length; i++){
                    checkboxes[i].checked = false;
            }
        }
        datecheck();
    }
           function datecheck(){
            let datebox =  document.getElementsByName("sid[]");
            let checkboxes = document.getElementsByName("del-salary[]");
            for(let i=0;i<checkboxes.length;i++){
                if(checkboxes[i].checked==true){
                    datebox[i].checked=true;
                }else{
                    datebox[i].checked=false;
                }
            }
        }
</script>
        <?php
        mysqli_close($conn);
        ?>
</div>
</body>
</html>