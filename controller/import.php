<?php
include '../vendor/autoload.php'; // Include Composer's autoloader

use PhpOffice\PhpSpreadsheet\IOFactory; // Import PhpSpreadsheet classes
use PhpOffice\PhpSpreadsheet\Spreadsheet;

include '../auth/connect.php'; // Include database connection

// Initialize success flag and message
$success = false;
$message = '';

// Define column mappings for your database table
$columnMappings = [
    'id' => 'id',
    'col1' => 'col1',
    'col2' => 'col2',
    'col3' => 'col3',
    'col4' => 'col4',
    'col5' => 'col5',
    // Add more mappings if needed
];

// Function to check for duplicates based on ID
function isDuplicate($id, $conn)
{
    $id = $conn->real_escape_string($id);
    $query = "SELECT COUNT(*) as count FROM random_table WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result && $result->fetch_assoc()['count'] > 0) {
        return true;
    }
    return false;
}

// Function to insert data into the database
function insertData($data, $conn, $columnMappings)
{
    $columns = array_values($columnMappings); // Extract column names for SQL query
    $query = "INSERT INTO random_table (" . implode(", ", $columns) . ") VALUES ";
    $values = [];
    foreach ($data as $row) {
        $escapedRow = array_map([$conn, 'real_escape_string'], $row);
        $values[] = "('" . implode("','", $escapedRow) . "')";
    }
    if (!empty($values)) {
        $query .= implode(',', $values);
        return $conn->query($query);
    }
    return false;
}

