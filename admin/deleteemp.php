<?php
session_start();
include '../config/config.php';

if(isset($_POST['del-emp-button'])){
    $ids = $_POST['del-emp'];
    if($ids>1){
        $count = count($ids);  
        for ($i = 0; $i < $count; $i++) {
            $id = $ids[$i];
            $query = "DELETE FROM employee_details WHERE emp_id = '$id' ";
            $result = mysqli_query($conn,$query);
            $reset_query = "DELETE FROM resetpass WHERE emp_id = '$id' ";
            $reset_result = mysqli_query($conn,$reset_query);
        }
        if($result){
            $_SESSION['message'] = 'Selected employees deleted sucessfully';
            header('location:show_emp.php');
        }else{
            $_SESSION['message'] = 'Could not delete';
            header('location:show_emp.php');
        }
    }else{
        $_SESSION['message'] = 'Please select atleast 1 data';
        header('location:show_emp.php');
    }
}
?>