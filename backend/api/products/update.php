<?php
// Suppress all PHP errors and warnings to prevent HTML output
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
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

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->nama_produk) && !empty($data->harga)) {
    $product->id = $data->id;
    $product->nama_produk = $data->nama_produk;
    $product->harga = $data->harga;
    $product->foto = isset($data->foto) ? $data->foto : '';
    
    if($product->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Product was updated successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update product."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update product. Data is incomplete."));
}
?>