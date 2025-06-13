<?php
require_once 'backend/config/database.php';

echo "Testing database connection...\n";

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "Connection successful!";
} else {
    echo "Connection failed!";
}
?>