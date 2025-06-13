<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Order.php';

// Check if user is logged in and is admin
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Admin privileges required."));
    exit();
}

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

$stmt = $order->read();
$num = $stmt->rowCount();

if($num > 0) {
    $orders_arr = array();
    $orders_arr["records"] = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        $order_item = array(
            "id" => $id,
            "nama" => $nama,
            "jumlah" => $jumlah,
            "alamat" => $alamat,
            "nama_produk" => $nama_produk ?? '',
            "total_harga" => $total_harga ?? 0
        );
        
        array_push($orders_arr["records"], $order_item);
    }
    
    http_response_code(200);
    echo json_encode($orders_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No orders found."));
}
?>