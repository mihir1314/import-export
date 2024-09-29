<?php
include '../auth/connect.php';
include '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF; // Ensure TCPDF is correctly used

// Get form data
$dataAmount = $_POST['dataAmount'];
$fileFormat = $_POST['fileFormat'];

// Determine query based on amount of data
$limit = $dataAmount == 'all' ? '' : 'LIMIT ' . intval($dataAmount);
$sql = "SELECT * FROM random_table $limit";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Prepare data for export
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$conn->close();

// Export based on file format
switch ($fileFormat) {
    case 'csv':
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export.csv"');
        $output = fopen('php://output', 'w');
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        break;

    case 'excel':
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export.xlsx"');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if (!empty($data)) {
            $sheet->fromArray(array_keys($data[0]), NULL, 'A1');
            $sheet->fromArray($data, NULL, 'A2');
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        break;

    case 'pdf':
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="export.pdf"');

        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $html = '<table border="1">';
        if (!empty($data)) {
            $html .= '<tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr>';
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        $pdf->writeHTML($html);
        $pdf->Output('php://output', 'D');
        break;

    case 'json':
        header('Content-Type: application/json');
        header('Content-Disposition: attachment;filename="export.json"');
        echo json_encode($data);
        break;

    case 'xml':
        header('Content-Type: text/xml');
        header('Content-Disposition: attachment;filename="export.xml"');
        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($data, function($value, $key) use ($xml) {
            $xml->addChild($key, $value);
        });
        echo $xml->asXML();
        break;

    case 'html':
        header('Content-Type: text/html');
        header('Content-Disposition: attachment;filename="export.html"');
        echo '<table border="1">';
        if (!empty($data)) {
            echo '<tr>';
            foreach (array_keys($data[0]) as $header) {
                echo '<th>' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr>';
            foreach ($data as $row) {
                echo '<tr>';
                foreach ($row as $value) {
                    echo '<td>' . htmlspecialchars($value) . '</td>';
                }
                echo '</tr>';
            }
        }
        echo '</table>';
        break;

    case 'txt':
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment;filename="export.txt"');
        $output = fopen('php://output', 'w');
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]), "\t"); // Use tab for delimiter
            foreach ($data as $row) {
                fputcsv($output, $row, "\t");
            }
        }
        fclose($output);
        break;

    default:
        die("Unsupported file format.");
}
?>
