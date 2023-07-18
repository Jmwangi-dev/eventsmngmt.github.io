<?php
include 'admin/db_connect.php';

$events = []; // Initialize $events variable as an empty array

// Fetch all events from the database
$qry = $conn->query("SELECT e.*, v.venue FROM events e INNER JOIN venue v ON v.id = e.venue_id");
while ($row = $qry->fetch_assoc()) {
    $events[] = $row; // Add each event to the $events array
}

$error_message = null; // Initialize $error_message variable

?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
    <style type="text/css">
        /* Styles for event details and registration form */
        .container {
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Styles for payment form */
        .payment-container {
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .payment-container h2 {
            margin-bottom: 10px;
        }

        .payment-container form {
            margin-top: 20px;
        }

        .payment-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .payment-container input[type="text"],
        .payment-container input[type="email"],
        .payment-container input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        .payment-container button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
        }

        .payment-container button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Custom CSS */
        /* Add your custom CSS here */
        .custom-class {
            color: blue;
            font-weight: bold;
        }

        /* Event image style */
        .event-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Event Selection Section -->
    <section>
        <div class="container">
            <h1>Select an Event</h1>
            <?php if (!empty($events)): ?>
                <ul>
                    <?php foreach ($events as $event): ?>
                        <li>
                            <a href="javascript:void(0);" onclick="fetchEventDetails(<?php echo $event['id']; ?>)">
                                <?php echo $event['event']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No events available.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Event Details Section -->
    <section>
        <div id="eventDetails" class="container">
            <?php if ($event): ?>
                <div class="row">
                    <div class="col-md-12">
    <h2>Selected Event: <?php echo isset($event['event']) ? $event['event'] : ''; ?></h2>
    <?php if (!empty($event['image'])): ?>
        <img class="event-image" src="<?php echo $event['image']; ?>" alt="<?php echo $event['event']; ?>">
    <?php endif; ?>
    <p><strong>Venue:</strong> <?php echo isset($event['venue']) ? $event['venue'] : ''; ?></p>
    <p><strong>Date:</strong> <?php echo isset($event['date']) ? $event['date'] : ''; ?></p>
    <p><strong>Time:</strong> <?php echo isset($event['time']) ? $event['time'] : ''; ?></p>
    <!-- Display additional event details as needed -->
    <!-- ... -->
</div>

                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-12">
                        <p><?php echo isset($error_message) ? $error_message : 'Event details not available.'; ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Registration Form Section -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Register</h2>
                    <form id="registrationForm" onsubmit="submitRegistrationForm(event)">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>

                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" required>

                        <input type="hidden" name="event_id" id="selectedEventId" value="">
                        <button type="submit">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Form Section -->
    <section>
        <div class="payment-container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Make Payment</h2>
                    <form id="paymentForm" onsubmit="submitPaymentForm(event)">
                        <label for="payment_option">Select Payment Option:</label>
                        <select id="payment_option" name="payment_option" onchange="showPaymentDetails(this.value)">
                            <option value="">Select Payment Option</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="bank">Bank Payment</option>
                            <option value="card">Card Payment</option>
                        </select>

                        <div id="mpesaDetails" style="display: none;">
                            <label for="mpesa_number">M-Pesa Number</label>
                            <input type="text" id="mpesa_number" name="mpesa_number">
                        </div>

                        <div id="bankDetails" style="display: none;">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" id="bank_name" name="bank_name">

                            <label for="account_number">Account Number</label>
                            <input type="text" id="account_number" name="account_number">
                        </div>

                        <div id="cardDetails" style="display: none;">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" name="card_number">

                            <label for="card_holder">Card Holder Name</label>
                            <input type="text" id="card_holder" name="card_holder">

                            <label for="expiry_date">Expiry Date</label>
                            <input type="text" id="expiry_date" name="expiry_date">

                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv">
                        </div>

                        <input type="hidden" name="event_id" id="selectedEventId" value="">
                        <label for="event_name">Event Name:</label>
                        <input type="text" id="event_name" name="event_name" readonly>

                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" readonly>

                        <button type="submit">Make Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CSS -->
    <style type="text/css">
        /* Add your custom CSS here */
        /* Custom CSS */
        .custom-class {
            color: blue;
            font-weight: bold;
        }

        /* Event image style */
        .event-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // // Function to fetch and display event details using AJAX
function fetchEventDetails(eventId) {
    // Send AJAX request to fetch event details
    $.ajax({
        url: 'admin/events.php',
        type: 'GET',
        data: {id: eventId},
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var event = response.event;
                // Update event details in the DOM
                $('#eventDetails').html('<div class="row"><div class="col-md-12"><h2>Selected Event: ' + event.event + '</h2>' +
                    (event.image ? '<img class="event-image" src="' + event.image + '" alt="' + event.event + '">' : '') +
                    '<p><strong>Venue:</strong> ' + event.venue + '</p><p><strong>Date:</strong> ' + event.date + '</p>' +
                    '<p><strong>Time:</strong> ' + event.time + '</p></div></div>');
                // Update selected event ID in the registration and payment forms
                $('#selectedEventId').val(event.id);
                // Update event name and amount in the payment form
                $('#event_name').val(event.event);
                $('#amount').val(event.amount);
            } else {
                alert(response.message); // Display error message
            }
        },
        error: function() {
            alert('An error occurred while fetching event details.'); // Display error message
        }
    });
}


        // Function to submit the registration form using AJAX
        function submitRegistrationForm(event) {
            event.preventDefault(); // Prevent the default form submission behavior

            // Get the form data
            var formData = $('#registrationForm').serialize();

            // Send AJAX request to submit the form data
            $.ajax({
                url: 'registration.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Registration successful.'); // Display success message
                        $('#registrationForm')[0].reset(); // Reset the form
                    } else {
                        alert(response.message); // Display error message
                    }
                },
                error: function() {
                    alert('An error occurred while processing the registration.'); // Display error message
                }
            });
        }

        // Function to submit the payment form using AJAX
        function submitPaymentForm(event) {
            event.preventDefault(); // Prevent the default form submission behavior

            // Get the form data
            var formData = $('#paymentForm').serialize();

            // Send AJAX request to submit the form data
            $.ajax({
                url: 'payment.php',
                type: 'POST',
                data: formData, 
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Payment successful.'); // Display success message
                        $('#paymentForm')[0].reset(); // Reset the form
                    } else {
                        alert(response.message); // Display error message
                    }
                },
                error: function() {
                    alert('An error occurred while processing the payment.'); // Display error message
                }
            });
        }

        // Function to show payment details based on the selected payment option
        function showPaymentDetails(paymentOption) {
            // Hide all payment details sections
            $('#mpesaDetails').hide();
            $('#bankDetails').hide();
            $('#cardDetails').hide();

            // Show the selected payment details section
            if (paymentOption === 'mpesa') {
                $('#mpesaDetails').show();
            } else if (paymentOption === 'bank') {
                $('#bankDetails').show();
            } else if (paymentOption === 'card') {
                $('#cardDetails').show();
            }
        }
    </script>
</body>
</html>
