<?php
// Enable error reporting for debugging (will be suppressed in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Product.php';

// Check if user is logged in and is admin
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Admin privileges required."));
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(array("message" => "Database connection failed."));
        exit();
    }
    
    $product = new Product($db);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Server error: " . $e->getMessage()));
    exit();
}

$input = file_get_contents("php://input");
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array("message" => "No data received."));
    exit();
}

$data = json_decode($input);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid JSON data: " . json_last_error_msg()));
    exit();
}

if(!empty($data->nama_produk) && !empty($data->harga)) {
    try {
        $product->nama_produk = $data->nama_produk;
        $product->harga = $data->harga;
        $product->foto = isset($data->foto) ? $data->foto : '';
        
        if($product->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Product was created successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create product. Database operation failed."));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Error creating product: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create product. Required fields: nama_produk, harga."));
}
?>