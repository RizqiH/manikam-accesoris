<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../../logs/delete_errors.log');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Only allow DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    error_log("[DELETE] Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed. Only DELETE requests are accepted."));
    exit();
}

include_once '../../config/database.php';
include_once '../../models/Order.php';

// Check if user is logged in and is admin
session_start();
error_log("[DELETE] Session data: " . print_r($_SESSION, true));
error_log("[DELETE] Logged in: " . (isset($_SESSION['logged_in']) ? ($_SESSION['logged_in'] ? 'true' : 'false') : 'not set'));
error_log("[DELETE] Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set'));

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    error_log("[DELETE] Authentication failed - Access denied");
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Admin privileges required."));
    exit();
} else {
    error_log("[DELETE] Authentication successful");
}

$database = new Database();
$db = $database->getConnection();

// Check if database connection is successful
if ($db === null) {
    error_log("[DELETE] Database connection failed");
    http_response_code(500);
    echo json_encode(array("message" => "Database connection failed"));
    exit();
} else {
    error_log("[DELETE] Database connection successful");
}

$order = new Order($db);

// Log input data
$input = file_get_contents("php://input");
error_log("[DELETE] Raw input: " . $input);

$data = json_decode($input);
error_log("[DELETE] Decoded data: " . print_r($data, true));

if(!empty($data->id)) {
    $order->id = $data->id;
    error_log("[DELETE] Order ID to delete: " . $order->id);
    
    if($order->delete()) {
        error_log("[DELETE] Order deleted successfully: ID " . $order->id);
        http_response_code(200);
        echo json_encode(array("message" => "Order was deleted successfully."));
    } else {
        error_log("[DELETE] Failed to delete order: ID " . $order->id);
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete order."));
    }
} else {
    error_log("[DELETE] No ID provided in request data");
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete order. Data is incomplete."));
}
?>