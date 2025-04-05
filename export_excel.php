<?php
require 'vendor/autoload.php'; // or your path
require 'config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Tasks');

// Header
$headers = ['ID', 'Title', 'Description', 'Category', 'Priority', 'Status', 'Due Date'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Data
$result = $conn->query("SELECT * FROM tasks ORDER BY due_date ASC");
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowNum", $row['id']);
    $sheet->setCellValue("B$rowNum", $row['title']);
    $sheet->setCellValue("C$rowNum", $row['description']);
    $sheet->setCellValue("D$rowNum", $row['category']);
    $sheet->setCellValue("E$rowNum", $row['priority']);
    $sheet->setCellValue("F$rowNum", $row['status']);
    $sheet->setCellValue("G$rowNum", $row['due_date']);
    $rowNum++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'tasks.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer->save('php://output');
exit;
?>
