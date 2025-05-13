<?php
header("Content-Type: application/json");

// Read the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (empty($data['name']) || empty($data['address']) || empty($data['payment']) || empty($data['items']) || empty($data['total'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

// Allowed payment methods
$allowedPayments = ['COD', 'JazzCash', 'Easypaisa'];
if (!in_array($data['payment'], $allowedPayments)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid payment method.']);
    exit;
}

// Database connection details
$host = "localhost";
$dbname = "papertal_wp696";
$username = "papertal_W-O";
$password = "Pass@34545.";

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check for database connection errors
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Prepare and sanitize inputs
$name = $data['name'];
$email = $data['email'] ?? ''; // If email is not provided, set as empty
$phone = $data['phone'] ?? ''; // If phone is not provided, set as empty
$address = $data['address'];
$payment = $data['payment'];

// If payment is JazzCash or Easypaisa, validate the paymentNumber
$paymentNumber = ($payment === 'JazzCash' || $payment === 'Easypaisa') && !empty($data['paymentNumber']) ? $data['paymentNumber'] : '';

// Convert the items array into a JSON string
$items = $data['items'];
$itemsJson = json_encode($items);

// Ensure total is a valid number
$total = floatval($data['total']);

// Prepare the SQL query to insert the order into the database
$stmt = $conn->prepare("INSERT INTO orders (name, email, phone, address, payment_method, payment_number, items, total, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

// Bind the parameters for the SQL query
$stmt->bind_param("sssssssd", $name, $email, $phone, $address, $payment, $paymentNumber, $itemsJson, $total);

// Execute the SQL query
if ($stmt->execute()) {
    // If the query is successful, return a success response
    echo json_encode(['status' => 'success']);
} else {
    // If the query fails, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Failed to place order.']);
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>
