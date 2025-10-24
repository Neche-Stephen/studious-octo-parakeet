<?php
// assets/mail.php — Pinnacle Health Contact Form Handler
header('Content-Type: application/json');

// Allow only POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

// Sanitize input helper
function clean($data) {
    return trim(strip_tags(str_replace(array("\r", "\n"), ' ', $data)));
}

// Collect form data
$name         = clean($_POST["name"] ?? '');
$email        = filter_var(trim($_POST["email"] ?? ''), FILTER_VALIDATE_EMAIL);
$phone        = clean($_POST["phone"] ?? '');
$organization = clean($_POST["organization"] ?? '');
$service      = clean($_POST["service"] ?? '');
$message      = trim($_POST["message"] ?? '');

// Basic validation
if (!$name || !$email || !$phone || !$service || !$message) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Please complete all required fields."]);
    exit;
}

// Recipient (your company email)
$recipient = "Pinnaclehealthenvirons@gmail.com";

// Email subject
$subject = "New Inquiry from $name via Pinnacle Website";

// Build email body
$body = "You have received a new inquiry from your website contact form.\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Organization: $organization\n";
$body .= "Service of Interest: $service\n";
$body .= "Message:\n$message\n\n";
$body .= "Time: " . date("Y-m-d H:i:s") . "\n";
$body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";

// Email headers
$headers  = "From: Pinnacle Website <no-reply@" . ($_SERVER['SERVER_NAME'] ?? 'example.com') . ">\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($recipient, $subject, $body, $headers)) {
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Thank you! Your message has been sent successfully."]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Mail function failed. Please contact Pinnaclehealthenvirons@gmail.com directly."
    ]);
}
?>
