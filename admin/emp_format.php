<?php
require '../vendor/autoload.php'; // Include PhpSpreadsheet library

// Create a new PhpSpreadsheet instance
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Syntax for Date Format (1st row)
$syntax = array('', '', '', 'max-10', '', '', '', '', '', '', 'yyyy-mm-dd');

// Set syntax in the first row of the spreadsheet
$columnIndex = 1;
foreach ($syntax as $format) {
    $sheet->setCellValueByColumnAndRow($columnIndex, 1, $format);
    $columnIndex++;
}

// Column headers name (2nd row)
$headers = array('EMP_ID', 'NAME OF THE EMPLOYEE', 'GMAIL', 'PHONE NO', 'PAN NO', 'PF NO', 'UAN NO', 'BANK ACCOUNT', 'DEPARTMENT', 'DESIGNATION', 'DOJ');

// Set headers in the second row of the spreadsheet
$columnIndex = 1;
foreach ($headers as $header) {
    $sheet->setCellValueByColumnAndRow($columnIndex, 2, $header);
    $columnIndex++;
}

// Create a writer for the spreadsheet
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename=details_format.xlsx');

// Write the spreadsheet content to the output
$writer->save('php://output');
exit;
?>
