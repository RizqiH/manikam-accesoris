<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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

$stmt = $payment->read();
$num = $stmt->rowCount();

if($num > 0) {
    $payments_arr = array();
    $payments_arr["records"] = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        $payment_item = array(
            "id" => $id,
            "nama_produk" => $nama_produk,
            "pembayaran" => $pembayaran,
            "tgl_bayar" => $tgl_bayar,
            "keterangan" => $keterangan
        );
        
        array_push($payments_arr["records"], $payment_item);
    }
    
    http_response_code(200);
    echo json_encode($payments_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No payments found."));
}
?>