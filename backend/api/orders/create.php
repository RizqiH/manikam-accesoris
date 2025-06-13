<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nama) && !empty($data->jumlah) && !empty($data->alamat)) {
    $order->nama = $data->nama;
    $order->jumlah = $data->jumlah;
    $order->alamat = $data->alamat;
    $order->nama_produk = $data->nama_produk ?? '';
    $order->total_harga = $data->total_harga ?? 0;
    $order->status_pesanan = $data->status_pesanan ?? 'pending';
    $order->catatan = $data->catatan ?? '';
    
    if($order->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Order was created successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create order."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
}
?>