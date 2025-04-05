<?php
require 'vendor/autoload.php'; // mPDF autoload
require 'config.php';

$mpdf = new \Mpdf\Mpdf([
    'default_font' => 'Arial',
    'format' => 'A4',
]);

$mpdf->SetTitle("Task List");

$result = $conn->query("SELECT * FROM tasks ORDER BY due_date ASC");

$html = '
<style>
    body {
        font-family: Arial, sans-serif;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    thead {
        background-color: #343a40;
        color: white;
    }
    th, td {
        padding: 8px 10px;
        border: 1px solid #ccc;
        font-size: 12px;
        vertical-align: top;
    }
    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>

<h2>Task List</h2>
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Description</th>
    <th>Category</th>
    <th>Priority</th>
    <th>Status</th>
    <th>Due Date</th>
</tr>
</thead>
<tbody>
';

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$row['id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['description']}</td>
        <td>{$row['category']}</td>
        <td>{$row['priority']}</td>
        <td>{$row['status']}</td>
        <td>{$row['due_date']}</td>
    </tr>";
}

$html .= '</tbody></table>';

$mpdf->WriteHTML($html);
$mpdf->Output('tasks.pdf', 'D'); // D = force download
?>
