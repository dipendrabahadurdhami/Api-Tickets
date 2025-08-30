<?php include('../includes/header.php'); ?>
<?php
session_start();  // Start the session to access session variables
include '../config.php';
$showModal = false;  // Flag to determine if modal should be shown

// Check if the user is logged in and has the role of "staff"

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $bus_name = $_POST['bus_name'];
    $departure_time = $_POST['departure_time'];
    $cost = $_POST['cost'];
    $available_date = $_POST['available_date'];
    $phone_number = $_POST['phone_number'];

    // AC and charger options
    $ac_option = $_POST['ac_option'];  // AC or Non-AC
    $charger_option = $_POST['charger_option'];  // Charger or No Charger

    // Handle photo upload
    $photo = $_FILES['bus_photo']['name'];
    $photo_tmp = $_FILES['bus_photo']['tmp_name'];
    $photo_folder = 'uploads/' . $photo;
    move_uploaded_file($photo_tmp, $photo_folder);

    // Get the user_id from the session
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {

        // Prepare the insert query for the bus
        $stmt = $conn->prepare("INSERT INTO buses (start_location, end_location, bus_name, departure_time, cost, available_date, phone_number, ac_option, charger_option, bus_photo, user_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $start_location, $end_location, $bus_name, $departure_time, $cost, $available_date, $phone_number, $ac_option, $charger_option, $photo_folder, $user_id);

        // Execute the query and check if insertion was successful
        if ($stmt->execute()) {
            // Get the newly inserted bus ID
            $bus_id = $stmt->insert_id;

            // Save the bus ID in the session
            $_SESSION['bus_id'] = $bus_id;

            // Now insert all seats for this bus and set status to 'available'
            $seat_query = "INSERT INTO seats (bus_id, seat_number, status) VALUES ";
            $seats = [];
            $seat_numbers = [
                'A1',
                'A2',
                'B1',
                'B2',
                'A3',
                'A4',
                'B3',
                'B4',
                'A5',
                'A6',
                'B5',
                'B6',
                'A7',
                'A8',
                'B7',
                'B8',
                'A9',
                'A10',
                'B9',
                'B10',
                'A11',
                'A12',
                'B11',
                'B12',
                'A13',
                'A14',
                'B13',
                'B14',
                'A15',
                'A16',
                'AB',
                'B15',
                'B16'
            ];

            // Build the values part of the query
            foreach ($seat_numbers as $seat_number) {
                $seats[] = "($bus_id, '$seat_number', 'available')";
            }

            // Join the seat values and finalize the query
            $seat_query .= implode(", ", $seats);

            // Execute the seat insertion query
            if ($conn->query($seat_query)) {
                $showModal = true;  // Set the flag to true to show modal
            } else {
                echo "Error inserting seats: " . $conn->error;
            }
        } else {
            echo "Error adding bus: " . $stmt->error;
        }

        $stmt->close();

    } else {
        // Redirect to login page if the user is not logged in
        header("Location: login.php");  // Change 'login.php' to your actual login page
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bus</title>
    <style>
        /* Styling for the entire page */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        /* Style for the form container */
        .container1 {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        /* Headings */
        h2 {
            text-align: center;
        }

        /* Label styling */
        label {
            display: block;
            margin: 10px 0 5px;
        }

        /* Input field styling */
        input[type="text"],
        input[type="number"],
        input[type="time"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Styling for radio buttons */
        input[type="radio"] {
            margin-right: 5px;
            cursor: pointer;
        }

        /* Displaying options horizontally */
        .radio-options {
            display: flex;
            justify-content: flex-start;
            /* Align options to the left */
            gap: 20px;
            /* Space between options */
            margin-bottom: 10px;
        }

        /* Styling for labels of radio buttons */
        .radio-options label {
            font-size: 14px;
            font-weight: normal;
            color: #333;
            cursor: pointer;
        }

        /* Highlight checked radio button with green */
        input[type="radio"]:checked+label {
            font-weight: bold;
            color: #28a745;
        }

        /* Change color on hover */
        input[type="radio"]:hover+label {
            color: #007bff;
        }

        /* Styling for bus image and options side by side */
        .bus-options {
            display: flex;
            align-items: center;
            /* Vertically center the content */
            justify-content: space-between;
            /* Space between image and radio options */
            margin-bottom: 20px;
        }

        /* Bus image styling */
        .bus-image {
            width: 150px;
            /* Adjust size as needed */
            height: auto;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Styling for the submit button */
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        /* Modal styling */
        .modal {
            display:
                <?php echo $showModal ? 'block' : 'none'; ?>
            ;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: auto;
            text-align: center;
            border-radius: 8px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }
    </style>
</head>

<body>
    <div class="container1">
        <h2>Add New Bus</h2>
        <form method="POST" action="add_bus.php" enctype="multipart/form-data">
            <label for="start_location">Start Location:</label>
            <input type="text" id="start_location" name="start_location"
                onkeyup="fetchSuggestions(this.value, 'start_suggestions')" autocomplete="off" required>
            <div id="start_suggestions" class="suggestions"></div>

            <label for="end_location">End Location:</label>
            <input type="text" id="end_location" name="end_location"
                onkeyup="fetchSuggestions(this.value, 'end_suggestions')" autocomplete="off" required>
            <div id="end_suggestions" class="suggestions"></div>

            <label for="bus_name">Bus Name:</label>
            <input type="text" id="bus_name" name="bus_name" required>

            <label for="departure_time">Departure Time:</label>
            <input type="time" id="departure_time" name="departure_time" required>

            <label for="cost">Cost:</label>
            <input type="number" id="cost" name="cost" required>

            <label for="available_date">Available Date:</label>
            <input type="date" id="available_date" name="available_date" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <!-- AC Option -->
            <div class="radio-options">
                <input type="radio" id="ac_yes" name="ac_option" value="AC" required>
                <label for="ac_yes">AC</label>
                <input type="radio" id="ac_no" name="ac_option" value="Non-AC">
                <label for="ac_no">Non-AC</label>
            </div>

            <!-- Charger Option -->
            <div class="radio-options">
                <input type="radio" id="charger_yes" name="charger_option" value="Charger" required>
                <label for="charger_yes">Charger</label>
                <input type="radio" id="charger_no" name="charger_option" value="No Charger">
                <label for="charger_no">No Charger</label>
            </div>


            <!-- Photo Upload -->
            <label for="bus_photo">Bus Photo:</label>
            <input type="file" id="bus_photo" name="bus_photo" accept="image/*" required onchange="previewImage(event)">
            <br>
            <img id="photoPreview" src="" alt="Photo Preview" style="max-width: 200px; display: none;">


            <button type="submit">Add Bus</button>
        </form>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <p>Bus added successfully!!!</p>
        </div>
    </div>

    <script>
        function fetchSuggestions(input, suggestionsDiv) {
            if (input.length === 0) {
                document.getElementById(suggestionsDiv).innerHTML = ""; // Clear suggestions
                return;
            }

            // Make AJAX request to fetch suggestions
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `fetch_locations.php?query=${encodeURIComponent(input)}`, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const suggestions = JSON.parse(xhr.responseText);
                    let suggestionHTML = "";

                    // Generate suggestions
                    suggestions.forEach(function (suggestion) {
                        suggestionHTML += `<div onclick="selectSuggestion('${suggestion}', '${suggestionsDiv}')">${suggestion}</div>`;
                    });

                    document.getElementById(suggestionsDiv).innerHTML = suggestionHTML;
                }
            };
            xhr.send();
        }

        // Fill the input field with the selected suggestion
        function selectSuggestion(value, suggestionsDiv) {
            const inputField = suggestionsDiv === "start_suggestions" ? "start_location" : "end_location";
            document.getElementById(inputField).value = value;
            document.getElementById(suggestionsDiv).innerHTML = ""; // Clear suggestions
        }

        function closeModal() {
            document.getElementById("successModal").style.display = "none";
            window.location.href = "../index.php";
        }

        // Close modal if user clicks outside the content
        window.onclick = function (event) {
            const modal = document.getElementById("successModal");
            if (event.target === modal) {
                modal.style.display = "none";
                window.location.href = "../index.php";
            }
        };

    </script>
    <script>
        function previewImage(event) {
            const photoPreview = document.getElementById('photoPreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    photoPreview.style.display = 'block';
                    photoPreview.src = e.target.result;  // Set the src to the selected image
                }

                reader.readAsDataURL(file);  // Read the file as a Data URL
            }
        }
    </script>


</body>

</html>

<?php include('../includes/footer.php'); ?>