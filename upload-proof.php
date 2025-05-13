<?php
$response = ["status" => "error", "message" => "Unknown error"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['paymentProof'])) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file = $_FILES['paymentProof'];
    $targetPath = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // File uploaded successfully

        // Prepare email details
        $subject = "New Payment Proof Uploaded";
        $message = "A new payment proof has been uploaded.\n\n";
        $message .= "Order ID: " . $_POST['orderId'] . "\n"; // Capture orderId from POST data
        $message .= "File Name: " . $file['name'] . "\n";
        $message .= "File Type: " . $file['type'] . "\n";
        $message .= "File Size: " . $file['size'] . " bytes\n";
        $message .= "File Path: " . $targetPath . "\n"; // Path where the file is saved

        $headers = "From: info@papertale.shop" . "\r\n" .
                   "Reply-To: info@papertale.shop" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Send email to rimsha@papertale.shop
        if (mail('rimsha@papertale.shop', $subject, $message, $headers)) {
            $response = ["status" => "success", "message" => "File uploaded successfully and email sent."];
        } else {
            $response["message"] = "File uploaded, but failed to send email notification.";
        }
    } else {
        $response["message"] = "Failed to move uploaded file.";
    }
} else {
    $response["message"] = "No file uploaded.";
}

echo json_encode($response);
?>
