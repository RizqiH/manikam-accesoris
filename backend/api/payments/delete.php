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

if(!empty($data->id)) {
    $payment->id = $data->id;
    
    if($payment->delete()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Payment was deleted.'));
    } else {
        http_response_code(503);
        echo json_encode(array('message' => 'Unable to delete payment.'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Unable to delete payment. Data is incomplete.'));
}
?>