<?php
// Database connection
$servername = "mysql-4ddad7-mihirjariwala334-4557.g.aivencloud.com";
$username = "avnadmin";
$password = "AVNS_Xmec4zkLISdSk5ZNMiA";
$dbname = "import-export";
$port = 18193; // Specify the port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
