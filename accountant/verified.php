<?php  
require '../fpdf186/fpdf.php';
include '../config/config.php';
require_once '../logger.php';
$logger = new Logger('../log.txt');

session_start();

   
    class PDF extends FPDF{
        private $stampImage = '../image/ver.jpg';
        function header(){
            $this->Image('../image/Untitled-2.png',40,69,130);
            $imagePath = '../image/imagemname.png';
            if (file_exists($imagePath)) {
                $this->Image($imagePath, 5, 0, 200, 37);
                // syntax $this->Line(x-start,y-start,x-end,y-end);
                $this->Line(5, 37, 205, 37);
               
            
            } else {
                // Display alternative text if the image doesn't load
                $this->SetFont('Arial', 'B', 12);
                $this->SetXY(5, 10); // Adjust the position as needed
                $this->Line(5, 18, 205, 18);
                $this->Cell(200, 10, 'ADD YOUR HEADER IMAGE HERE', 5, 0, 'C');
            }

            // Set line color to black and make it bold
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(0.5); // Adjust the line width as needed

            // Draw a line below the header image
        }
        // Footer function to define the footer of the PDF
    function footer(){
        $this->SetY(-20);
        //  SYNTAX - $this->Cell(width, height, text, border, next_line, alignment);
        $this->Cell(0, 10, '(This is a system generated copy)', 0, 0, 'L');
        $this->Cell(0, 10, date('d-m-Y H:i:s'), 0, 0, 'R'); 
        
        // $this->SetY(-25);
        // $this->Cell(0,10,$_SESSION['name'],0,0,'R');
        // $this->SetY(-30);
        // $this->Cell(0,10,'Printed by',0,0,'R');
        // $this->SetY(-35);
        // $this->Cell(0,10,,0,0,'R');
    }

    }
    
$pdf = new PDF();
$pdf->setMargins(5,30,5);

