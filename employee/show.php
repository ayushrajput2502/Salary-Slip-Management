    <?php 

    include '../essentials/header.php';
    include '../config/config.php';


     
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/styles1.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <title>Salary Details</title>
        <style>
           
        </style>
    </head>
    <body>
        
    <section class="filter-container">
    <div class='form-container'>
        <div class="instructions">
        <h2><i class="fa-solid fa-triangle-exclamation"></i> Instructions:</h2>
        <ol>
            <li><strong>Select the month:</strong> Choose the specific month for your salary slips.</li>
            <li><strong>For a Single Month:</strong> To get slips for one month, pick the same month for both "From" and "To" fields.</li>
            <li><strong>For Multiple Months:</strong> For multiple months, select different months and years in the "From" and "To" fields.</li>
            <li><strong>Submit Your Request:</strong> After making your choice, click the "Submit" button.</li>
            <li><strong>Download Your Slips:</strong> The salary slips will be listed. You can check individual boxes to download specific slips, or use the checkbox under the "Download" button to download all slips.</li>
        </ol>
        </div>
         
            <!-- <button type="submit" name="date-reset">Reset</button> -->
         
    </div>
    <div class='form-container'>
        <form style="padding:42px 20px;" action="" method="post">
            <h3 style="margin-bottom:10px; ">Filter by date</h3>
            <label for="fromdate">From Month: </label>
            <input type="month" name="from" max="<?php echo date('Y-m'); ?>" min="<?php echo date('Y-m', strtotime('-1 year')); ?>">
            <label for="to">To Month: </label>
            <input type="month" name="to" max="<?php echo date('Y-m'); ?>" min="<?php echo date('Y-m', strtotime('-1 year')); ?>">
            <button  type="submit" name="date-submit">Submit</button>
            <!-- <button type="submit" name="date-reset">Reset</button> -->
            </form>
    </div>
    </section>


    <!-- TABLE CODE TO DISPLAY ENTIRE DETAILS STARTS-->

    
        <div class="table-container" style="box-shadow:none; border:none; margin: -40px 20px;">

        
            <table class="content-table">
            <form action="download.php" method="post" target="_blank">
                <thead>
                        <tr>
                            <!-- COLUMN NAMES OF THE TABLE -->
                            <td>No</td>
                            <td>Days 
                                worked
                            </td>
                            <td>Earnings</td>
                            <td>Deductions</td>
                            <td>Net Salary</td>
                            <td id="picker"> Date</td>
                            <td><button class="table-download"  type="submit"  name="down-sal-button">Download</button> <input type="checkbox" onclick='myFun()' style="width:50%;margin-top:5px;" id="selectall" ></td>
                        </tr>
                </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $display = True;
                        if(isset($_POST['date-submit'])){
                            // CODE TO SELECT DATE INTERVAL 
                            $from = $_POST['from'];
                            $from = strtotime($from);
                            $from = date("Y-m",$from);
                            $to = strtotime($_POST['to']);
                            $to = date("Y-m",$to);

                            if($from<=$to){
                                $query = "SELECT * FROM salary WHERE emp_id = {$_SESSION['id']} AND DATE_FORMAT(date,'%Y-%m') BETWEEN '$from' AND '$to'";
                                $result = mysqli_query($conn,$query);
                            }else{
                                $display = False;
                                echo '<tr><td colspan = "7">Please select an appropriate date</td></tr>';
                            }
                           
                            }else{
                                $display = False;
                                
                                echo '<tr><td colspan = "7">Please enter an date</td></tr>';
                        }
                        if($display){
                            if(mysqli_num_rows($result)>0){

                                while($row = mysqli_fetch_assoc($result)){
                            $earnings = $row['actual_basic']+$row['actual_agp']+$row['da']+$row['hra']+$row['cla']+$row['ta']+$row['spl_pay']+$row['exam_rem'];
                            $deductions = $row['pt']+$row['pf']+$row['i_tax']+$row['add_ded'];
                            
                            ?>
                        <tr>
                            <!-- GET THE ROW DATA HERE 
                        eg: <td> <?php echo $row['column_name_in_salary_table']; ?> </td> 
                        -->
                            <td><?php echo $i; ?></td>
                            <td> <?php echo $row["days_worked"]; ?> </td>
                            <td> <?php echo $earnings; ?> </td>
                            <td> <?php echo $deductions; ?> </td>
                            <td> <?php echo $earnings-$deductions ?> </td>
                            <td> <?php echo date("F Y",strtotime($row["date"])); ?> </td>
                            <td> <input onclick="datecheck()" type="checkbox" name="download-salary[]" value="<?php echo $row['emp_id']; ?>">
                        <input type="checkbox" id="sid" name="sid[]" value="<?php echo $row['id']; ?>">
                    </td>
                        </tr>
                        
                        <?php $i = $i+1;
                        }
                        }else{
                            echo '<tr><td colspan="7">No Data Found</td></tr>';                      
                        }
                    }
                        ?>
                    </tbody>
                    </form>
            </table>
      
            </div>
    <!-- TABLE CODE TO DISPLAY ENTIRE DETAILS ENDS-->



    <?php 
    mysqli_close($conn);
    ?>
    <script> 
    // SCRIPT FOR SELECTING OR UNSELECTING CHECKBOXES
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