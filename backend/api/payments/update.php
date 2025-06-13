<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

session_start();

// Check if user is logged in and is admin
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(array('message' => 'Unauthorized. Admin access required.'));
    exit();
}

include_once '../../config/database.php';
include_once '../../models/Payment.php';

$database = new Database();
$db = $database->getConnection();

$payment = new Payment($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->nama_produk) && !empty($data->pembayaran) && !empty($data->tgl_bayar) && !empty($data->keterangan)) {
    $payment->id = $data->id;
    $payment->nama_produk = $data->nama_produk;
    $payment->pembayaran = $data->pembayaran;
    $payment->tgl_bayar = $data->tgl_bayar;
    $payment->keterangan = $data->keterangan;
    
    if($payment->update()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Payment was updated.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Unable to update payment.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Unable to update payment. Data is incomplete.'));
}
?>