<?php
//..............
// THIS FILE CONTAINS THE CODE FOR ADDING NEW EMPLOYEES THROUGH EXCEL
//..............


include '../config/config.php';
include '../essentials/header.php';
require '../vendor/autoload.php';
session_start();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

if (isset($_POST['upload'])) {
    $fileName = $_FILES['excel']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    // SET THE EXTENSIONS
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if ($_FILES['excel']['size'] > $maxFileSize) {
        $_SESSION['message'] = 'File size exceeds the maximum limit (2MB). Please upload a smaller file.';
        header('location:../admin/emp_add_excel.php');
        exit; // Stop further execution
    }

    if (in_array($file_ext, $allowed_ext)) {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_mime_type = finfo_file($finfo, $_FILES['excel']['tmp_name']);
        if (in_array($file_mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv'])) {
        
        $inputFileNamePath = $_FILES['excel']['tmp_name'];

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $headerMapping = null;
        $isDataStarted = false;

        $successfulEmpIDs = [];
        $unsuccessfulEmpIDs = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            if (!$isDataStarted) {
                // Check if the row contains the column names (headers)
                if (
                    // ADD THE COLUMN NAMES HERE AS PER THE EXCEL SHEET HEADER 
                    in_array('EMP_ID', $rowData) &&
                    in_array('NAME OF THE EMPLOYEE', $rowData) &&
                    in_array('GMAIL', $rowData) &&
                    in_array('PHONE NO', $rowData) &&
                    in_array('PAN NO', $rowData) &&
                    in_array('PF NO', $rowData) &&
                    in_array('UAN NO', $rowData) &&
                    in_array('BANK ACCOUNT', $rowData) &&
                    in_array('DEPARTMENT', $rowData) &&
                    in_array('DESIGNATION', $rowData)&& 
                    in_array('DOJ', $rowData) 
                ) {
                    $headerMapping = $rowData; // Keep the header row as is
                    $isDataStarted = true;
                }
            } else {
                // If data insertion has started, use the header mapping to access the correct columns
                // CREATE VARIABLES AS PER THE DATA INSERTION NEED
                $emp_id = $rowData[array_search('EMP_ID', $headerMapping)];
                $name = $rowData[array_search('NAME OF THE EMPLOYEE', $headerMapping)];
                $gmail = $rowData[array_search('GMAIL', $headerMapping)];
                $phone_no = $rowData[array_search('PHONE NO', $headerMapping)];
                $pan_no = $rowData[array_search('PAN NO', $headerMapping)];
                $pf_no = $rowData[array_search('PF NO', $headerMapping)];
                $uan_no = $rowData[array_search('UAN NO', $headerMapping)];
                $bank_acc = $rowData[array_search('BANK ACCOUNT', $headerMapping)];
                $department = $rowData[array_search('DEPARTMENT', $headerMapping)];
                $designation = $rowData[array_search('DESIGNATION', $headerMapping)];
                $DOJ = $rowData[array_search('DOJ', $headerMapping)];
                $DOJ = ($DOJ - 25569) * 86400;
                $DOJ = date("Y-m-d", $DOJ);
                
                $role = "Employee";
                // $password = $emp_id . '@Apsit';
                // $password = sha1($password);


                $columnsToCheck = [
                    'emp_id' => $emp_id,
                    'phone_no' => $phone_no,
                    'pf_no' => $pf_no,
                    'pan_no' => $pan_no,
                    'uan_no' => $uan_no,
                    'bank_acc' => $bank_acc,
                    'gmail' => $gmail,
                ];

                $allString = true;

                foreach ($columnsToCheck as $column => $value) {
                    // Loop through the columns and values you want to check for distinctness
                
                    // Create a dynamic SQL query to check if a value already exists in the database for the current column
                    $check_query = "SELECT emp_id FROM employee_details WHERE {$column} = '" . mysqli_real_escape_string($conn, $value) . "'";
                    
                    // Execute the SQL query
                    $check_result = mysqli_query($conn, $check_query);
                    
                    // Check if there are any matching rows in the database
                    if (mysqli_num_rows($check_result) > 0) {
                        // If there are matching rows, it means the value is not distinct in this column
                
                        // Set a flag to indicate that not all values are distinct
                        $allString = false;
                
                        // Add the current employee ID ($emp_id) to the list of unsuccessful employee IDs
                        $unsuccessfulEmpIDs[] = $emp_id;
                
                        // Exit the loop, as there's no need to continue checking other columns
                        break;
                    }
                }
                

                // if employee doesnt already exists in the table procced with insertion if it does then skip that insertion
                if ($allString) {
                    
                    // ADD THE COLUMN NAME AND VARIABLE NAMES IN THE QUERY
                    $query = "INSERT INTO employee_details (emp_id, name, phone_no, pf_no, pan_no, uan_no, bank_acc, department, gmail, designation,role,joining_date) VALUES('{$emp_id}', '{$name}', '$phone_no', '{$pf_no}', '{$pan_no}', '{$uan_no}', '{$bank_acc}', '{$department}', '{$gmail}', '{$designation}','{$role}','{$DOJ}')";
                    $result = mysqli_query($conn, $query) or die("Query Failed");
                    
                    $pass_query = "INSERT INTO resetpass (emp_id) VALUES('$emp_id')";
                    $pass_result = mysqli_query($conn, $pass_query);
                    $successfulEmpIDs[] = $emp_id;
                   
                    $msg = true;
                }
            }
        }

        $_SESSION['successfulEmpIDs'] = $successfulEmpIDs;
        $_SESSION['unsuccessfulEmpIDs'] = $unsuccessfulEmpIDs;

        if ($msg) {
            // IF ANY 1 EMPLOYEE IS ADDED DISPLAY THIS MESSAGE
            $logger->log($_SESSION['name'] . ' created multiple employees using excel ');
            $_SESSION['message'] = 'New employees added successfully. '.'<a href="show_emp.php">Click to view!</a>';
            header('location:../admin/emp_add_excel.php');
        } else {
            // if all the employees already exists display this message
            $_SESSION['message'] = 'Make sure the column names match the given format or employee ID already exists.';
            header('location:../admin/emp_add_excel.php');
        }
    }else{
        $_SESSION['message'] = 'Invalid file format. Only Excel files and csv files are allowed.';
        header('location:../admin/emp_add_excel.php');
    }
}
     else {
        // if file format is other than csv,xls,xlxs 
        $_SESSION['message'] = 'Invalid file';
        header('location:../admin/emp_add_excel.php');
    }
}

mysqli_close($conn);
?>
