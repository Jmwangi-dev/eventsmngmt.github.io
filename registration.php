<?php
// registration.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $eventId = $_POST['event_id'];

    // Perform validation on the form data (e.g., check if required fields are filled)

    // Process the registration (e.g., insert the data into the database)

    // Assuming you have a database connection, you can insert the registration data into a table
    include 'admin/db_connect.php'; // Include the database connection file

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO registrations (name, email, phone, event_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $phone, $eventId); // Bind the form data to the statement parameters

    // Execute the SQL statement
    if ($stmt->execute()) {
        // Registration successful
        $response = [
            'status' => 'success',
            'message' => 'Registration successful.'
        ];
    } else {
        // Registration failed
        $response = [
            'status' => 'error',
            'message' => 'An error occurred while processing the registration.'
        ];
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo 'Invalid request method.';
}
?>
