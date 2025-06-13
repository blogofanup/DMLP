<?php
header('Content-Type: application/json'); // Set header to indicate JSON response

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true); // Decode JSON to associative array

    // Check if data is valid and required fields are present
    if (json_last_error() === JSON_ERROR_NONE && isset($data['name']) && isset($data['email']) && isset($data['message'])) {
        $name = htmlspecialchars(trim($data['name']));
        $email = htmlspecialchars(trim($data['email']));
        $message = htmlspecialchars(trim($data['message']));

        // Basic validation
        if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Please provide valid name and email.';
        } else {
            $leads_file = 'leads.txt';
            $timestamp = date('Y-m-d H:i:s');
            $entry = "Timestamp: {$timestamp}\nName: {$name}\nEmail: {$email}\nMessage: {$message}\n---\n";

            // Append data to the file
            if (file_put_contents($leads_file, $entry, FILE_APPEND | LOCK_EX) !== false) {
                $response['status'] = 'success';
                $response['message'] = 'Lead successfully saved.';
            } else {
                $response['message'] = 'Could not save lead data. File write error.';
            }
        }
    } else {
        $response['message'] = 'Invalid data received.';
    }
}

echo json_encode($response); // Encode response as JSON
?>