// Check if file is uploaded and handle errors
if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == UPLOAD_ERR_OK) {
    // Check file size (limit to 10MB as an example)
    if ($_FILES['fileUpload']['size'] > 10485760) {
        $message = 'File size exceeds the maximum allowed size of 10MB.';
        header('Location: ../index.php?status=error&message=' . urlencode($message));
        exit;
    }

    $fileType = $_POST['fileType'];
    $fileTmpName = $_FILES['fileUpload']['tmp_name'];
    $fileName = $_FILES['fileUpload']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Supported file formats
    $validFormats = ['csv', 'excel', 'html', 'txt', 'xml','json'];
    if (!in_array($fileType, $validFormats)) {
        $message = 'Unsupported file format.';
        header('Location: ../index.php?status=error&message=' . urlencode($message));
        exit;
    }

    if ($fileType === 'excel' && !in_array($fileExtension, ['xlsx', 'xls'])) {
        $message = 'Invalid Excel file format. Please upload .xlsx or .xls file.';
        header('Location: ../index.php?status=error&message=' . urlencode($message));
        exit;
    }

    $data = [];
    $headers = [];
    try {
        switch ($fileType) {

            // Handle CSV import
            case 'csv':
                if (($handle = fopen($fileTmpName, "r")) !== FALSE) {
                    $headers = fgetcsv($handle, 1000, ","); // Read headers
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (count($row) === count($headers)) {
                            $data[] = array_combine($headers, $row); // Combine headers with row data
                        }
                    }
                    fclose($handle);
                }
                break;

            // Handle Excel import
            case 'excel':
                try {
                    $spreadsheet = IOFactory::load($fileTmpName); // Load the spreadsheet
                    $worksheet = $spreadsheet->getActiveSheet(); // Get the active sheet

                    $headerRow = $worksheet->getRowIterator(1)->current(); // Get header row
                    $cellIterator = $headerRow->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $headers = array_map(function ($cell) {
                        return $cell->getValue();
                    }, iterator_to_array($cellIterator));

                    foreach ($worksheet->getRowIterator(2) as $row) { // Start from the second row
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        $rowData = array_map(function ($cell) {
                            return $cell->getValue();
                        }, iterator_to_array($cellIterator));

                        if (count($rowData) === count($headers)) {
                            $rowData = array_combine($headers, $rowData);

                            // Validate the 'id' field
                            if (isset($rowData['id'])) {
                                $rowData['id'] = trim($rowData['id']); // Remove any whitespace
                                if ($rowData['id'] === '' || !is_numeric($rowData['id'])) {
                                    $rowData['id'] = null; // Set to null or handle it as needed
                                    // You might want to skip this row or log the issue
                                }
                            }

                            // Insert $rowData into the database here, if valid
                            if ($rowData['id'] !== null) {
                                $data[] = $rowData;
                            } else {
                                // Handle rows with invalid or missing 'id' values
                                // You can log them or skip the insertion
                            }
                        }
                    }
                } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                    $message = 'Error reading Excel file: ' . $e->getMessage();
                    error_log('Excel Error: ' . $e->getMessage());
                    header('Location: ../index.php?status=error&message=' . urlencode($message));
                    exit;
                } catch (Exception $e) {
                    $message = 'Error processing file: ' . $e->getMessage();
                    error_log('General Error: ' . $e->getMessage());
                    header('Location: ../index.php?status=error&message=' . urlencode($message));
                    exit;
                }
                break;

            // Handle HTML import
            case 'html':
                // Handle HTML import using DOMDocument
                $html = file_get_contents($fileTmpName);
                $dom = new DOMDocument();
                @$dom->loadHTML($html);
                $table = $dom->getElementsByTagName('table')->item(0);
                $rows = $table->getElementsByTagName('tr');
                $headerRow = $rows->item(0);
                $headerCells = $headerRow->getElementsByTagName('th');
                foreach ($headerCells as $cell) {
                    $headers[] = $cell->textContent;
                }
                foreach ($rows as $index => $row) {
                    if ($index === 0)
                        continue; // Skip header row
                    $cols = $row->getElementsByTagName('td');
                    $rowData = [];
                    foreach ($cols as $col) {
                        $rowData[] = $col->textContent;
                    }
                    if (count($rowData) === count($headers)) {
                        $data[] = array_combine($headers, $rowData);
                    }
                }
                break;

            // Handle TEXT import
            case 'txt':
                // Handle TXT import (tab-separated values)
                $textData = file_get_contents($fileTmpName);
                $lines = explode("\n", $textData);
                $headers = explode("\t", array_shift($lines)); // First line as headers
                foreach ($lines as $line) {
                    $rowData = explode("\t", $line); // Split by tab
                    if (count($rowData) === count($headers)) {
                        $data[] = array_combine($headers, $rowData);
                    }
                }
                break;

            // Handle XML import
            case 'xml':
                try {
                    // Load the XML file
                    $xml = simplexml_load_file($fileTmpName);
                    if ($xml === false) {
                        throw new Exception("Failed to load XML file.");
                    }

                    // Convert XML to an associative array
                    $json = json_encode($xml);
                    $rowData = json_decode($json, true);

                    // Validate the 'id' field
                    if (isset($rowData['id'])) {
                        $rowData['id'] = trim($rowData['id']); // Remove any whitespace
                        if ($rowData['id'] === '' || !is_numeric($rowData['id'])) {
                            $rowData['id'] = null; // Set to null or handle it as needed
                        }
                    }

                    // Insert $rowData into the database if valid
                    if ($rowData['id'] !== null) {
                        $data[] = $rowData;
                    } else {
                        // Handle cases where 'id' is invalid or missing
                        // You can log the issue or skip the insertion
                        throw new Exception("Invalid ID value in the XML file.");
                    }
                } catch (Exception $e) {
                    $message = 'Error processing XML file: ' . $e->getMessage();
                    error_log('XML Error: ' . $e->getMessage());
                    header('Location: ../index.php?status=error&message=' . urlencode($message));
                    exit;
                }
                break;

                // Handle JSON import
                case 'json':
                    try {
                        // Read the JSON file content
                        $jsonContent = file_get_contents($fileTmpName);
                        if ($jsonContent === false) {
                            throw new Exception("Failed to read JSON file.");
                        }
                
                        // Decode JSON data into an associative array
                        $dataArray = json_decode($jsonContent, true);
                        if ($dataArray === null || !is_array($dataArray)) {
                            throw new Exception("Invalid JSON format.");
                        }
                
                        // Process each item in the JSON array
                        foreach ($dataArray as $rowData) {
                            // Validate the 'id' field
                            if (isset($rowData['id'])) {
                                $rowData['id'] = trim($rowData['id']); // Remove any whitespace
                                if ($rowData['id'] === '' || !is_numeric($rowData['id'])) {
                                    $rowData['id'] = null; // Set to null or handle it as needed
                                }
                            }
                
                            // Insert $rowData into the database if valid
                            if ($rowData['id'] !== null) {
                                $data[] = $rowData;
                            } else {
                                // Handle cases where 'id' is invalid or missing
                                // Log the issue or skip the insertion
                                error_log("Invalid ID value for row: " . json_encode($rowData));
                            }
                        }
                
                        // If there's valid data, proceed with database insertion
                        if (!empty($data)) {
                            // Insert into the database
                            // Your database insertion code goes here
                        } else {
                            throw new Exception("No valid data found in the JSON file.");
                        }
                
                    } catch (Exception $e) {
                        $message = 'Error processing JSON file: ' . $e->getMessage();
                        error_log('JSON Error: ' . $e->getMessage());
                        header('Location: ../index.php?status=error&message=' . urlencode($message));
                        exit;
                    }
                    break;
                
                
        }

        // Map headers to database columns and insert data into the database
        $mappedData = [];
        foreach ($data as $row) {
            $mappedRow = [];
            foreach ($columnMappings as $header => $dbColumn) {
                $mappedRow[$dbColumn] = $row[$header] ?? null;
            }
            if (!isDuplicate($mappedRow['id'], $conn)) { // Assuming 'id' is the ID column
                $mappedData[] = $mappedRow;
            }
        }

        if (insertData($mappedData, $conn, $columnMappings)) {
            $success = true;
            $message = 'File imported successfully.';
        } else {
            $message = 'Error inserting data: ' . $conn->error;
        }

    } catch (Exception $e) {
        $message = 'Error processing file: ' . $e->getMessage();
        error_log('Processing Error: ' . $e->getMessage());
    }

} else {
    $message = 'No file uploaded or upload error.';
}

// Close the database connection
$conn->close();

// Redirect with status and message
header('Location: ../index.php?status=' . ($success ? 'success' : 'error') . '&message=' . urlencode($message));
exit;
