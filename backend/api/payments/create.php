<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Payment.php';

// Check if user is logged in and is admin
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Admin privileges required."));
    exit();
}

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nama_produk) && !empty($data->pembayaran)) {
    $payment->nama_produk = $data->nama_produk;
    $payment->pembayaran = $data->pembayaran;
    $payment->tgl_bayar = isset($data->tgl_bayar) ? $data->tgl_bayar : date('Y-m-d');
    $payment->keterangan = isset($data->keterangan) ? $data->keterangan : 'LUNAS';
    
    if($payment->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Payment was created successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create payment."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create payment. Data is incomplete."));
}
?>