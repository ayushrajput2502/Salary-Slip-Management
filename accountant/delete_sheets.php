<?php
// session_start();
// include '../config/config.php';

// if(isset($_POST['del-sal-button'])){
//     $ids = $_POST['del-salary'];
//     $sids = $_POST['sid'];
//     if($ids>1){
//         $count = count($ids);  
//         for ($i = 0; $i < $count; $i++) {
//             $id = $ids[$i];
//             $sid = $sids[$i];
//             $query = "DELETE FROM salary WHERE emp_id = '$id' AND id='$sid'";
//             $result = mysqli_query($conn,$query);
//         }
//         if($result){
//             $_SESSION['message'] = 'Data deleted sucessfully';
//             header('location:view.php');
//         }else{
//             $_SESSION['message'] = 'Data not deleted';
//             header('location:view.php');
//         }
//     }else{
//         $_SESSION['message'] = 'Please select atleast 1 data';
//         header('location:view.php');
//     }
// }
?>