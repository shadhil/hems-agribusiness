<?php
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name = htmlspecialchars(trim($_POST["name"]));
//     $email = htmlspecialchars(trim($_POST["mail"]));
//     $subject = htmlspecialchars(trim($_POST["subject"]));
//     $message = htmlspecialchars(trim($_POST["message"]));

//     // Email destination
//     $to = "shadhil90@gmail.com"; // <-- Change to your actual email address

//     // Email content
//     $body = "You have received a new message from your website contact form:\n\n";
//     $body .= "Name: $name\n";
//     $body .= "Email: $email\n";
//     $body .= "Subject: $subject\n";
//     $body .= "Message:\n$message\n";

//     $headers = "From: $email\r\n";
//     $headers .= "Reply-To: $email\r\n";

//     // Send email
//     if (mail($to, $subject, $body, $headers)) {
//         echo "<script>alert('Message sent successfully!'); window.location = 'thank-you.html';</script>";
//     } else {
//         echo "<script>alert('Message could not be sent. Try again later.'); history.back();</script>";
//     }
// } else {
//     echo "Invalid request.";
// }

// header('Content-Type: application/json');

// // Only allow POST requests
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405);
//     echo json_encode(['success' => false, 'message' => 'Method not allowed']);
//     exit;
// }

// // Get JSON input
// $input = json_decode(file_get_contents('php://input'), true);

// // Validate input
// if (empty($input['name']) || empty($input['email']) || empty($input['subject']) || empty($input['message'])) {
//     http_response_code(400);
//     echo json_encode(['success' => false, 'message' => 'All fields are required']);
//     exit;
// }

// // Sanitize input
// $name = htmlspecialchars(trim($_POST["name"]));
// $email = htmlspecialchars(trim($_POST["mail"]));
// $subject = htmlspecialchars(trim($_POST["subject"]));
// $message = htmlspecialchars(trim($_POST["message"]));
// // $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
// // $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
// // $subject = filter_var($input['subject'], FILTER_SANITIZE_STRING);
// // $message = filter_var($input['message'], FILTER_SANITIZE_STRING);

// // Validate email
// if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
//     http_response_code(400);
//     echo json_encode(['success' => false, 'message' => 'Invalid email address']);
//     exit;
// }

// // Email configuration
// $to = 'info@hemsagribusiness.co.tz'; // Change this to your receiving email
// // $email_subject = "New Contact Form Submission: $subject";
// // $email_body = "You have received a new message from your website contact form.\n\n" .
// //     "Name: $name\n" .
// //     "Email: $email\n" .
// //     "Subject: $subject\n" .
// //     "Message:\n$message";
// // $headers = "From: $email\n";
// // $headers .= "Reply-To: $email\n";
// $e_subject = 'You have been contacted by ' . $name . '.';

// $e_body = "You have been contacted by $name. Their additional message is as follows." . PHP_EOL . PHP_EOL;
// $e_content = "\"$message\"" . PHP_EOL . PHP_EOL;
// $e_reply = "You can contact $name via email, $email";

// $msg = wordwrap($e_body . $e_content . $e_reply, 70);

// $headers = "From: $name <" . $email . ">" . PHP_EOL;
// $headers .= "Reply-To: $email" . PHP_EOL;
// $headers .= "MIME-Version: 1.0" . PHP_EOL;
// $headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
// $headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

// // Send email
// if (mail($to, $e_subject, $msg, $headers)) {
//     echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
// } else {
//     http_response_code(500);
//     echo json_encode(['success' => false, 'message' => 'Failed to send message']);
// }

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$jsonInput = file_get_contents('php://input');
if ($jsonInput === false) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$input = json_decode($jsonInput, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON format']);
    exit;
}

// Validate input
if (empty($input['name']) || empty($input['email']) || empty($input['subject']) || empty($input['message'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Sanitize input (PHP 8.2 compatible)
$name = htmlspecialchars($input['name'], ENT_QUOTES, 'UTF-8');
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($input['subject'], ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($input['message'], ENT_QUOTES, 'UTF-8');

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Email configuration
$to = 'info@hemsagribusiness.co.tz'; // Change this to your receiving email
$email_subject = "New Web Contact: $subject";
$email_body = "You have received a new message from your website contact form.\n\n" .
    "Name: $name\n" .
    "Email: $email\n" .
    "Subject: $subject\n" .
    "Message:\n$message";

// Proper headers for PHP 8.2 (RFC-compliant)
$headers = [
    'From' => "$name <$email>",
    'Reply-To' => $email,
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
    'X-Mailer' => 'PHP/' . phpversion()
];

// Convert headers to string
$headersString = '';
foreach ($headers as $key => $value) {
    $headersString .= "$key: $value\r\n";
}

// Send email with error suppression
$mailSent = @mail($to, $email_subject, $email_body, $headersString);

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} else {
    // Log the error for debugging
    error_log('Mail send failed for: ' . $email);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}
