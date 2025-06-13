<?php
// process.php
header('Content-Type: application/json');

// Quick helper
function respond($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// 1. Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.');
}

// 2. Sanitize & validate
$name    = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email   = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Name or email is invalid.');
}

// 3. Append to flat‑file database
$line = date('c') . " | {$name} | {$email} | " . str_replace(["\r", "\n"], ' ', $message) . PHP_EOL;
$file = __DIR__ . '/messages.txt';

if (!file_put_contents($file, $line, FILE_APPEND | LOCK_EX)) {
    respond(false, 'Could not write to messages.txt ‑ check permissions.');
}

// 4. Send welcome email (adjust the From: address and siteName)
$subject = 'Welcome to Blog of Anup!';
$body    = "Hi {$name},\n\nThanks for reaching out. We’ll get back to you shortly.\n\n— Anup Ain";
$headers = "From: Blog of Anup <no-reply@yourdomain.com>\r\nReply-To: no-reply@yourdomain.com";

@mail($email, $subject, $body, $headers); // Silenced with @ to avoid warnings if mail() isn’t configured

// 5. Success response
respond(true, 'Thank you! A welcome email is on its way.');
?>