if (isset($_POST['down-sal-button'])) {
    if(isset($_POST['download-salary']) && isset($_POST['sid'])){


    $ids = $_POST['download-salary'];
    $sids = $_POST['sid'];
    $count = count($ids);
    
    
    $pdf->AddPage();
    $tablesPerPage = 3;
    $tablesPrinted = 0;
    
    
    for ($i = 0; $i < $count; $i++) {
        $id = $ids[$i];
        $sid = $sids[$i];
        
        // keep track of how many tables are printed on a single page
        if ($tablesPrinted >= $tablesPerPage) {
            $pdf->AddPage();
            $tablesPrinted = 0; // Reset the counter
        }
        
        // Fetch the data for the current row
        $query = "SELECT * FROM salary as s INNER JOIN employee_details as e ON s.emp_id=e.emp_id  WHERE e.emp_id = '$id' AND s.id='$sid'";
        $result = mysqli_query($conn, $query);
        $row = $result->fetch_assoc();
        
        $pdf->SetFont('Times', '', 9);

        $cellWidth = 38;            

        $earnings = $row['actual_basic'] + $row['actual_agp'] + $row['da'] + $row['hra'] + $row['cla'] + $row['ta'] + $row['spl_pay'] + $row['exam_rem'];
        $deductions = $row['pt'] + $row['pf'] + $row['i_tax'] + $row['add_ded'];

        // Add content for the current row to the PDF
        
        
        $pdf->Ln(10); // used to add line breaks/spacing

            
        // $pdf->Ln(5);
        
        $pdf->SetFillColor(200, 200, 200);
        
        $pdf->SetX(30);
        $pdf->SetFont('Times', 'B', 9); // Set font to bold
        // SYNTAX $pdf->Cell(width, height, text, border, next_line, alignment, fill);
        $pdf->Cell(173, 6, 'Salary statement for the month of  '.date("F Y",strtotime($row['date'])),1,1,0,0);
        $pdf->SetFont('Times', '', 9); // Reset font to regular after the cell
        
        // SYNTAX $pdf->Cell(width, height, text, border, next_line, alignment, fill);
        $pdf->SetX(30);
        $pdf->SetFont('Times', 'B', 9); // Set font to bold
        $pdf->Cell(20, 5, 'EMP ID', 1,0,0,1); 
        $pdf->SetFont('Times', '', 9); // Reset font to regular after the cell
        $pdf->Cell($cellWidth, 5, $row['emp_id'], 1,0,0,1);
        $pdf->SetFont('Times', 'B', 9); // Set font to bold
        $pdf->Cell(50, 5, 'Earnings', 1,0,0,1);
        $pdf->Cell(40, 5, 'Deductions', 1,0,0,1);
        $pdf->Cell(25, 5, 'Net salary', 1,0,0,1);
        $pdf->SetFont('Times', '', 9); // Reset font to regular after the cell
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->SetFont('Times', 'B', 9); // Set font to bold
        $pdf->Cell(20, 5, 'Name', 1);
        $pdf->SetFont('Times', '', 9); // Reset font to regular after the cell
        $pdf->Cell($cellWidth, 5, $row['name'], 1);
        $pdf->Cell(25, 5, 'Basic', 1);
        $pdf->Cell(25, 5, $row['actual_basic'], 1);
        $pdf->Cell(20, 5, 'PT', 1);
        $pdf->Cell(20, 5, $row['pt'], 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->SetFont('Times', 'B', 9); // Set font to bold
        $pdf->Cell(20, 5, 'Designation', 1);
        $pdf->SetFont('Times', '', 9); // Reset font to regular after the cell
        $pdf->Cell($cellWidth, 5, $row['designation'], 1);
        $pdf->Cell(25, 5, 'AGP', 1);
        $pdf->Cell(25, 5, $row['actual_agp'], 1);
        $pdf->Cell(20, 5, 'PF', 1);
        $pdf->Cell(20, 5, $row['pf'], 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->Cell(20, 5, 'DOJ', 1);
        $pdf->Cell($cellWidth, 5, date("d-m-Y",strtotime($row['joining_date'])), 1);
        $pdf->Cell(25, 5, 'DA', 1);
        $pdf->Cell(25, 5, $row['da'], 1);
        $pdf->Cell(20, 5, 'TDS', 1);
        $pdf->Cell(20, 5, $row['i_tax'], 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->Cell(20, 5, 'Pan No', 1);
        $pdf->Cell($cellWidth, 5, $row['pan_no'], 1);
        $pdf->Cell(25, 5, 'HRA', 1);
        $pdf->Cell(25, 5, $row['hra'], 1);
        $pdf->Cell(20, 5, 'Add Ded', 1);
        $pdf->Cell(20, 5, $row['add_ded'], 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->Cell(20, 5, 'PF no', 1);
        $pdf->Cell($cellWidth, 5, $row['pf_no'], 1);
        $pdf->Cell(25, 5, 'CLA', 1);
        $pdf->Cell(25, 5, $row['cla'], 1);
        $pdf->Cell(20, 5,'', 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->Cell(20, 5, 'UAN no', 1);
        $pdf->Cell($cellWidth, 5, $row['uan_no'], 1);
        $pdf->Cell(25, 5, 'TA', 1);
        $pdf->Cell(25, 5, $row['ta'], 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->Cell(20, 5, 'Bank Acc', 1);
        $pdf->Cell($cellWidth, 5, $row['bank_acc'], 1);
        $pdf->Cell(25, 5, 'Spl Pay', 1);
        $pdf->Cell(25, 5, $row['spl_pay'], 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->Cell(20, 5, 'Working days', 1);
        $pdf->Cell($cellWidth, 5, $row['days_worked'], 1);
        $pdf->Cell(25, 5, 'Exam Rem', 1);
        $pdf->Cell(25, 5, $row['exam_rem'], 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell(25, 5, '', 1);
        $pdf->Ln();
        
        $pdf->SetX(30);
        $pdf->SetFont('Times', 'B', 9);
        $pdf->Cell(20, 5, '', 1);
        $pdf->Cell($cellWidth, 5, '', 1);
        $pdf->Cell(25, 5, 'Total', 1);
        $pdf->Cell(25, 5, $earnings, 1);
        $pdf->Cell(20, 5, 'Total', 1);
        $pdf->Cell(20, 5, $deductions, 1);
        $pdf->Cell(25, 5, $earnings - $deductions, 1);
        
        
        $pdf->Ln();
        $tablesPrinted++;
        
    }
    // To store information in log file that accountant has given a verified copy to some employee
    $logger->log($_SESSION['name'] . ' printed salary details of emp: ' . $row['emp_id']);
    
    $pdf->Output('payslip.pdf', 'I');
}else{
    $_SESSION['message'] = "Select atleast 1 data";
    header('location:show.php');
}
}

$conn->close();


?>