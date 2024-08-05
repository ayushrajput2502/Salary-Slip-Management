    <?php  
include '../essentials/sessions.php';

require_once '../logger.php';
$logger = new Logger('../log.txt');

// REASSIGN THE TIME WHEN USER PERFORMS ANY ACTIVITY
$_SESSION['last_activity'] = time();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Paysleep_accountant</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <!-- custom css file link  -->
        <link rel="stylesheet" href="css/styles1.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
<body>
<header class="header">
        <section class="flex">
            <p class="logo"> PayEngine</p>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
            </div>
        </section>
    </header>

    <!-- Sidebar section starts -->
    <div class="side-bar">
        <div class="close-side-bar">
            <i id="close-btn" class="fas fa-times"></i>
         </div>
        <div class="profile">
            <span><?php echo 'Id: '.$_SESSION['id']; ?></span>
            <h3><?php echo ucwords($_SESSION['name']); ?></h3>
            <span><?php echo  ucwords($_SESSION['type']); ?></span><br>
            <span id="clock">00:00:00</span>
        </div>
        <nav class="navbar">
        <?php if($_SESSION['type']=='accountant'){ 
            // echo '<a href="../accountant/accountant.php" ><i class="fa-solid fa-house"></i><span> Home</span></a>';
            echo '<a href="../accountant/sendmail.php"> <i class="fa fa-envelope" aria-hidden="true"></i><span>Send Mail</span></a>';
            echo '<a href="../employee/eprofile.php"> <i class="fa-solid fa-user"></i><span>My Profile</span></a>';
            echo '<a href="../employee/show.php"><i class="fas fa-receipt"></i><span>My Payslip</span></a>';
            echo '<a href="../accountant/upload.php" ><i class="fa fa-upload" aria-hidden="true"></i><span> Upload <?span></a>';
            echo '<a href="../accountant/view.php" ><i class="fas fa-eye"></i></i><span> View Uploads </span></a>';
            echo '<a href="../accountant/verified_code.php" ><i class="fa-solid fa-sheet-plastic"></i><span>Print Payslip</span></a>';
            echo '<a href="../admin/show_emp.php"><i class="fas fa-users"></i><span>Add Employee</span></a>';
        }
        // elseif($_SESSION['type']=='HR'){
            //     echo '<a href="../admin/admin.php"><i class="fa-solid fa-house"></i><span> Home</span></a>';
            //     echo '<a href="../employee/show.php"><i class="fas fa-receipt"></i><span> Payslip</span></a>';
            //     echo '<a href="../admin/show_emp.php"><i class="fas fa-users"></i><span> Employee</span></a>';
            // echo '<a href="show_acc.php"><i class="fas fa-users"></i><span> Accountant</span></a>';
            // }
            elseif($_SESSION['type']=='employee'){
            echo '<a href="../employee/currentmonth.php"><i class="fa-solid fa-calendar-days"></i><span>Current Month Payslip</span></a>';
             echo '<a href="eprofile.php"> <i class="fa-solid fa-user"></i><span>My Profile</span></a>';
             echo '<a href="show.php"><i class="fas fa-receipt"></i><span>My Payslip</span></a>';
            }elseif($_SESSION['type']=='SA'){
             echo '<a href="role.php"> <i class="fa-solid fa-user"></i><span> Role</span></a>';
         }
         ?>
            <!-- <a href="../employee/profile.html"><i class="fa-solid fa-user"></i><span> Profile</span></a>
            <a href="../employee/payslip.html"><i class="fas fa-receipt"></i><span> Payslip</span></a> -->
            <a href="../logout.php"  onclick="return confirm('Do you want to logout?')">    <i class="fa fa-sign-out" aria-hidden="true"></i><span> Logout</span></a>
        </nav>
    </div>
    <!-- Sidebar section ENDS -->


    <script>
       let sideBar = document.querySelector('.side-bar');
let body = document.body;
document.querySelector('#menu-btn').onclick = () =>{
   sideBar.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelector('#close-btn').onclick = () =>{
   sideBar.classList.remove('active');
   body.classList.remove('active');
}

function updateClock(){
            const clock = document.getElementById("clock");
            const time = new Date();
            let hours = time.getHours();
            let amPm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            hours = hours.toString().padStart(2,0)
            const min = time.getMinutes().toString().padStart(2,0);
            const second = time.getSeconds().toString().padStart(2,0);
            const timeString = `${hours}:${min}:${second} ${amPm}`;
            clock.textContent = timeString;
        }

        setInterval(updateClock,1000);
    </script>
</body>
</html>    