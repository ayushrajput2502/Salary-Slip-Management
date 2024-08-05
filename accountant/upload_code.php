<?php
// Include necessary configuration and libraries
include '../config/config.php'; // Include the configuration file that connects to the database
require '../vendor/autoload.php'; // Require the autoloader for loading necessary libraries
require_once '../logger.php';
$logger = new Logger('../log.txt');

session_start(); // Start the PHP session to store data across requests

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

// Check if the form was submitted
if (isset($_POST['import'])) {
    $fileName = $_FILES['excel']['name']; // Get the name of the uploaded file
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION); // Get the file extension
    $allowed_ext = ['xls', 'csv', 'xlsx']; // Allowed file extensions
    
    // Code to check whether size of the file is less than 2MB
    if ($_FILES['excel']['size'] > $maxFileSize) {
        $_SESSION['message'] = 'File size exceeds the maximum limit (2MB). Please upload a smaller file.';
        header('location: ../accountant/upload.php');
        exit; // Stop further execution
    }

    // Check if the file extension is allowed
    if (in_array($file_ext, $allowed_ext)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_mime_type = finfo_file($finfo, $_FILES['excel']['tmp_name']);
        if (in_array($file_mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv'])) {
        // Get the temporary file path
        $inputFileNamePath = $_FILES['excel']['tmp_name']; // Get the temporary file path

        // Load the spreadsheet and get the active worksheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Initialize variables for data handling
        $headerMapping = null;  // Mapping of header names to columns
        $isDataStarted = false;  // Flag to indicate the start of data

        $successfulEmpIDs = [];
        $unsuccessfulEmpIDs = [];
     

        // Iterate through each row in the worksheet
        foreach ($worksheet->getRowIterator() as $row) {
            
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue(); // Get cell values and store in an array
            }

            if (!$isDataStarted) {
                // Check if the row contains the column names (headers)
                if (
                    // ADD THE COLUMN NAMES HERE AS PER THE EXCEL SHEET HEADER IN format.php
                    in_array('EMP_ID', $rowData) &&
                    in_array('NAME OF THE EMPLOYEE', $rowData) &&
                    in_array('DESIGNATION', $rowData) &&
                    in_array('BASIC SALARY', $rowData)&&
                    in_array('A.G.P.',$rowData)&&
                    in_array('DAYS WORKED',$rowData)&&
                    in_array('ACTUAL BASIC',$rowData)&&
                    in_array('ACTUAL A.G.P.',$rowData)&&
                    in_array('BASIC + A.G.P.',$rowData)&&
                    in_array('D.A.',$rowData)&&
                    in_array('H.R.A.',$rowData)&&
                    in_array('C.L.A.',$rowData)&&
                    in_array('T.A.',$rowData)&&
                    in_array('Exam Rem',$rowData)&&
                    in_array('SPL PAY',$rowData)&&
                    in_array('GROSS',$rowData)&&
                    in_array('P.F.',$rowData)&&
                    in_array('P.T.',$rowData)&&
                    in_array('I. TAX',$rowData)&&
                    in_array('ADD DED',$rowData)&&
                    in_array('NET SALARY',$rowData)
                ) {
                    $headerMapping = $rowData; // Keep the header row as is
                    $isDataStarted = true;
                }
            } else {
                // If data insertion has started, use the header mapping to access the correct columns
                // CREATE THE VARIABLES AS PER NEED
                // EG $var_name = $rowData[array_search('COLUMN_NAME_IN_EXCEL',$headerMapping)];
                
                $emp_id = $rowData[array_search('EMP_ID', $headerMapping)];
                $name = $rowData[array_search('NAME OF THE EMPLOYEE', $headerMapping)];
                $designation = $rowData[array_search('DESIGNATION', $headerMapping)];
                $basic_sal = $rowData[array_search('BASIC SALARY', $headerMapping)];
                $agp = $rowData[array_search('A.G.P.',$headerMapping)];
                $days_worked = $rowData[array_search('DAYS WORKED',$headerMapping)];
                $actual_basic = $rowData[array_search('ACTUAL BASIC',$headerMapping)];
                $actual_agp = $rowData[array_search('ACTUAL A.G.P.',$headerMapping)];
                $basic_agp = $rowData[array_search('BASIC + A.G.P.',$headerMapping)];
                $da = $rowData[array_search('D.A.',$headerMapping)];
                $hra = $rowData[array_search('H.R.A.',$headerMapping)];
                $cla = $rowData[array_search('C.L.A.',$headerMapping)];
                $ta = $rowData[array_search('T.A.',$headerMapping)];
                $exam_rem = $rowData[array_search('Exam Rem',$headerMapping)];
                $spl_pay = $rowData[array_search('SPL PAY',$headerMapping)];
                $gross = $rowData[array_search('GROSS',$headerMapping)];
                $pf = $rowData[array_search('P.F.',$headerMapping)];
                $pt = $rowData[array_search('P.T.',$headerMapping)];
                $inc_tax = $rowData[array_search('I. TAX',$headerMapping)];
                $add_ded = $rowData[array_search('ADD DED',$headerMapping)];
                $net_sal = $rowData[array_search('NET SALARY',$headerMapping)];

                // Define an array to hold the numeric columns along with their corresponding values
                $numericColumns = [
                    'EMP_ID' => $emp_id,
                    'BASIC SALARY' => $basic_sal,
                    'A.G.P.'=> $agp,
                    'DAYS WORKED' => $days_worked,
                    'ACTUAL BASIC' => $actual_basic,
                    'ACTUAL A.G.P.' => $actual_agp,
                    'BASIC + A.G.P.' => $basic_agp,
                    'D.A.' => $da,
                    'H.R.A.' => $hra,
                    'C.L.A.' => $cla,
                    'T.A.' => $ta,
                    'Exam Rem' => $exam_rem,
                    'SPL PAY' => $spl_pay,
                    'GROSS' => $gross,
                    'P.F.' => $pf,
                    'P.T.' => $pt,
                    'I. TAX' => $inc_tax,
                    'ADD DED' => $add_ded,
                    'NET SALARY' => $net_sal,
                ];

                // Initialize flags to track if all values are numeric and if name and designation are strings
                $allString = true;

                $allNumeric = true;

                // Iterate through the numeric columns
                foreach ($numericColumns as $columnName => $columnValue) {
                    if (!is_numeric($columnValue)) {
                        // Store the employee ID in the unsuccessful list if they are not numeric
                        $unsuccessfulEmpIDs[] = $emp_id;
                        $allNumeric = false;
                        break;
                    }
                }

                // Check if the name and designation are not strings
                if (!is_string($name) || !is_string($designation)) {
                    // if not then store in unsuccessful
                    $unsuccessfulEmpIDs[] = $emp_id;
                    $allString = false;
                }

                if ($allNumeric && $allString) {
                    if ($emp_id != 0) {
                        // Check if the employee exists in the system
                        $check_query = "SELECT COUNT(*) FROM employee_details WHERE emp_id = '$emp_id'";
                        $check_emp_result = mysqli_query($conn, $check_query);
                        $row_check = mysqli_fetch_assoc($check_emp_result);
                        $count = $row_check['COUNT(*)']; // Access the count using the column name

                        
                        if ($count > 0) {
                            $date = $_POST['up-date']; // Assuming the format is "MM/YYYY"
                            $year = date('Y', strtotime($date));
                            $month = date('m', strtotime($date));
                            // HERE BY DEFAULT DAY WILL BE SET TO 01 FOR DATABASE convenience
                            $mysqlDate = $year . '-' . $month . '-01';

                            // Check if there is already a salary record for this emp_id and month
                            $check_sal = "SELECT COUNT(*) FROM salary WHERE emp_id = '$emp_id' AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$mysqlDate', '%Y-%m')";
                            $check_sal_result = mysqli_query($conn, $check_sal);
                            $row_sal = mysqli_fetch_assoc($check_sal_result);
                            $count_sal = $row_sal['COUNT(*)'];
                            if ($count_sal > 0) {
                                // Update the existing salary record for the employee
                                $update_query = "UPDATE salary SET name = '$name', designation = '$designation', basic_salary = '$basic_sal', agp = '$agp', days_worked = '$days_worked', actual_basic = '$actual_basic', actual_agp = '$actual_agp', basic_add_agp = '$basic_agp', da = '$da', hra = '$hra', cla = '$cla', ta = '$ta', exam_rem = '$exam_rem', spl_pay = '$spl_pay', gross = '$gross', pf = '$pf', pt = '$pt', i_tax = '$inc_tax', add_ded = '$add_ded', net_salary = '$net_sal' WHERE emp_id = '$emp_id' AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$mysqlDate', '%Y-%m')";

                                $result = mysqli_query($conn, $update_query);
                                                    
                                $successfulEmpIDs[] = $emp_id;
                                
                            } else {
                                // Employee exists, proceed with insertion
                                $query = "INSERT INTO salary (emp_id, name, designation, basic_salary, agp, days_worked, actual_basic, actual_agp, basic_add_agp, da, hra, cla, ta, exam_rem, spl_pay, gross, pf, pt, i_tax, add_ded, net_salary, date) VALUES ('$emp_id', '$name', '$designation', '$basic_sal', '$agp', '$days_worked', '$actual_basic', '$actual_agp', '$basic_agp', '$da', '$hra', '$cla', '$ta', '$exam_rem', '$spl_pay', '$gross', '$pf', '$pt', '$inc_tax', '$add_ded', '$net_sal', '$mysqlDate')";
                                $result = mysqli_query($conn, $query);

                                $successfulEmpIDs[] = $emp_id;
                            }
                        } else {
                            // Employee doesn't exist, skip the insertion for that row
                            // continue;
                            
                        }
                    } elseif (!empty($emp_id) || $emp_id === '0') {
                        $unsuccessfulEmpIDs[] = $emp_id;
                    }
                }
            }
        }
        
        // Check if there are any error messages
        $_SESSION['successfulEmpIDs'] = $successfulEmpIDs;
        $_SESSION['unsuccessfulEmpIDs'] = $unsuccessfulEmpIDs;
        
        
        
        if ($msg) {
            $logger->log($_SESSION['name'] . ' uploaded the salary details for: ' . date("d-m-Y",strtotime($mysqlDate)));
            $_SESSION['message'] = 'Salary Sheet for '.date("F Y",strtotime($date)).' uploaded successfully. '.'<a href="view.php">Click to view!</a>';
            header('location:../accountant/upload.php');
        } else {
            // Store info in log file that accountant has uploaded the salary sheet
            $logger->log($_SESSION['name'] . ' uploaded salary details for: ' . date("d-m-Y",strtotime($mysqlDate)));
            $_SESSION['message'] = 'Salary Sheet for successful employee ids are uploaded. Please inform to your employees that current month salary sheet is uploaded by send mail.';
            header('location:../accountant/upload.php');
        }
    } else {
        $_SESSION['message'] = 'Invalid file format. Only Excel files and csv files are allowed.';
        header('location:../accountant/upload.php');
    }
} else {
    $_SESSION['message'] = 'Invalid file extension. Only .xls, .csv, and .xlsx files are allowed.';
    header('location:../accountant/upload.php');
}
}

mysqli_close($conn);
?>
