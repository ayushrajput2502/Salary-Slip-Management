<?php
include '../config/config.php';
include '../essentials/header.php';
session_start();

// PHP CODE TO INSERT THE DETAILS STARTS
if (isset($_POST['add_employee'])) {
    // SET THE VARIABLES HERE AS PER THE INPUT FIELDS
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $phn_no = mysqli_real_escape_string($conn, $_POST['phn_no']);
    $name = mysqli_real_escape_string($conn, ucwords($_POST['name']));
    $pf_no = mysqli_real_escape_string($conn, $_POST['pf_no']);
    $pan_no = mysqli_real_escape_string($conn, $_POST['pan_no']);
    $uan_no = mysqli_real_escape_string($conn, $_POST['uan_no']);
    $bank_acc = mysqli_real_escape_string($conn, $_POST['bank_acc']);
    $department = mysqli_real_escape_string($conn, ucwords($_POST['department']));
    $gmail = mysqli_real_escape_string($conn, $_POST['email']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $role = mysqli_real_escape_string($conn, "Employee");
    $doj = mysqli_real_escape_string($conn,$_POST['doj']);
    $doj = date("Y-m-d",strtotime($doj));

    // Define an associative array where keys are column names and values are the corresponding input values
    $columnsToCheck = [
        'emp_id' => $id,
        'phone_no' => $phn_no,
        'pf_no' => $pf_no,
        'pan_no' => $pan_no,
        'uan_no' => $uan_no,
        'bank_acc' => $bank_acc,
        'gmail' => $gmail,
    ];

    $errorMessages = [];

foreach ($columnsToCheck as $column => $value) {
    // Create a dynamic SQL query to check distinctness for each column
    $check_query = "SELECT emp_id FROM employee_details WHERE {$column} = '" . mysqli_real_escape_string($conn, $value) . "'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Value is not distinct in this column
        // Specify which data already exists
        $column = ucwords($column);
        $errorMessages[] = "{$column}: {$value} already exists.";
    }
}
if (!empty($errorMessages)) {
    // If there are error messages, set them in the session and redirect
    $_SESSION['message'] = implode("<br>", $errorMessages);
    header('location: emp_add.php');
}  else {
        // All checks passed, proceed with the insertion
        $query = "INSERT INTO employee_details (emp_id, name, phone_no, pf_no, pan_no, uan_no, bank_acc, department, gmail, designation, role,joining_date) VALUES('$id', '$name', '$phn_no', '$pf_no', '$pan_no', '$uan_no', '$bank_acc', '$department', '$gmail', '$designation', '$role','$doj')";
        $result = mysqli_query($conn, $query) or die("Query Failed");

        // Insert the password into the resetpass table
        $query_reset = "INSERT INTO resetpass (emp_id) VALUES('$id')";
        $result_reset = mysqli_query($conn, $query_reset) or die("Query Failed");

        $logger->log($_SESSION['name'] . ' created new employee manually');

        if ($result && $result_reset) {
            // If the employee is successfully created
            $_SESSION['message'] = "New Employee Created Id: <span>{$id}</span>";
            header('location: emp_add.php');
        } else {
            $_SESSION['message'] = "Could not enter the data";
            header('location: emp_add.php');
        }
    }
}
// PHP CODE TO INSERT THE DETAILS ENDS

mysqli_close($conn);
?>
