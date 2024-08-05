<?php

require '../vendor/autoload.php'; // Include PhpSpreadsheet library

// Create a new PhpSpreadsheet instance
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Define column headers
// ADD COLUMN NAMES AS PER REQUIRED AND ADD THE COLUMN NAMES IN upload_code.php AS PER MENTIONED HERE 
$headers = array('EMP_ID', 'NAME OF THE EMPLOYEE', 'DESIGNATION', 'BASIC SALARY', 'A.G.P.', 'DAYS WORKED', 'ACTUAL BASIC', 'ACTUAL A.G.P.', 'BASIC + A.G.P.', 'D.A.', 'H.R.A.', 'C.L.A.', 'T.A.', 'Exam Rem', 'SPL PAY', 'GROSS', 'P.F.', 'P.T.', 'I. TAX', 'ADD DED', 'NET SALARY');

// Set headers in the first row of the spreadsheet
$columnIndex = 1;
foreach ($headers as $header) {
    $sheet->setCellValueByColumnAndRow($columnIndex, 1, $header);
    $columnIndex++;
}

// Create a writer for the spreadsheet
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename=format.xlsx');

// Write the spreadsheet content to the output
$writer->save('php://output');
exit;
?>
